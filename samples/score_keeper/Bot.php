<?php
require '../../../../../vendor/autoload.php';
include(__DIR__ . '/RedisHelper.php');

use \Baidu\Duer\Botsdk\Card\TextCard;
use \Baidu\Duer\Botsdk\Card\StandardCard;
use \Baidu\Duer\Botsdk\Card\ListCard;
use \Baidu\Duer\Botsdk\Card\ListCardItem;

class Bot extends Baidu\Duer\Botsdk\Bot
{
    private $user_id;
    private $helper; //redis_helper 连接实例
    private $user_key;

    /**
     * @desc 构造函数
     * @param $postData 
     * @return null
     * */
    public function __construct($postData = []){
        parent::__construct($postData);
        //开启校验请求参数签名，php得支持open ssl扩展
        //$this->certificate->enableVerifyRequestSign();

        $this->log = new \Baidu\Duer\Botsdk\Log([
            'path' => 'log/',
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
            ]);

        $this->user_id = $this->request->getUserId();
        $this->user_key = 'data_' . $this->user_id;
        $this->helper = new RedisHelper();
        $request = $this->request->getData();
        $this->log->setField('[request]', json_encode($request, JSON_UNESCAPED_UNICODE));

        // 意图1： 启动意图
        $this->addLaunchHandler('launch');

        // 意图2: 添加选手
        $this->addIntentHandler('add_player', 'add_player');

        //意图3： 删除选手
        $this->addIntentHandler('remove_player', 'remove_player');

        //意图4: 给选手加分
        $this->addIntentHandler('add_score', 'add_score');

        //意图5：给选手扣分
        $this->addIntentHandler('minus_score', 'minus_score');

        //意图6：查询选手得分
        $this->addIntentHandler('search_score', 'search_score');

        //意图7：查询有哪些参赛选手
        $this->addIntentHandler('query_players', 'query_players');

        //意图8： 重置比赛，清零所有选手的分数
        $this->addIntentHandler('reset_game', 'reset_game');

        // 意图9：开始新比赛，清除已有的记分数据
        $this->addIntentHandler('open_new_game', 'open_new_game');

        // 意图10： 退出记分，删除redis 中key为data_userid的键
        $this->addIntentHandler('exit_score', 'exit_score');

        // 意图11： 退出（保存所有记分数据）
        $this->addSessionEndedHandler('session_end');
    }


    //===================================意图的实现函数，单独提出==================================================

    /**
     * @desc 启动意图的实现函数
     * @param null
     * @return array
     * */
    public function launch() {
        $con = $this->helper->getConnection();
        // redis 服务器连接失败
        if (!$con) {
            return $this->getStandardCard('记分器打开失败', '连接服务器失败，暂时无法提供服务');
        }
        else {
            $this->waitAnswer();
            
            $card = new StandardCard();
            $card->setTitle('欢迎使用记分器');
            $card->setContent('计分器已打开，现在你可以为你的比赛增加参赛选手了,你可以说增加选手张三');
            $card->setImage('http://cbu01.alicdn.com/img/ibank/2017/013/887/4166788310_562231958.jpg');
            return [
                'card' => $card,
                'outputSpeech' => '计分器已打开，现在你可以为你的比赛增加参赛选手了,你可以说增加选手张三',
                'reprompt' => '你可以这样添加选手，如说添加选手张三',
                ];
        }
    }

