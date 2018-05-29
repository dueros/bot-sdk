<?php
/**
 * @desc demo
 * @author zhangzhaojing
 */

use \Baidu\Duer\Botsdk\Directive\Display\Hint;
use \Baidu\Duer\Botsdk\Directive\Display\RenderTemplate;
use \Baidu\Duer\Botsdk\Directive\Display\Template\BodyTemplate1;
use \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplate1;
use \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplateItem;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\PlayerInfo;
use \Baidu\Duer\Botsdk\Directive\VideoPlayer\Play as VideoPlay;
use \Baidu\Duer\Botsdk\Directive\VideoPlayer\Stop as VideoStop;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play as AudioPlay;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Stop as AudioStop;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\PlayPauseButton;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\NextButoon;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\PreviousButton;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\ShowPlayListButton;
class Bot extends \Baidu\Duer\Botsdk\Bot{

    const SERVER_VIDEO = '视频模板';
    const SERVER_AUDIO = '音频模板';

    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    //IMAGE_URL
    const IMAGE_VIDEO = 'http://dbp-resource.gz.bcebos.com/zhaojing_demo/1.jpg?authorization=bce-auth-v1%2Fbc881876e7a94578935a868716b6cf69%2F2018-05-29T06%3A43%3A48Z%2F-1%2Fhost%2F57cfa880c01aef30b0b2c258231c81f4f887da9db67f424ca22985ef84a69fd1';
    const IMAGE_AUDIO = 'http://dbp-resource.gz.bcebos.com/zhaojing_demo/2.jpg?authorization=bce-auth-v1%2Fbc881876e7a94578935a868716b6cf69%2F2018-05-29T06%3A44%3A15Z%2F-1%2Fhost%2F63b930bae44a50b66940fc04ea617448506f690a86db4f90dc6b9d635e2b8ce3';

    public static $servers = array(
        self::SERVER_VIDEO,
        self::SERVER_AUDIO,
    );

    /**
     * encode token
     * @param $array
     * @return json
     */
    function genToken($array){
        $json = json_encode($array);
        return base64_encode($json);
    }
    /**
     * decode token
     * @param $token
     * @return array
     */
    function decodeToken($token){
        $json = base64_decode($token);
        return json_decode($json, true);
    }
    /**
     * @param null
     * @return null
     */
     public function __construct($postData = []) {
        parent::__construct($postData);
        
        $this->log = new \Baidu\Duer\Botsdk\Log([
            //日志存储路径
            'path' => 'log/',
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
        ]);
        // 记录这次请求
        $requestJson = json_encode($this->request->getData());
        $this->log->setField('[logid]',$this->request->getLogId());
        $this->log->setField('[request]',$requestJson);
        
        //意图1:处理技能启动
        $this->addLaunchHandler('launch');
        //意图2：处理技能结束
        $this->addSessionEndedHandler('sessionEndedRequest');
        //意图3：视频模板界面
        $this->addIntentHandler('video', 'videoIntent');
        //意图4：音频模板界面
        $this->addIntentHandler('audio', 'audioIntent');
        //意图5：选择第几个
        $this->addIntentHandler('ai.dueros.common.choose_action', 'chooseIntent');
        //意图6：暂停播放
        $this->addIntentHandler('ai.dueros.common.pause_intent', 'pauseIntent');
        //意图7：继续播放
        $this->addIntentHandler('ai.dueros.common.continue_intent', 'continueIntent');
        //意图8：返回指定界面
        $this->addIntentHandler('back', 'backIntent');
        //事件1：屏幕点击事件
        $this->addEventListener('Display.ElementSelected', 'ScreenClickedEvent');
        //事件2：音频播放结束事件
        $this->addEventListener('AudioPlayer.PlaybackFinished', 'audioPlaybackFinished');
        //事件3：视频播放结束事件
        $this->addEventListener('VideoPlayer.PlaybackFinished', 'videoPlaybackFinished');
        //事件4：音频上报
        $this->addEventListener('AudioPlayer.ProgressReportIntervalElapsed', 'defaultEvent');
        //事件5：视频上报
        $this->addEventListener('VideoPlayer.ProgressReportIntervalElapsed', 'defaultEvent');
        //事件6：兜底
        $this->addDefaultEventListener('defaultEvent');

    }

