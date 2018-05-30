# BOT-SDK的使用

## 模板样例演示

完成对sdk的安装之后，那么如何使用sdk开发一个属于自己的bot呢？
让我们以一个模板示例来开始sdk的使用之旅。

我们先来看下在控制台上新建的bot意图以及各个意图的槽位。
在这里我们创建了如下意图：
![意图](http://dbp-resource.gz.bcebos.com/zhaojing_demo/intent.png?authorization=bce-auth-v1%2Fbc881876e7a94578935a868716b6cf69%2F2018-05-29T11%3A20%3A50Z%2F-1%2Fhost%2F9319c0576c2cf3fd6b5ee11874633ca83fcdf09e5f8971d6454f517398aa356e)

右边有dueros的标志的意图表明此为系统意图，可在创建时直接引用。

 - 在audio意图中，我们创建了一个槽位：audioname，对应视频名词典；
 - 在video意图中，我们创建了一个槽位：videoname，对应音频名词典；
 - 在back意图中，创建了槽位：back，用来指定返回到哪一层界面。

我们创建了三个词典：
![词典](http://dbp-resource.gz.bcebos.com/zhaojing_demo/BaiduHi_2018-5-29_18-2-58.png?authorization=bce-auth-v1/bc881876e7a94578935a868716b6cf69/2018-05-29T10:03:19Z/-1/host/149be9f69c292c36ce7a0eb8c0ffc8fce9cf18b26b44fdcc4b2ce8a051591e8d)

- back词典，可以指定返回到某个界面
- 视频名，要播放的视频名字
- 音频名，要播放的音频名字

首先，我们新建一个目录[demo],使用vim新建一个[index.php]文件，在文件中添加如下代码：

```php
<?php
/**
 * @desc 入口文件
 */                                                                  
require '../../../../../vendor/autoload.php';
require (__DIR__.'/src/Bot.php');

$demo = new Bot();
//执行
$ret = $demo->run();

print $ret;
```

在此入口文件中，我们引入了sdk的autoload文件以及实现bot最核心的代码文件bot.php。
你先需要require autoload.php文件，这个文件一般在vendor目录，如果没有这个目录，请先执行composer dump-autoload。	

接下来，让我们开始正式的bot开发吧！
我们新建一个目录[demo/src],该目录下存放我们自己定义的bot代码文件。
使用vim新建一个文件[bot.php]。

首先，我们需要引入sdk中的指令directive
```php
use \Baidu\Duer\Botsdk\Directive\Display\Hint;
use \Baidu\Duer\Botsdk\Directive\Display\RenderTemplate;
use \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplate1;
use \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplateItem;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\PlayerInfo;
use \Baidu\Duer\Botsdk\Directive\VideoPlayer\Stop as VideoStop;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play as AudioPlay;
```
此处只列举几个，可根据自己bot开发的需求引入不同的directive

根据我们创建的意图需要在构造函数中将意图与函数绑定，部分代码如下：
```php
class Bot extends \Baidu\Duer\Botsdk\Bot{
    public function __construct($postData = []) {
        parent::__construct($postData);
        //意图1:处理技能启动
        $this->addLaunchHandler('launch');
        //意图2：处理技能结束
        $this->addSessionEndedHandler('sessionEndedRequest');
        //意图3：视频模板界面
        $this->addIntentHandler('video', 'videoIntent');
        //事件1：屏幕点击事件
        $this->addEventListener('Display.ElementSelected', 'ScreenClickedEvent');
    }
}
```
第一个参数是意图的名字，第二个参数对应实现函数的函数名。
我们将各个意图、事件与函数绑定后，可以在函数中自己定义功能。

接下来，我们添加意图函数的定义，luanch函数是整个bot的启动入口。
```php
    /**
     * launch意图
     * @return array
     */
    function launch(){
        $this->waitAnswer();
        $template = $this->getHomeCard();
        $speech = '欢迎使用平台样例演示，请试着说打开技能';
        $reprompt = '没有听懂，可以直接对我想要使用的服务，例如' . $server;
        $hint = new Hint('打开技能');
        return [
            'outputSpeech' => $speech,
            'reprompt' => $reprompt,
            'directives' => [$hint, $template]
        ];
    }
```
以audioIntent为例，详细讲解一下如何使用hint指令以及template指令：
```php
    /**
     * 音频意图
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

```
 
**hint指令**是对用户的一些提示语。我们可以通过:
```$hint = new Hint('第一个','我想看告白气球');```
来创建一个hint指令，它会在端设备的底部显示。

**template展现模板**，sdk为开发者提供了多种展现模板。具体使用可见getAudioCard()函数中。
我们以横向列表模板ListTemplate1为例：
```
use \Baidu\Duer\Botsdk\Directive\Display\RenderTemplate;
use \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplate1;
use \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplateItem;

$listTemplate = new ListTemplate1();
//设置模板token
$listTemplate->setToken('token');
//设置模板背景图
$listTemplate->setBackGroundImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg');
//设置模版标题
$listTemplate->setTitle('托尔斯泰的格言');

//设置模版列表数组listItems其中一项，即列表的一个元素
$listTemplateItem = new ListTemplateItem();
$listTemplateItem->setToken('token');
$listTemplateItem->setImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg');
//or 图片设置宽和高
$listTemplateItem->setImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg', 200, 200);

$listTemplateItem->setPlainPrimaryText('一级标题');
$listTemplateItem->setPlainSecondaryText('二级标题');

//把listTemplateItem添加到模版listItems
$listTemplate->addItem($listTemplateItem);
//定义RenderTemplate指令
$directive = new RenderTemplate($listTemplate);
return [
    'directives' => [$directive],
    'outputSpeech' => '这是ListTemplate1模板',
];
```
**列表卡片点击事件处理**
ScreenLinkClicked 如果卡片或者卡片列表配置了URL地址，当用户点击卡片或者卡片列表时，DuerOS会向技能发送ScreenLinkClicked事件，技能收到该事件后会返回需要展现的内容。
```
use \Baidu\Duer\Botsdk\Card\StandardCard;
$this->addEventListener('ScreenLinkClicked', function($event){
    $url = $event['url'];
    $token = $event['token'];

    $card = new StandardCard();
    $card->setTitle('title');
    $card->setContent('content');

    return [
        'card' => $card,
    ];
});
```
**图片卡片**
ImageCard
```
$card = new ImageCard();
$card->addItem('http://src.image', 'http://thumbnail.image');
directive返回指令
```
**音乐播放指令**
AudioPlayer.Play
```
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play;

$directive = new Play('http://www.music', Play::REPLACE_ALL); 
return [
    'directives' => [$directive],
    'outputSpeech' => '正在为你播放歌曲',
];
```
**渲染音频播放器的主界面**
AudioPlayer.Play指令中增加playerInfo信息
```
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\PlayerInfo;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\PlayPauseButton;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\NextButoon;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\PreviousButton;
//创建音频播放指令
$directive = new Play('http://www.music', Play::REPLACE_ALL);

//音频播放器的主界面
$playerInfo = new PlayerInfo();

//创建暂停按钮
$playpause = new PlayPauseButton();
$previous = new PreviousButton();
$controls = array(
    $playpause, 
    $previous
);

//设置PlayerInfo的Controls内容
$playerInfo->setControls($controls);

//也可以使用addControl,增加一个control
$playerInfo->addControl(new NextButoon());

$playerInfo->setTitle('周杰伦');
$playerInfo->setTitleSubtext1('七里香');

//设置Play指令的PlayerInfo
$directive->setPlayerInfo($playerInfo);
return [
    'directives' => [$directive],
    'outputSpeech' => '周杰伦,七里香',
];
```
**停止端上的播放音频**
AudioPlayer.Stop
```
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Stop;

$directive = new Stop(); 
return [
    'directives' => [$directive],
    'outputSpeech' => '已经停止播放',
];
```
**音频事件处理**
Bot可以通过addEventListener接口来监听音频播放的时的事件，下面以AudioPlayer.PlaybackNearlyFinished事件举例。
```
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play;

$this->addEventListener('AudioPlayer.PlaybackNearlyFinished', function($event){
    $token = $event['token'];
    $directive = new Play('http://www.audio', Play::ENQUEUE); 
    return [
        'directives' => [$directive],
    ];
});
```

视频的处理逻辑和音频类似。