    /**
     * @desc 增加选手意图的实现函数
     * @param null
     * @return array
     * */
    public function add_player() {
        $player = $this->getSlot('player');
        //未获取到选手名字，请求重新输入
        if (!$player) {
            $this->nlu->ask('player');
            $this->waitAnswer();
            return $this->getStandardCard(
                '添加选手失败', 
                '没听清选手名字，能再说一遍吗?',
                '如要增加选手可以这样说，增加选手张三'
            );

        } //获取到选手名字，但是选手已存在
        else if ($this->isExists($player)) {
            $this->waitAnswer();
            return $this->getStandardCard(
                '添加选手失败',
                '当前选手已存在，请重新添加新的选手或者给已有选手加分',
                '如要查询比分，您可以说查询比分或查询选手张三得分'
            );
        } //获取到要添加的选手，进行添加
        else {
            $ret = $this->addPlayer($player);
            if($ret == 0){  // redis 修改数据不成功
                return $this->getStandardCard(
                    '添加选手失败',
                    '添加选手失败，添加数据异常'
                );
            }

            $this->waitAnswer();
            return $this->getStandardCard(
                '添加选手成功',
                '已添加选手' . $player . ',你可以继续添加选手或者给已有选手增加分数',
                '您可以继续增加选手，给选手加分，查询指定选手得分，或者查询场上比分'
            );
        }
    }

    /**
     * @desc 删除选手意图的实现函数
     * @param null
     * @return array
     * */
    public function remove_player() {
        $player = $this->getSlot('player');
        //未获取到选手名字，请求重新输入
        if (!$player) {
            $this->nlu->ask('player');
            $this->waitAnswer();
            return $this->getStandardCard(
                '删除选手失败',
                '没听清选手名字，能再说一遍吗?',
                '如要移除选手可以这样说，删除选手张三'
            );
        } //获取到选手名字，但是选手不存在
        else if (!$this->isExists($player)) {
            $this->waitAnswer();
            return $this->getStandardCard(
                '删除选手失败',
                '当前选手不存在，请确认',
                '如要移除选手可以这样说，删除选手张三'
            );
        } //获取到要删除的选手，进行删除
        else {
            $ret = $this->removePlayer($player);
            if($ret == 0){  // redis 修改数据不成功
                return $this->getStandardCard(
                    '删除选手失败',
                    '删除选手失败，数据操作异常'
                );
            }
            $this->waitAnswer();
            return $this->getStandardCard(
                '删除选手成功',
                '已删除选手' . $player . ',你可以继续增删选手或者给选手增减分数',
                '您可以继续增减选手，给选手加减分，查询指定选手得分，或者查询场上比分'
            );
        }
    }


    /**
     * @desc 查询分数意图的实现函数
     * @param null
     * @return array
     * */
    public function search_score() {
        if (!$this->keeperExists()) {
            $this->waitAnswer();
            return $this->getStandardCard(
                '查询分数失败',
                '当前比赛无选手，请先增加选手',
                '你可以说增加选手张三来给您的比赛添加选手'
            );
        }

        $player = $this->getSlot('player');
        //获取到选手，查询指定选手得分
        if ($player) {
            if (!$this->isExists($player)) { // 选手不存在
                $this->waitAnswer();
                return $this->getStandardCard(
                    '查询分数失败',
                    '选手' . $player . '不存在',
                    '查询选手分数可以说：选手张三得分，查询选手比分可以说查询场上比分'
                );
            } 
            //选手存在
            $score = $this->searchScore($player);
            $this->waitAnswer();
            return $this->getStandardCard(
                '查询分数成功',
                '选手' . $player . '的得分是：' . $score,
                '如要继续查询选手比分，可以说查询选手张三得分'
            );
        } //未获取到选手，则给出场上所有选手的得分
        else {

            $player_score = $this->queryAllScore();
            arsort($player_score); //按分数倒排

            if (count($player_score) == 0) { //场上选手数为0
                $this->waitAnswer();
                return $this->getStandardCard(
                    '查询分数失败',
                    '当前场上无选手，请先添加选手，您可以说添加选手张三',
                    '您可以说添加选手张三来给您的比赛增加选手哦'
                );
            } else {
                $card = new ListCard();
                $ret = '';
                $item = new ListCardItem();
                $item->setTitle('当前比赛排名如下：');
                $item->setContent('当前比赛排名如下（选手较多时仅能显示前几名）');
                $card->addItem($item);
                foreach ($player_score as $player => $score) {
                    $item = new ListCardItem();
                    $item->setTitle($player);
                    $item->setContent($player.':'.strval($score).'分');
                    $card->addItem($item);
                }
                $this->waitAnswer();
                return [
                    'card' => $card,
                    'outputSpeech' => '已为您查询到如下比分',
                    'reprompt' => '您可以继续选择：增加选手，给选手加分，或者查询指定选手得分',
                    ];
            }
        }
    }

