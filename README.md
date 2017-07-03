# 度秘BOT SDK for PHP
这是一个帮助开发Bot的SDK，我们强烈建议您使用这个SDK开发度秘的Bot。当然，您可以完全自己来处理中控的协议，自己完成session、nlu、result的处理，但是度秘的中控对BOT的协议会经常进行升级，这样会给您带来一些麻烦。这个SDK会与中控的协议一起升级，会最大限度减少对您开发bot的影响。

## 通过bot-sdk可以快速的开发bot
我们的目标是通过使用bot-sdk，可以迅速的开发一个bot，而不必过多去关注中控对Bot的复杂协议。我们提供如下功能：

* 封装了中控的request和response
* 提供了session简化接口
* 提供了nlu简化接口
    * slot 操作
    * nlu理解交互接口（ask、select、check）
* 提供了多轮对话开发接口
* 提供了事件监听接口

## 安装、使用BOT SDK进行开发 
度秘BOT SDK采用[PSR-4规范](http://www.php-fig.org/psr/psr-4/)自动加载 , PHP版本确保在5.4.42及以上。使用[composer](https://getcomposer.org/)执行如下命令进行安装：
```shell
composer require dueros/bot-sdk:2.0.*@dev
```

为了开始使用BOT SDK，你需要先新建一个php文件，比如文件名是Bot.php。你先需要require autoload.php文件，这个文件一般在vendor目录，如果没有这个目录，请先执行composer dump-autoload。

```javascript
require 'vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * $postData可以不传，由于中控对bot是post请求，sdk默认自动获取
     */
    public function __construct($postData = []) {
       parent::__construct($postData); 
    }
}
```
需要继承Baidu\Duer\Botsdk\Bot，并在构造函数中指出关注的`domain`，比如：remind。 下一步，我们处理这个domain下的intent。Bot-sdk提供了一个函数来handle这些intent。比如，为新建闹钟，创建一个handler，在构造函数中添加：

```php
use \Baidu\Duer\Botsdk\Card\TextCard;

$this->addHandler('#remind && slot.remind_time', function(){
    $remindTime = $this->getSlot('remind_time');
   
    $card = new TextCard('创建中');
    return [
        'card' => $card,
    ];
});

/**
 * 或者，可以不通过匿名函数，还支持传入成员函数名
 */

$this->addHandler('#remind && slot.remind_time', 'create');

//定义一个成员函数
public function create(){
    $remindTime = $this->getSlot('remind_time');
    return [...];
}
```
这里`addHandler`可以用来建立(intent, slot, session) => handler的映射，第一个参数是条件，如果满足则执行对应的回调函数(第二个参数)。
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


设置好handler之后，就可以实例化刚刚定义的Bot，在webserver中接受中控来的请求。比如，新建一个文件index.php，拷贝如下代码：
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
当bot被@（通过bot唤醒名打开时），中控会发送`LanuchRequest`给bot，此时，bot可以返回欢迎语或者操作提示：
```php
$this->addHandler('LaunchRequest', function(){
    return [
        'outputSpeech' => '<speak>欢迎光临</speak>' 
    ];

});

```

### bot 结束服务
当用户表达退出bot时，中控会发送`SessionEndRequest`：
```php
$this->addHandler('SessionEndRequest', function(){
    //todo sth
    return null; 
});

```

## 使多轮对话管理更加简单
往往用户一次表达的需求，信息不一定完整，比如：'给我创建一个闹钟'，由于query中没有提醒的时间，一个好的bot实现会问用户：'我应该什么时候提醒你呢？'，这时用户说明天上午8点，这样bot就能获取设置时间，可以为用户创建一个闹钟。比如，你可以这样来实现：

```php
//提醒意图而且有提醒时间slot
$this->addHandler('#remind && slot.remind_time', function(){
    $remindTime = $this->getSlot('remind_time');
    return [/*设置闹钟指令*/];
});

//当前面条件不满足（没有提醒时间），会执行这个handler
$this->addHandler('#remind', function(){
    $this->nlu->needAsk('remind_time');
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
```
Bot-sdk会根据通过`addHandler`添加handler的顺序来遍历所有的检查条件，寻找条件满足的handler来执行回调，并且当回调函数返回值不是`null`时结束遍历，将这个不为`null`的值返回。
条件是关系运算表达式，可以使用：`intent`, `slot`, `session`参与条件运算：
```javascript
//intent，以#开头，后接具体的intent名
#remind 
#rent_car.book

//session,  'session'是条件表达式的保留字。session是key-value结构，你可以完全自由的使用。
session.key   //取session['key']
session.key.key1 //取session['key']['key1']

//slot, 'slot'是条件表达式的保留字。slot来自NLU的解析结果
slot.remind_time  //取nlu中 remind_time 槽位
slot.car_type     //取nlu中 car_type 槽位
```
条件表达式`session`, `slot`是保留字，不能作为字段名使用。表达式只支持关系运算，不支持函数，自定义变量引用。
NLU会维护slot的值，merge每次对话解析出的slot，你可以不用自己来处理，中控每次请求Bot时会将merge的slot都下发。`session`内的数据完全由你来维护，你可以用来存储一些状态，比如打车Bot会用来存储当前的订单状态。你可以通过如下接口来使用`slot`和`session`：
```php
//slot
getSlot('slot name');
setSlot('slot name', 'slot value');// 如果没有找到对应的slot，会自动新增一个slot

//session
getSession('key');
setSession('key', 'value');
//or
setSession('key.key1', 'value');
getSession('key.key1');

//清空session
clearSession();
```

你的Bot可以订阅端上触发的事件，通过接口`addEventListener`实现，比如端上设置闹钟成功后，会下发`SetAlertSucceeded`的事件，Bot通过注册事件处理函数，进行相关的操作。

## NLU交互协议ask
多轮对话的bot，会通过询问用户来收集完成任务所需要的信息（slot），询问用户的特点总结为3点，`ask`：问啥啥。如果你的bot在询问用户的时候，也能告诉NLU你的bot在问什么，能在用户回答你的问题时，NLU可以很针对性的去解析用户的query，大大提高理解用户的准确率，你bot的多轮对话在用户看来就是非常流畅的。bot-sdk提供了接口`needAsk`，帮助你完成这些工作：
```javascript
//打车intent，但是没有提供目的地
$this->addHandler('#rent_car.book && !slot.end_point', function(){
    //询问slot: end_point
    $this->nlu->needAsk('end_point');

    $card = new TextCard('打车去哪呢');
    return [
        'card' => $card
    ];
});

## 插件
你还可以写插件(拦截器`Intercept`)，干预对话流程、干预返回结果。比如，用户没有通过百度帐号登录，bot直接让用户去登录，不响应intent，可以使用`LoginIntercept`：
```javascript
public function __construct($domain, $postData = []) {
    parent::__construct($domain, $postData);
    $this->addIntercept(new Baidu\Duer\Botsdk\Plugins\LoginIntercept());
    //...
}
```
开发自己的拦截器，继承`\Baidu\Duer\Botsdk\Intercept`，通过重载`before`，能够在处理通过`addHandler`，`addEventListener`添加的回调之前，定义一些逻辑。通过重载`after`能够对回调函数的返回值，进行统一的处理：
```php
class YourIntercept extends \Baidu\Duer\Botsdk\Intercept{
    public function before($bot) {
        //$bot: 你的bot实例化对象
    }

    public function after($bot, $result) {
        //maybe format $result
        return $result;
    }
}
```
`intercept`可以定义多个，执行顺序，以调用`addIntercept`的顺序来执行

## 如何调试
### 本地测试
bot-sdk提供了一个简单的工具，方便用户在没有接入中控时调试自己的bot。
首先你需要通过PHP内置的webserver，将你的bot运行起来，这里假设是监听的`8000`端口。然后，构造你的`NLU`、`session`等数据，如打车bot构造的数据结构，具体可以参考`samples/personal_income_tax`中part目录的例子，比如：`./post-part.sh part/create.php`
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

### 连接中控调试（TODO）


## 如何部署，接入度秘中控条件
