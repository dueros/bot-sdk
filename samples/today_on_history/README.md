# 历史上的今天技能使用说明
## 技能描述 

|技能名称     |   历史上的今天  |
|-------------|-----------------|
|开发语言     |         PHP5.4  |
|开发平台     |          LINUX  |
|接口地址     |  [历史上的今天](http://www.todayonhistory.com/index.php?m=content&c=index&a=json_event&page=1&pagesize=40&month=3&day=5)  |
|功能描述     |技能通过用户提供的日期信息来返回历史上当天的事件信息,支持多轮询问  |
|项目来源     |  百度度秘事业部 |

## 功能描述
该技能包含currentDate槽位和historyToday、otherHistoryToday两个意图。

* historyToday意图在用户唤醒技能后会匹配意图。
  用户询问“历史上的今天发生了什么”会匹配到该意图，并识别是否已填槽位。

* otherHistoryToday意图在多轮询问的时候会匹配意图。
  用户在询问一次“历史上今天发生了什么”之后，再次询问“还有呢”便与该意图匹配，槽位继承historyToday意图中currentDate槽位的值。

* currentDate槽位记录用户询问的日期值，当值为空时，会触发ask请求。
```php
if (!this->getSlot('currentDate')) {
    $this->nul->ask('currentDate');
    $card = new TextCard('请问您要知道几月几日的事呢？');
    }
```    
---------

## 使用例子：

用户：打开今日历史  
bot：欢迎使用今日历史  
用户：历史上的今天发生了什么  
bot：（say something）  
用户：还有呢   
bot：（say other thing）   
用户：退出    
bot：感谢使用今日历史    

----------

用户：打开今日历史   
bot：欢迎使用今日历史    
用户：说点事   
bot：您想知道几月几日的事呢    
用户：3月3   
bot：（say something）   
用户：换一个    
bot：（say other thing）    


-------

## 使用资源

simple_html_dom类文件
获取html文件中不同标签的内容