    /**
     * launch意图
     * @param null
     * @return array
     */
    function launch(){

        $this->waitAnswer();
        $template = $this->getHomeCard();
        $server = self::$servers[array_rand(self::$servers)];
        $speech = '欢迎使用平台样例演示，请试着说' . $server;
        $reprompt = '没有听懂，可以直接对我想要使用的服务，例如' . $server;
        $hint = new Hint($server);

        return [
            'outputSpeech' => $speech,
            'reprompt' => $reprompt,
            'directives' => [$hint, $template]
        ];
    }

    /**
     * 视频意图
     * @param null
     * @return array
     */
    function videoIntent(){
        $this->waitAnswer();

        $videoName = $this->getSlot('videoname');
        if($videoName){
            $video = $this->getDetailBy("video","title",$videoName);
            $directives = $this->getVideoPlay($video["id"]);
            if($directives){
                return [
                    'directives' => $directives
                ];
            }else{
                $speech = "没有找到你要播放的视频";
                $hint = new Hint('第一个','我想看告白气球');
                $template = $this->getVideoCard();
                return [
                    'outputSpeech' => $speech,
                    'directives' => [$hint,$template],
                ];
            }
        }
        $speech = '请选择您要播放的视频';
        $reprompt = '没有听懂，请告诉我想要播放的视频';
        $template = $this->getVideoCard();
        
        //定义hint指令
        $hint = new Hint('第一个','我想看告白气球');
        return [
            'outputSpeech' => $speech,
            'reprompt' => $reprompt,
            'directives' => [$hint,$template],
        ];
    }

    /**
     * 音频意图
     * @param null
     * @return array
     */
    function audioIntent(){
        $this->waitAnswer();

        $audioName = $this->getSlot('audioname');
        if($audioName){
            $audio = $this->getDetailBy("audio","title",$audioName);
            $directives = $this->getAudioPlay($audio["id"]);
            if($directives){
                return [
                    'directives' => $directives
                ];
            }else{
                $speech = "没有找到你要播放的视频";
                $hint = new Hint('第一个','我想听告白气球');
                $template = $this->getAudioCard();
                return [
                    'outputSpeech' => $speech,
                    'directives' => [$hint,$template],
                ];
            }
        }
        $speech = '请选择你想要听的歌曲';
        $reprompt = '没有听懂，请告诉我想要听的歌曲';
        $template = $this->getAudioCard();
        
        //定义hint指令
        $hint = new Hint('第一个', '我想听告白');

        return [
            'outputSpeech' => $speech,
            'reprompt' => $reprompt,
            'directives' => [$hint,$template],
        ];
    }

