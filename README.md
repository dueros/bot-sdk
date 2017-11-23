# 度秘BOT SDK for PHP
这是一个帮助开发Bot的SDK，我们强烈建议您使用这个SDK开发度秘的Bot。当然，您可以完全自己来处理DuerOS的协议，自己完成session、nlu、result的处理，但是度秘的DuerOS对BOT的协议会经常进行升级，这样会给您带来一些麻烦。这个SDK会与DuerOS的协议一起升级，会最大限度减少对您开发bot的影响。

## 通过bot-sdk可以快速的开发bot
我们的目标是通过使用bot-sdk，可以迅速的开发一个bot，而不必过多去关注DuerOS对Bot的复杂协议。我们提供如下功能：

* 封装了DuerOS的request和response
* 提供了session简化接口
* 提供了nlu简化接口
    * slot 操作
    * nlu理解交互接口（ask）
* 提供了多轮对话开发接口
* 提供了事件监听接口

## 安装、使用BOT SDK进行开发 
度秘BOT SDK采用[PSR-4规范](http://www.php-fig.org/psr/psr-4/)自动加载 , PHP版本确保在5.4.0及以上。要验证请求参数来自DuerOS，php还得支持openssl扩展。使用[composer](https://getcomposer.org/)执行如下命令进行安装：
```shell
composer require dueros/bot-sdk
```

为了开始使用BOT SDK，你需要先新建一个php文件，比如文件名是Bot.php。你先需要require autoload.php文件，这个文件一般在vendor目录，如果没有这个目录，请先执行composer dump-autoload。

```javascript
require 'vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * $postData可以不传，由于DuerOS对bot是post请求，sdk默认自动获取
     */
    public function __construct($postData = []) {
       parent::__construct($postData); 

       // 开启校验请求参数签名
       // php 得支持open ssl扩展
       $this->certificate->enableVerifyRequestSign();
    }
}
```
需要继承Baidu\Duer\Botsdk\Bot。 下一步，我们处理意图。Bot-sdk提供了一个函数来handle这些意图。比如，为新建闹钟，创建一个handler，在构造函数中添加：

```php
use \Baidu\Duer\Botsdk\Card\TextCard;

$this->addIntentHandler('remind', function(){
    $remindTime = $this->getSlot('remind_time');
   
    if($remindTime) {
        $card = new TextCard('创建中');
        return [
            'card' => $card,
        ];
    }
});

/**
 * 或者，可以不通过匿名函数，还支持传入成员函数名
 */

$this->addIntentHandler('remind', 'create');

//定义一个成员函数
public function create(){
    $remindTime = $this->getSlot('remind_time');
    return [...];
}
```
这里`addHandler`可以用来建立(intent) => handler的映射，第一个参数是条件，如果满足则执行对应的回调函数(第二个参数)。
其中，$this指向当前的Bot，`getSlot`继承自父类Bot，通过slot名字来获取对应的值。回调函数返回值是一个数组，可以包含多个字段，比如：`card`，`directives`，`outputSpeech`，`reprompt`。

`card`展现卡片
### 文本卡片
`TextCard`
```php
$card = new TextCard('content');

//or
$card = new TextCard();
//设置content
$card->setContent('Content');
//设置链接
$card->setAnchor('http://www.baidu.com');
$card->setAnchor('http://www.baidu.com', 'showtext');
//设置cueWords
$card->addCueWords('hint1');
$card->addCueWords(['hint1', 'hint2']);
```

### 标准卡片
`StandardCard`

```json
$card = new StandardCard();

$card->setTitle('title');
$card->setContent('content');
$card->setImage('http://www...');
$card->setAnchor('http://www.baidu.com');
```

### 列表卡片
`ListCard`

```php
$card = new ListCard();
$item = new ListCardItem();
$item->setTitle('title')
    ->setContent('content')
    ->setUrl('http://www')
    ->setImage('http://www.png');

$card->addItem($item);
$card->addItem($item);
```
### 图片卡片
`ImageCard`

```php
$card = new ImageCard();
$card->addItem('http://src.image', 'http://thumbnail.image');
```

`directive`返回指令

### 音乐播放指令
`AudioPlayer.Play`

```php
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play;

$directive = new Play('http://www.music', Play::REPLACE_ALL); 
return [
    'directives' => [$directive],
    'outputSpeech' => '正在为你播放歌曲',
];
```

### 停止端上的播放音频

`AudioPlayer.Stop`

```php
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Stop;

$directive = new Stop(); 
return [
    'directives' => [$directive],
    'outputSpeech' => '已经停止播放',
];
```

设置好handler之后，就可以实例化刚刚定义的Bot，在webserver中接受DuerOS来的请求。比如，新建一个文件index.php，拷贝如下代码：
```php
$bot = new Bot();

header("Content-Type: application/json");
print $bot->run();

```

利用php内建的webserver，运行如下命令：
```shell
php -S 0.0.0.0:8000 index.php
```

## 返回speech
### outputSpeech
上面例子，除了返回`card`之外，还可以返回outputSpeech，让客户端播报tts：
```php
return [
    'outputSpeech' => '请问你要干啥呢',
    //或者ssml
    'outputSpeech' => '<speak>请问你要干啥呢</speak>',
];
```
### reprompt
当客户端响应用户后，用户可能会一段时间不说话，如果你返回了reprompt，客户端会提示用户输入
```php
return [
    'reprompt' => 'hello，请问你要干啥呢',
    //或者ssml
    'reprompt' => '<speak>hello，请问你要干啥呢</speak>',
];
```


## Lanuch & SessionEnd
### bot开始服务
当bot被@（通过bot唤醒名打开时），DuerOS会发送`LanuchRequest`给bot，此时，bot可以返回欢迎语或者操作提示：
```php
$this->addLaunchHandler(function(){
    return [
        'outputSpeech' => '<speak>欢迎光临</speak>' 
    ];

});

```

### bot 结束服务
当用户表达退出bot时，DuerOS会发送`SessionEndedRequest`：
```php
$this->addSessionEndedHandler(function(){
    // clear status
    // 清空状态，结束会话。 
    return null; 
});

```

## 使多轮对话管理更加简单
往往用户一次表达的需求，信息不一定完整，比如：'给我创建一个闹钟'，由于query中没有提醒的时间，一个好的bot实现会问用户：'我应该什么时候提醒你呢？'，这时用户说明天上午8点，这样bot就能获取设置时间，可以为用户创建一个闹钟。比如，你可以这样来实现：

```php
//提醒意图而且有提醒时间slot
$this->addIntentHandler('remind', function(){
    $remindTime = $this->getSlot('remind_time');
    if($remindTime) {
        return [/*设置闹钟指令*/];
    }

    //当前面条件不满足（没有提醒时间），会执行这个handler
    $this->nlu->ask('remind_time');
    $card = new TextCard('要几点的闹钟呢?');
    return [
        'card' => $card,
        'outputSpeech' => '要几点的闹钟呢?',
    ];
});

//监听events
$this->addEventListener('Alerts.SetAlertSucceeded', function($event){
    //do sth. eg. set alert status 
    $card = new TextCard('闹钟创建成功');
    return [
        'card' => $card
    ];
});

$this->addEventListener('AudioPlayer.PlaybackNearlyFinished', function($event){
    $offset = $event['offsetInMilliSeconds'];
    //todo sth，比如：返回一个播放enqueue
    //
    $directive = new Play('ENQUEUE'); 
    $directive->setUrl('http://wwww');
    return [
        'directives' => [$directive],
	];
});
```
Bot-sdk会根据通过`addIntentHandler`添加handler的顺序来遍历所有的检查条件，寻找条件满足的handler来执行回调，并且当回调函数返回值不是`null`时结束遍历，将这个不为`null`的值返回。

NLU会维护slot的值，merge每次对话解析出的slot，你可以不用自己来处理，DuerOS每次请求Bot时会将merge的slot都下发。`session`内的数据完全由你来维护，你可以用来存储一些状态，比如打车Bot会用来存储当前的订单状态。你可以通过如下接口来使用`slot`和`session`：
```php
//slot
getSlot('slot name');
setSlot('slot name', 'slot value');// 如果没有找到对应的slot，会自动新增一个slot

//session
getSessionAttribute('key');
setSessionAttribute('key', 'value');
//or
setSessionAttribute('key.key1', 'value');
getSessionAttribute('key.key1');

//清空session
clearSession();
```

你的Bot可以订阅端上触发的事件，通过接口`addEventListener`实现，比如端上设置闹钟成功后，会下发`SetAlertSucceeded`的事件，Bot通过注册事件处理函数，进行相关的操作。

## NLU交互协议
在DuerOS Bot Platform平台，可以通过nlu工具，添加了针对槽位询问的配置，包括：

* 是否必选，对应询问的默认话术
* 是否需要用户确认槽位内容，以及对应的话术
* 是否需要用户在执行动作前，对所有的槽位确认一遍，以及对应的话术

针对填槽多轮，Bot发起对用户收集、确认槽位（如果针对特定槽位有设置确认选项，就进行确认）、确认意图（如果有设置确认选项）的询问，bot-sdk提供了方便的快捷函数支持：

*注意：一次返回的对话directive，只有一个，如果多次设置，只有最后一次的生效*

### ask
多轮对话的bot，会通过询问用户来收集完成任务所需要的槽位信息，询问用户的特点总结为3点，`ask`：问一个特定的槽位。比如，打车服务收到用户的打车意图的时候，发现没有提供目的地，就可以ask `destination`(目的地的槽位名)：
```javascript
//打车意图，但是没有提供目的地
$this->addIntentHandler('rent_car.book', function(){
    $endPoint = $this->getSlot('destination');
    if(!$this->endPoint) {
        //询问slot: destination
        $this->nlu->ask('destination');
    
        $card = new TextCard('打车去哪呢');
        return [
            'card' => $card
        ];
    }
});
```

### delegate

将处理交给DuerOS的对话管理模块DM（Dialog Management），按事先配置的顺序，包括对缺失槽位的询问，槽位值的确认（如果设置了槽位需要确认，以及确认的话术）,整个意图的确认（如果设置了意图需要确认，以及确认的话术。比如可以将收集的槽位依次列出，等待用户确认）

```javascript
$this->addIntentHandler('your intent name', function(){
    if(!$this->request->isDialogStateCompleted()) {
        // 如果使用了delegate 就不再需要使用setConfirmSlot/setConfirmIntent，否则返回的directive会被后set的覆盖。
        return $this->setDelegate();
    }

    //do sth else
});
```

### confirm slot 

主动发起对一个槽位的确认，此时还需同时返回询问的outputSpeach。主动发起的确认，DM不会使用默认配置的话术。

```javascript
$this->addIntentHandler('your intent name', function(){
    if($this->getSlot('money') > 10000000000) {
        $this->setConfirmSlot('money');
        return [
            'outputSpeech' => '你确认充话费：10000000000',
        ];
    }

    //do sth else
});
```

### confirm intent

主动发起对一个意图的确认，此时还需同时返回询问的outputSpeach。主动发起的确认，DM不会使用默认配置的话术。

一般当槽位填槽完毕，在进行下一步操作之前，一次性的询问各个槽位，是否符合用户预期。

```javascript
$this->addIntentHandler('your intent name', function(){
    $money = $this->getSlot('money');
    $phone = $this->getSlot('phone');
    if($money && $phone) {
        $this->setConfirmIntent();
        return [
            'outputSpeech' => "你确认充话费：$money，充值手机：$phone",
        ];
    }

    //do sth else
});
```

## 插件
你还可以写插件(拦截器`Intercept`)，干预对话流程、干预返回结果。比如，用户没有通过百度帐号登录，bot直接让用户去登录，不响应意图，可以使用`LoginIntercept`：
```javascript
public function __construct($postData = []) {
    parent::__construct($postData);
    $this->addIntercept(new Baidu\Duer\Botsdk\Plugins\LoginIntercept());
    //...
}
```
开发自己的拦截器，继承`\Baidu\Duer\Botsdk\Intercept`，通过重载`preprocess`，能够在处理通过`addHandler`，`addEventListener`添加的回调之前，定义一些逻辑。通过重载`postprocess`能够对回调函数的返回值，进行统一的处理：
```php
class YourIntercept extends \Baidu\Duer\Botsdk\Intercept{
    public function preprocess($bot) {
        //$bot: 你的bot实例化对象
    }

    public function postprocess($bot, $result) {
        //maybe format $result
        return $result;
    }
}
```
`intercept`可以定义多个，执行顺序，以调用`addIntercept`的顺序来执行

## 如何调试
### 本地测试
bot-sdk提供了一个简单的工具，方便用户在没有接入DuerOS时调试自己的bot。
首先你需要通过PHP内置的webserver，将你的bot运行起来，这里假设是监听的`8000`端口。然后，构造你的`NLU`、`session`等数据，如个人所得税计算器bot构造的数据结构，具体可以参考`samples/personal_income_tax`中part目录的例子，比如：`./post-part.sh part/create.php`
```php
<?php
return [
    'nlu' => [
        'name' => 'personal_income_tax.inquiry',
        'slots' => [
            [
                'name' => 'monthlysalary',
                'value' => '121212',
            ],
            [
                'name' => 'compute_type',
                'value' => '个税',
            ],
        ]
    ],
    'session' => [],
];
```
### 如何打印日志
bot-sdk提供了日志打印的工具，开发者可以直接使用，当然也可以用自己习惯的日志工具。一次请求只打印一条`NOTICE`日志；`FATAL`，`WARN`日志可以打印多条。

日志按小时切分，fatal，warn日志存储到一个文件，notice日志存储到一个文件。日志输出路径可以在构造函数中通过`path`参数指定。比如`log/`表示是Bot.php同级的log目录。

可以参考`samples/personal_income_tax`的例子

#### 定义日志
```php
//可以在构造函数中执行
$this->log = new Baidu\Duer\Botsdk\Log([
    //日志存储路径
    'path' => 'log/', 
    //日志打印最低输出级别
    'level' => Baidu\Duer\Botsdk\Log::NOTICE,
]);
```

#### 统计耗时
```php
//标记开始，search_t 是对应的字段名
$this->log->markStart('search_t');
$res = \Utils::curl(['url'=>$url, 'timeout'=>2000]);
//标记结束
$this->log->markEnd('search_t');
```

#### 记录某个字段
```php
//记录这次请求的query
$this->log->setField('query', $this->request->getQuery());

//获取某个字段的值，比如，获取统计的时间
$this->log->getField('search_t');
```

#### 打印日志
*最后将NOTICE日志打印出来*
```
$bot->log->notice('remind');
```

#### 打印fatal、warn
```php
//test fatal log
$this->log->fatal("this is a fatal log");
```

## 数据统计
### BotMonitor是什么
它可以帮助您收集和分析您开发的bot运行中产生的数据，帮助您实时查看应用运行状态，及时发现应用中存在的问题，提升>用户体验。目前，BotMonitor提供应用性能分析、用户行为统计。使用BotMonitor，您可以方便的在自己的DBP平台查看Bot的用户量、会话量、请求量、QPS以及Session的相关统计数据指标。

### bot-sdk如何使用BotMonitor数据统计
在construct中使用如下方法
```php
//$privateKey为私钥内容,0代表你的Bot在DBP平台debug环境，1或者其他整数代表online环境
$this->botMonitor->setEnvironmentInfo($privateKey, 0);
//环境信息配置完成后，你需要打开BotMonitor数据采集上报开关(默认是开启的,你可以根据自己需求打开或者关闭),true代表打开，false代表关闭
$this->botMonitor->setMonitorEnabled(true);
```
具体数据统计的说明和使用可以参考BotMonitor文档
https://packagist.org/packages/monitor/bot-monitor