    /**
     * @desc 选手加分意图的实现函数
     * @param null
     * @return array
     * */
    public function add_score() {
        $player = $this->getSlot('player');
        $score = $this->getSlot('score');
        // 记分文件不存在，要求先创建比赛
        if (!$this->keeperExists()) {
            $this->waitAnswer();

            return $this->getStandardCard(
                '添加分数失败',
                '当前比赛无选手,请先增加选手',
                '您可以说增加选手张三来增加选手'
            );
        } //未获取到选手名字，请求重新输入
        else if (!$player) {
            $this->nlu->ask('player');
            $this->waitAnswer();

            return $this->getStandardCard(
                '添加分数失败',
                '没听清选手名字，能再说一遍吗？',
                '我没有听清您说的选手名字呢，可以再说一遍吗？如给选手张三加3分'
            );
        } //要加分的选手不存在
        else if (!$this->isExists($player)) {
            $this->waitAnswer();
            return $this->getStandardCard(
                '添加分数失败',
                '选手' . $player . '不存在',
                '如要给选手加分可以说：给选手张三加3分'
            );
        } //已知要加分的选手，未获取到加分数值
        else if (!$score) {
            $this->nlu->ask('score');
            $this->waitAnswer();

            return $this->getStandardCard(
                '添加分数',
                '请问要给选手' . $player . '加多少分呢？',
                '我没有听清，可以再说一遍吗？如给选手张三加4分'
            );
        } //选手，加分值已获得，进行处理
        else {
            $cur_score = $this->addScore($player, $score);
            if(!is_int($cur_score)){ // redis 操作失败
                return $this->getStandardCard('添加分数失败','添加分数失败，数据操作异常');
            }

            $this->waitAnswer();
            $ret = '已给选手' . $player . '增加' . $score . '分，选手当前得分：' . $cur_score . '分';
            return $this->getStandardCard('添加分数成功', $ret, '您可以继续增加选手或者给选手加分哦');
        }
    }


    /**
     * @desc 选手扣分意图的实现函数
     * @param null
     * @return array
     * */
    public function minus_score() {
        $player = $this->getSlot('player');
        $score = $this->getSlot('score');

        // 记分文件不存在，要求先创建比赛
        if (!$this->keeperExists()) {
            $this->waitAnswer();
           
            return $this->getStandardCard(
                '扣分失败',
                '当前场上无选手，请先增加选手',
                '如要增加选手，您可以说增加选手张三'
            );
        } //未获取到选手名字，请求重新输入
        else if (!$player) {
            $this->nlu->ask('player');
            $this->waitAnswer();

            return $this->getStandardCard(
                '扣分失败',
                '没听清选手名字，能再说一遍吗？',
                '我没有听清您说的选手名字呢，可以再说一遍吗？如给选手张三扣4分'
            );
        } //已知要扣分的选手，但选手不存在
        else if (!$this->isExists($player)) {
            $this->waitAnswer();

            return $this->getStandardCard(
                '扣分失败',
                '当前选手不存在',
                '如要给选手扣分可以说:选手张三扣4分'
            );
        } //已知要扣分的选手，未获取到扣分数值
        else if (!$score) {
            $this->nlu->ask('score');
            $this->waitAnswer();

            return $this->getStandardCard(
                '扣分操作',
                '请问要给选手' . $player . '扣多少分呢？',
                '我没有听清，可以再说一遍吗？如给选手张三扣4分'
            );
        } //选手，扣分值已获得，进行处理,支持负分
        else {
            $cur_score = $this->minusScore($player, $score);

            if(!is_int($cur_score)){ // redis 操作失败
                return $this->getStandardCard('扣分失败','扣分失败，数据操作异常');
            }

            $this->waitAnswer();
            $ret = '已给选手' . $player . '扣' . $score . '分，选手当前得分：' . $cur_score . '分';
            return $this->getStandardCard('扣分成功', $ret, '您可以继续增加选手或者给选手加减分哦');
        }
    }