    /**
     * 选择意图
     * @param null
     * @return array
     */
    function chooseIntent(){
        $this->waitAnswer();

        $context = $this->request->getScreenContext();
        $token = isset($context['template']['token']) ? $context['template']['token'] : '';
    
        $tokenArr = $this->decodeToken($token);
        $page = isset($tokenArr['page']) ? $tokenArr['page'] : '';
        $index = $this->getSlot('index');
        //index从1开始
        $audioPlayerContext = $this->request->getAudioPlayerContext();
        $videoPlayerContext = $this->request->getVideoPlayerContext();

        $audioToken = isset($audioPlayerContext['token']) ? $audioPlayerContext['token'] : '';
        $videoToken = isset($videoPlayerContext['token']) ? $videoPlayerContext['token'] : '';

        $audioTokenArr = $this->decodeToken($audioToken);
        $videoTokenArr = $this->decodeToken($videoToken);

        if($page == 'home'){
            if($index == '1'){
                return $this->videoIntent(); 
            } 
            if($index == '2'){
                return $this->audioIntent(); 
            } 
        } 
        if($page == 'video'){
            $directives = $this->getVideoPlay($index);
            return [
                'directives' => $directives
            ]; 
        }
        if($page == 'audio'){
            $directives = $this->getAudioPlay($index); 
            return [
                'directives' => $directives
            ]; 
        }
    }
    /**
     * 返回意图
     * @param null
     * @return array
     */
    function backIntent(){
        $this->waitAnswer();
        $back = $this->getSlot('back');
        if(!$back){
            $back = 'home';     
        }
        if($back == '视频模板'){
            return $this->videoIntent();
        }
        if($back == '音频模板'){
            return $this->getaudioCard();
        }
        if($back == 'home'){
            $template = $this->getHomeCard();
            $speech = '欢迎使用平台样例，请试着说出'.(self::$servers[array_rand(self::$servers)]);
            $directive = new Hint(self::$servers[array_rand(self::$servers)]);
            return [
                'outputSpeech' => $speech, 
                'directives' => [$directive, $template]
            ];
        }
    }
    /**
     * 暂停播放意图
     * @param null
     * @return array
     */
    function pauseIntent(){
        $this->waitAnswer();
        $this->setExpectSpeech(false);

        $audioPlayerContext = $this->request->getAudioPlayerContext();
        $videoPlayerContext = $this->request->getVideoPlayerContext();

        $audioToken = isset($audioPlayerContext['token']) ? $audioPlayerContext['token'] : '';
        $videoToken = isset($videoPlayerContext['token']) ? $videoPlayerContext['token'] : '';

        $audioTokenArr = $this->decodeToken($audioToken);
        $videoTokenArr = $this->decodeToken($videoToken);

        if($audioPlayerContext){
            $directive = new AudioStop(); 
            return [
                'directives' => [$directive]
            ];
        }
        if($videoPlayerContext){
            $directive = new VideoStop(); 
            return [
                'directives' => [$directive]
            ];
        }
        return $this->defaultRes();
    }
    /**
     * 继续播放意图
     * @param null
     * @return array
     */
    function continueIntent(){
        $this->waitAnswer();

        $audioPlayerContext = $this->request->getAudioPlayerContext();
        $videoPlayerContext = $this->request->getVideoPlayerContext();

        if($audioPlayerContext){
            $audioToken = isset($audioPlayerContext['token']) ? $audioPlayerContext['token'] : '';
            $audioTokenArr = $this->decodeToken($audioToken);      
            $id = $audioTokenArr['id'];
            $directives = $this->getAudioPlay($id);
            return [
                'directives' => $directives
            ];
        }
        if($videoPlayerContext){
            $videoToken = isset($videoPlayerContext['token']) ? $videoPlayerContext['token'] : '';
            $videoTokenArr = $this->decodeToken($videoToken);

            $id = $videoTokenArr['id'];
            $directives = $this->getVideoPlay($id);
            return [
                'directives' => $directives
            ];
        }
        return $this->defaultRes();
    }

    /**
     * 屏幕点击事件
     * @param null
     * @return array
     */
    function ScreenClickedEvent(){
        $this->waitAnswer();
        $data = $this->request->getData();
        $url = isset($data['request']['token']) ? $data['request']['token'] : '';
        if(!$url){
            $this->setExpectSpeech(false);
            return;
        }

        $token = $this->decodeToken($url);
        $page = isset($token['page']) ? $token['page'] : '';
        //item 是当前页面
        $item = isset($token['item']) ? $token['item'] : '';

        if($page == 'home' && $item == 'video'){
            return $this->videoIntent(); 
        }
        if($page == 'home' && $item == 'audio'){
            return $this->audioIntent(); 
        }
        if($page == 'video'){
            //page如果不为home，item则存的是id
            $directives = $this->getVideoPlay($item);
            return [
                'directives' => $directives
            ]; 
        }
        if($page == 'audio'){
            $directives = $this->getAudioPlay($item); 
            return [
                'directives' => $directives
            ]; 
        }
    }

    /**
     * audio PlaybackFinished
     * @param array $event
     * @return array
     */
    function audioPlaybackFinished($event){
        $this->waitAnswer();
        $this->setExpectSpeech(false);
        $audioToken = isset($event[0]['token']) ? $event[0]['token'] : '';
        $audioTokenArr = $this->decodeToken($audioToken);
        if(isset($audioTokenArr['id']) && isset($audioTokenArr['index'])){
            $id = $audioTokenArr['id'];
            $id = intval($id) +1;
            $directives = $this->getAudioPlay(strval($id));
            return [
                'directives' => $directives
            ];
        }
    }

    /**
     * video PlaybackNearlyFinished
     * @param array $event
     * @return array
     */
    function videoPlaybackNearlyFinished($event){
        $this->waitAnswer();
        $this->setExpectSpeech(false);
        $videoToken = isset($event[0]['token']) ? $event[0]['token'] : '';
        $videoTokenArr = $this->decodeToken($videoToken);

        if(isset($videoTokenArr['id']) && isset($videoTokenArr['index'])){
            $id = $videoTokenArr['id'];
            $id = intval($id) +1;
            $directives = $this->getVideoPlay(strval($id));
            return [
                'directives' => $directives
            ];
        }
    }

