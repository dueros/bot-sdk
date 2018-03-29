# QQ测吉凶技能使用说明
## 技能描述 

|技能名称     |  QQ测吉凶   |
|------------|---------------|
|开发语言     |        PHP5.4 |
|开发平台     |        LINUX  |
|接口地址     |  [QQ测吉凶](http://japi.juhe.cn/qqevaluate/qq)  |
|功能描述     |技能提供根据QQ号测吉凶功能  |
|项目来源     |  百度度秘事业部 |

## 功能描述
该技能包含qqNumber槽位和qqEvaluation意图。

* qqEvaluation意图在用户唤醒技能后会匹配意图。
  用户询问“我要测吉凶”会匹配到该意图，并识别是否已填槽位。

* qqNumber槽位记录用户询问的日期值，当值为空时，会触发ask请求。

```php

if (!this->getSlot('qqNumber')) {
    $this->nul->ask('qqNumber');
    $card = new TextCard('方便告诉我一下你的QQ号吗?');
    }

```    

## 使用例子：

用户：打开测吉凶    
bot：欢迎使用QQ测吉凶     
用户：我要测吉凶    
bot：方便告诉我一下你的QQ号吗?    
用户：888888888     
bot：（say other thing）     
用户：退出       
bot：感谢使用QQ测吉凶      