    /**
     * @desc 查询选手列表意图的实现函数
     * @param null
     * @return array
     * */
    public function query_players() {
        if (!$this->keeperExists()) {
            $this->waitAnswer();
            return $this->getStandardCard(
                '查询选手列表失败', 
                '当前比赛无选手，请先添加选手', 
                '如要添加选手，可以说：增加选手张三'
            );
        } else {
            $players = $this->queryPlayers();
            if (count($players) == 0) {
                $this->waitAnswer();
                return $this->getStandardCard(
                    '查询选手列表失败', 
                    '当前比赛无选手，请先添加选手', 
                    '如要添加选手，可以说：增加选手张三'
                );
            }

            //选手个数大于0时，播报已有选手
            $ret = '当前场上共有' . count($players) . '个参赛选手，他们分别是：' . PHP_EOL;
            foreach ($players as $player) {
                $ret = $ret . $player . ' ; ';
            }

            $this->waitAnswer();
            return $this->getStandardCard('参赛选手列表', $ret, '你可以继续添加选手或者给选手加分');
        }
    }

    /**
     * @desc 重置比赛意图的实现函数
     * @param null
     * @return array
     * */
    public  function reset_game() {
        $this->clearScores();
        $this->waitAnswer();
        return $this->getStandardCard(
            '重置比赛成功',
            '清除场上选手的比分，现在可以重新为他们加分了，如：给选手李四增加4分',
            '您可以这样说来给选手加分哦，如给选手张三加3分'
        );
    }

    /**
     * @desc 开始新比赛意图的实现函数
     * @param null
     * @return array
     * */
    public function open_new_game() {
        $ret = $this->removeKeeper();//清除之前的比赛数据
        if($ret == 0){ // 记分员不存在，说明当前记分员没有记分数据
            $this->waitAnswer();
            return $this->getStandardCard(
                '已开始新比赛',
                '已开始新比赛，没有需要清除的记分数据',
                '已准备好为您记分，您可以添加选手了'
            );
        }

        $this->waitAnswer();
        return $this->getStandardCard(
            '已开始新比赛',
            '已准备好为您记分，原记分数据已清除，现在可为您的比赛增加选手了，如：增加选手张三',
            '已准备好为您记分，您可以添加选手了'
        );
    }

    /**
     * @desc 退出记分器意图的实现函数
     * @param null
     * @return array
     * */
    public function exit_score() {
        $ret = $this->removeKeeper();
        if($ret == 0){ // 记分员不存在，（记分数据为空时，记分员的键就会自动删除）
            return $this->getStandardCard('已退出记分', '已退出记分，当前没有需要清除的记分数据');
        }
        return $this->getStandardCard('已退出记分', '所有记分数据已清除');
    }


    /**
     * @desc 退出意图的实现函数
     * @param null
     * @return array
     * */
    public function session_end() {
        return $this->getStandardCard('记分器已退出', '感谢您的使用，祝您生活愉快');
    }

    //============================== 辅助函数，调用redis helper提供的接口去对数据增删改查===================================


    /**
     * @desc 为指定选手加分
     * @param $player 要加分的选手
     * @param $score 要加的分数
     * @return 加分后的分数
     * */
    public function addScore($player, $score)
    {
        $this->helper->getConnection();
        //对键的指定字段加值
        $ret = $this->helper->hincrby($this->user_key, $player, $score);
        $this->helper->closeConnection();
        return $ret;
    }


    /**
     * @desc 为指定选手扣分,支持负分
     * @param $player 要扣分的选手
     * @param $score 要扣的分数
     * @return 扣分后的分数
     * */
    public function minusScore($player, $score)
    {
        $this->helper->getConnection();
        $ret = $this->helper->hincrby($this->user_key, $player, -1 * $score);
        $this->helper->closeConnection();
        return $ret;
    }


