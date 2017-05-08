# 度秘BOT SDK for PHP
这是一个帮助开发Bot的SDK，我们强烈建议您使用这个SDK开发度秘的Bot。当然，您可以完全自己来处理中控的协议，自己完成session、nlu、result的处理，但是度秘的中控对BOT的协议会经常进行升级，这样会给您带来一些麻烦。这个SDK会与中控的协议一起升级，会最大限度减少对您开发bot的影响。

我们假设您已经完成了query解析方面的工作，已经了解到自己bot所在domain，以及包含的intent、slot。如果还没有，可以先阅读(TODO:nlu开放平台)

## 通过bot-sdk可以快速的开发bot
我们的目标是通过使用bot-sdk，可以迅速的开发一个bog，而不必过多去关注中控对Bot的复杂协议。我们提供如下功能：

* 封装了中控的request和response
* 提供了session简化接口
* 提供了nlu简化接口
    * slot 操作
    * nlu理解交互接口（ask、select、check）
* 提供了多轮对话开发接口
* 提供了事件监听接口

## 安装、使用BOT SDK进行开发 
度秘BOT SDK采用PSR-4规范自动加载(http://www.php-fig.org/psr/psr-4/) , PHP版本确保在5.4.42及以上。使用composer(https://getcomposer.org/)执行如下命令进行安装：
```shell
composer require baidu/duer/botsdk
```

为了开始使用BOT SDK，你需要先新建一个php文件，比如文件名是Bot.php。你先需要require autoload.php文件，这个文件一般在vendor目录，如果没有这个目录，请先执行composer dump-autoload。

```shell
require 'vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($postData = []) {
       parent::__construct('remind', $postData); 
    }
}
```
需要继承Baidu\Duer\Botsdk\Bot，并在构造函数中指出关注的domain，