    /**
     * 默认事件
     * @param null
     * @return array
     */
    function defaultEvent($event){
        $this->waitAnswer();
        $this->setExpectSpeech(false);
    }
    
    
    /**
     * 获取主页卡片
     * @param null
     * @return Card
     */
    public function getHomeCard(){

        $videoToken = array(
            'page' => 'home',
            'item' => 'video'
        );
        $audioToken = array(
            'page' => 'home',
            'item' => 'audio'
        );
        $token = array(
            'page' => 'home'
        );

        $listTemplate = new ListTemplate1();
        //设置模板token
        $listTemplate->setToken($this->genToken($token));
        //设置模版标题
        $listTemplate->setTitle('样例演示');

        //视频模板
        $listTemplateItem = new ListTemplateItem();
        $listTemplateItem->setToken($this->genToken($videoToken));
        $listTemplateItem->setImage(self::IMAGE_VIDEO);
        $listTemplateItem->setPlainPrimaryText('1  ' . self::SERVER_VIDEO);
        $listTemplate->addItem($listTemplateItem);

        //音频模板
        $listTemplateItem = new ListTemplateItem();
        $listTemplateItem->setToken($this->genToken($audioToken));
        $listTemplateItem->setImage(self::IMAGE_AUDIO);
        $listTemplateItem->setPlainPrimaryText('2  ' . self::SERVER_AUDIO);
        $listTemplate->addItem($listTemplateItem);

        //定义RenderTemplate指令
        $directive = new RenderTemplate($listTemplate);
        return $directive;
    }
    /**
     * 视频界面卡片
     * @param null
     * @return array
     */
    function getVideoCard(){
        $listTemplate = new ListTemplate1();
        //设置token
        $tokenArr = array(
                'page' => 'video',
            );
        $listTemplate->setToken($this->genToken($tokenArr));
        //设置模版标题
        $listTemplate->setTitle('视频示例');

        $videoList = $this->getPlayList(self::TYPE_VIDEO);
        if(is_array($videoList) && $videoList){
            foreach($videoList as $video){
                $id = isset($video['id']) && $video['id']?$video['id']:'';
                $title = isset($video['title'])&&$video['title']?$video['title']:'';
                $introduction = isset($video['intro'])&&$video['intro']?$video['intro']:'';
                $picUrl = isset($video['picurl'])&& $video['picurl']?$video['picurl']:'';

                $token = array(
                    'page' => 'video',
                    'item' => strval($id)
                );                            
                //设置模版列表数组listItems其中一项，即列表的一个元素
                $listTemplateItem = new ListTemplateItem();
                $listTemplateItem->setToken($this->genToken($token));
                $listTemplateItem->setImage($picUrl);
                $listTemplateItem->setPlainPrimaryText($title);
                $listTemplateItem->setPlainSecondaryText($introduction);            
                //把listTemplateItem添加到模版listItems
                $listTemplate->addItem($listTemplateItem);
            }
        }  
        //定义RenderTemplate指令
        $template = new RenderTemplate($listTemplate);
        return $template;
    }
    /**
     * 音频界面卡片
     * @param null
     * @return array
     */
    function getAudioCard(){
        $listTemplate = new ListTemplate1();
        //设置模板token
        $tokenArr = array(
            'page' => 'audio',
        );
        $listTemplate->setToken($this->genToken($tokenArr));
        //设置模板标题
        $listTemplate->setTitle('音频示例');
        //getaudioCard
        $audioList = $this->getPlayList(self::TYPE_AUDIO);
        if(is_array($audioList)&&$audioList){
            foreach($audioList as $audio){
                $id = isset($audio['id']) && $audio['id']?$audio['id']:'';
                $title = isset($audio['title']) && $audio['title']?$audio['title']:'';
                $introduction = isset($audio['intro']) && $audio['intro']?$audio['intro']:'';
                $picUrl = isset($audio['picurl']) && $audio['picurl']?$audio['picurl']:'';
            
                $token = array(
                    'page' => 'audio',
                    'item' => strval($id)
                );   
                //设置模版列表数组listItems其中一项，即列表的一个元素
                $listTemplateItem = new ListTemplateItem();
                $listTemplateItem->setToken($this->genToken($token));
                $listTemplateItem->setImage($picUrl);
                $listTemplateItem->setPlainPrimaryText($title);
                $listTemplateItem->setPlainSecondaryText($introduction);
                
                //把listTemplateItem添加到模版listItems
                $listTemplate->addItem($listTemplateItem);
            }
        }
        //定义RenderTemplate指令
        $template = new RenderTemplate($listTemplate);
        return $template;
    }
    /**
     * 获取音频或视频的播放列表
     * @param $type
     * @return array
     */
    function getPlayList($type){
        $list = file_get_contents(__DIR__.'/data.json');
        $list = json_decode($list,true);

        if($type === "video"){
            if(isset($list["video"])&& $list["video"]) {
                return $list["video"];
            }
        }
        if($type === "audio"){
            if(isset($list["audio"])&& $list["audio"]) {
                return $list["audio"];
            }
        }
        //此时应该返回为false；
        return $list;
    }
    /**
     * 视频播放
     * @param string $id
     * @return array
     */
    function getVideoPlay($id){
        $this->setExpectSpeech(false);

        $token = array(
            'type' => 'video',
            'id' => $id,
        );

        $video = $this->getDetailBy("video","id",$id);

        if(is_array($video)&&$video) {
            $directive = new VideoPlay($video['url'],'REPLACE_ALL');
            $directive->setReportIntervalInMs(10000);
            $directive->setReportDelayInMs(10000);
            $directive->setOffsetInMilliSeconds(0);
            $directive->setToken($this->genToken($token));

            $hint = new Hint(['返回视频模板']);
            $directives[] = $directive;
            $directives[] = $hint;
        }
        return $directives; 
    }
    /**
     * 音频播放
     * @param $id
     * @return array
     */
    function getAudioPlay($id){
        $this->setExpectSpeech(false);
 
        $token = array(
            'type' => 'audio',
            'id' => $id,
        );

        $audio = $this->getDetailBy("audio","id",$id);
        if(is_array($audio)&&$audio) {
            $directive = new AudioPlay($audio['url'], 'REPLACE_ALL');
            $directive->setOffsetInMilliSeconds(0);
            
            $playerInfo = new PlayerInfo();
            $playpause = new PlayPauseButton();
            $previous = new PreviousButton();
            
            $next = new NextButoon();
            $showPlayList = new ShowPlayListButton();
            $showPlayList->setEnabled(false);

            $controls = array(
                $playpause, $previous, $next, $showPlayList
            );

            $playerInfo->setControls($controls);
            $playerInfo->setTitle($audio['title']);
            $playerInfo->setTitleSubtext1($audio['intro']);

            $directive->setPlayerInfo($playerInfo);
            $directive->setToken($this->genToken($token));
            $hint = new Hint(['返回音频模板']);

            $directives[] = $directive;
            $directives[] = $hint;
        }
        return $directives;
    }

    /**
     * 根据名字或者id来获取音频或者视频
     * @param $type "video"|"audio"
     * @param $element "title"|"id"
     * @param $value $name|$id
     * @return array
     */
    function getDetailBy($type,$element,$value){
        if($type === "video"){
           $videoList = $this->getPlayList($type);
            if(is_array($videoList)&&$videoList){
                foreach ($videoList as $video) {
                    $temp = isset($video[$element]) && $video[$element]?$video[$element]:'';
                    if($temp == $value){
                        return $video;
                    }
                }
            } 
        }
        if($type === "audio"){
            $audioList = $this->getPlayList($type);
            if(is_array($audioList)&&$audioList){
                foreach ($audioList as $audio) {
                    $temp = isset($audio[$element]) && $audio[$element]?$audio[$element]:'';
                    if($temp == $value){
                        return $audio;
                    }
                }
            }
        }
        return false;
    }

    /**
     * 兜底结果
     * @param null
     * @return array
     */
    function defaultRes(){
        $this->setExpectSpeech(false);
        return [
            'outputSpeech' => '平台样例演示' 
        ]; 
    }
    /**
     * sessionEndedRequest处理
     * @param null
     * @return array
     */
    function sessionEndedRequest(){
        $this->endDialog(); 
    }
}