    /**
     * @desc 添加选手
     * @param $player 要添加的选手名
     * @return 1: 添加成功  0:选手已存在，添加失败
     * */
    public function addPlayer($player)
    {
        $this->helper->getConnection();
        $ret = $this->helper->hset($this->user_key, $player, 0);
        $this->helper->closeConnection();
        return $ret;
    }

    /**
     * @desc 移除选手（适用场景：选手加错了或者选手中途退赛等情况）
     * @param $player 要移除的选手名
     * @return 1：删除选手成功  0:找不到选手等其他情况
     * */
    public function removePlayer($player)
    {
        $this->helper->getConnection();
        $ret = $this->helper->hdel($this->user_key, $player);
        $this->helper->closeConnection();
        return $ret;
    }

    /**
     * @desc 查询选手的分数
     * @param $player 要查询的选手
     * @return 选手的分数,选手不存在时，返回null
     * */
    public function searchScore($player)
    {
        $this->helper->getConnection();
        $ret = $this->helper->hget($this->user_key, $player);
        $this->helper->closeConnection();
        return $ret;
    }

    /**
     * @desc 查询场上目前所有选手的比分情况
     * @param null
     * @return 比分情况
     * */
    public function queryAllScore()
    {
        $this->helper->getConnection();
        $ret = $this->helper->hgetall($this->user_key);
        $this->helper->closeConnection();
        return $ret;
    }


    /**
     * @desc 查询场上的所有选手（适用场景：用户添加选手发现已添加后，可能会想知道已添加过哪些）
     * @param null
     * @return 所有选手的列表
     * */
    public function queryPlayers()
    {
        $this->helper->getConnection();
        $ret = $this->helper->hkeys($this->user_key);
        $this->helper->closeConnection();
        return $ret;
    }


    /**
     * @desc 比赛重置，清零所有选手的分数，选手保留
     * @param null
     * @return null
     * */
    public function clearScores()
    {
        $players = $this->queryPlayers();
        $this->helper->getConnection();
        foreach ($players as $player) {
            $this->helper->hset($this->user_key, $player, 0);
        }
        $this->helper->closeConnection();
    }


    /**
     * @desc 判断当前记分员是否已存在，存在则表示已经可以通过data_userid 这个key进行后续的各种记分操作
     * @param null
     * @return  1 已存在，0 不存在
     * */
    public function keeperExists()
    {
        $this->helper->getConnection();
        $ret = $this->helper->exists($this->user_key);
        $this->helper->closeConnection();
        return $ret;
    }

    /**
     * @desc 判断选手是否已存在
     * @param $player 选手名字
     * @return  1 表示选手已存在，0 表示选手不存在
     * */
    public function isExists($player)
    {
        $this->helper->getConnection();
        $ret = $this->helper->hexists($this->user_key, $player);
        $this->helper->closeConnection();
        return $ret;
    }

    /**
     * @desc 清除当前记分员及其所有记分数据,从redis中删除key: data_userid
     * @param null
     * @return 1 删除成功，0 删除失败（不存在key等情况）
     * */
    public function removeKeeper()
    {
        $this->helper->getConnection();
        $ret = $this->helper->del($this->user_key);
        $this->helper->closeConnection();
        return $ret;
    }

    /**
     * @desc 获取标准卡片返回
     * @param $title 标准卡片的标题
     * @param $content 标准卡片文本内容
     * @param $reprompt 用户语句不相干时的提示语句, 默认为null
     * @param $speech 标准卡片的语言朗读内容,默认为null, 此时outputSpeech 与content内容相同
     * @return $ret
     * */
    public function getStandardCard($title, $content, $reprompt = null, $speech = null){
        $card = new StandardCard();
        $card->setTitle($title);
        $card->setContent($content);

        $ret = array();
        $ret['card'] = $card;
        if($reprompt){
            $ret['reprompt'] = $reprompt;
        }
        // 不传speech时，outputSpeech 与卡片的content内容相同
        if(!$speech){
            $ret['outputSpeech'] = $content; 
        }else{
            $ret['outputSpeech'] = $speech;
        }
        return $ret;
    }
}
