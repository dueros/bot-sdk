# 自己NLU
如果你不使用度秘提供的NLU工具，处理用户的query，有自己的自然语言处理能力，可以这样来写代码：
```javascript
require 'vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * $postData可以不传，由于中控对bot是post请求，sdk默认自动获取
     */
    public function __construct($postData = []) {
        // 将第一个参数domain 设置为false
        parent::__construct(false, $postData);


        // other handler
        // 将最后的handler条件设置为恒定true
        $this->addHandler('true', function(){
            // TODO
        });
    }
}
```

## 你仍然可以使用度秘提供的session服务
虽然你的bot不使用度秘提供的NLU（domain，intent，slot），但是还是建议你使用度秘的session服务。

```javascript
// 当session['status'] 为 1 时
$this->addHandler('session.status == 1', function(){
    //修改sesion 状态
    $this->setSession('status', 2);

    return [
        'views' => [$this->getTxtView('这是第二轮对话的回复')]
    ];
});
```
