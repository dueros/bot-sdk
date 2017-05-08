# 度秘BOT SDK for PHP
这是一个帮助开发Bot的SDK，我们强烈建议您使用这个SDK开发度秘的Bot。当然，您可以完全自己来处理中控的协议，自己完成session、nlu、result的处理，但是度秘的中控对BOT的协议会经常进行升级，这样会给您带来一些麻烦。这个SDK会与中控的协议一起升级，会最大限度减少对您开发bot的影响。

我们假设您已经完成了query解析方面的工作，已经了解到自己bot所在domain，以及包含的intent、slot。如果还没有，可以先阅读(TODO:nlu开放平台)

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
composer require baidu/duer/botsdk
```

为了开始使用BOT SDK，你需要先新建一个php文件，比如文件名是Bot.php。你先需要require autoload.php文件，这个文件一般在vendor目录，如果没有这个目录，请先执行composer dump-autoload。

```php
require 'vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($postData = []) {
       $domain = 'remind';
       parent::__construct($domain, $postData); 
    }
}
```
需要继承Baidu\Duer\Botsdk\Bot，并在构造函数中指出关注的`domain`，比如：remind。 下一步，我们处理这个domain下的intent。Bot-sdk提供了一个函数来handle这些intent。比如，为新建闹钟，创建一个handler，在构造函数中添加：

```php
$this->addHandler('#remind && slot.remind_time', function(){
    $remindTime = $this->getSlot('remind_time');
    return [
        //'views' => [$this->getTxtView('创建中')],
        'views' => [
            [
                'type' => 'txt',
                'content' => '创建中',
            ]
        ],
        'directives' => [
            'header' => [
                'namespace' => 'Alerts',
                'name' => 'SetAlert',
                'message_id' => "msg id", 
            ],
            'payload' => [
                'token' => 'token',
                'type' => 'ALARM',
                'scheduled_time' => $remindTime,// 闹钟设置的时间
                'content' => date('Y-m-d H:i:s',  $remindTime) . '提醒你', 
            ],
        ],
    ];
});
```
这里`addHandler`可以用来建立(intent, slot, session) => handler的映射，第一个参数是条件，如果满足则执行对应的回调函数(第二个参数)。
其中，$this指向当前的Bot，`getSlot`继承自父类Bot，通过slot名字来获取对应的值。返回值是一个数组，包含两个字段`views`和`directives`。

`views`也是一个数组，说明可以返回多个view，[view的类型](https://github.com/dueros/dumi_doc/blob/master/doc/api/response.md#views)包含3种：txt, list, image。这个例子返回txt类型的view，一句话'创建中'。针对txt类型的view，这里还提供了快捷的方法：

```php
$this->getTxtView('text');
//或者是带链接的
$this->getTxtView('text', 'http://www.baidu.com');
```

`directives`也是一个数组，[directives格式说明](https://github.com/dueros/dumi_doc/blob/master/doc/api/response.md#directives)，这里返回的指令'SetAlerts'，告诉客户端需要设置一个闹钟。

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

## 使多轮对话管理更加简单
往往用户一次表达的需求，信息不一定完整，比如：'给我创建一个闹钟'，由于query中没有提醒的时间，一个好的bot实现会问用户：'我应该什么时候提醒你呢？'，这时用户说明天上午8点，这样bot就能获取设置时间，可以为用户创建一个闹钟。比如，你可以这样来实现：

```php
//提醒意图而且有提醒时间
$this->addHandler('#remind && slot.remind_time', function(){
    $remindTime = $this->getSlot('remind_time');
    return [/*设置闹钟指令*/];
});

//当前面条件不满足（没有提醒时间），会执行这个handler
$this->addHandler('#remind', function(){
    $this->nlu->needAsk('remind_time');
    return [
        'views' => [$this->getTxtView('要几点的闹钟呢?')]
    ];
});

//监听events
$this->addEventListener('Alerts.SetAlertSucceeded', function($event){
    //do sth. eg. set alert status 
    //var_dump($event);
    return [
        'views' => [$this->getTxtView('闹钟创建成功')]
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

## NLU交互协议ask, select, check

## 声明副作用操作

## 插件
你还可以写插件(拦截器)

