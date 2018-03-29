# 个人所得税技能使用说明
## 操作步骤
1.打开[技能开放平台](https://dueros.baidu.com/dbp/main/console);

2.创建技能：个人所得税，调用名称：个人所得税；

3.点击词典，新建词典，添加两个词典：城市和个税类型,月薪直接引用系统词典（sys.number）；

4.然后新建意图,将意图标识名取为：personal_income_tax.inquiry ，在常用表达里面输入常用查询语句，比如：查询在上海月薪8000交多少税，尽量多添加不同的常用表达，会使意图的识别更加精准。

## 意图  ##
意图指用户说话的目的，即用户想要表达什么、想做什么。该技能包含一个意图，即查询各种个税种类的意图，比如：查询养老金，查询在北京月薪8000交多少税等等这些，当我们在常用表达中输入我们的意图时，技能平台会自动识别槽位信息，将常用表达的关键信息对应我们创建的词典，以下是匹配意图的函数：

```
$this->addIntentHandler('personal_income_tax.inquiry', 'computeTax');
```
其中第一个参数是意图的名称，第二个参数是处理这个意图的函数。
## 槽位  ##
词典对应槽位，个人所得税技能有两个词典，分别是城市（city）和个税类型（compute_type）,还有一个是月薪(monthsalary)，引用系统槽位（sys.number）;打开意图界面，槽位标识对应的代码Bot.php里面的槽位变量。

当槽位值缺失时，我们可以使用以下函数询问槽位：
```
$this->nlu->ask($params);
```
以下是获取槽位值的函数：
```
$this->getSlot($params);
```

以下是获取获取个人所得税的槽位的方式：
```
if (!$this->getSlot('monthsalary')) {
    $this->nlu->ask('monthsalary');
    $card = new StandardCard();
    $card->setTitle('个税查询');
    $card->setContent('您的税前工资是多少呢?');
    $this->waitAnswer();
    return [
        'card' => $card,
        'outputSpeech' => '您的税前工资是多少呢？',
        'reprompt' => '您的税前工资是多少呢？'
    ];
} else if (!$this->getSlot('city')) {
    //在存在monthlysalary槽位的情况下，首先验证monthlysalary槽位值是否合法，然后询问城市city槽位
    $ret = $this->checkMonthlysalary();
    if ($ret != null) {
        return $ret;
    }
    $this->nlu->ask('city');
    $card = new StandardCard();
    $card->setTitle('个税查询');
    $card->setContent('请告诉我您所在城市是哪里呢');
    $this->waitAnswer();
    return [
        'card' => $card,
        'outputSpeech' => '请告诉我您所在城市是哪里呢？',
        'reprompt' => '请告诉我您所在城市是哪里呢'
    ];
} else if (!$this->getSlot('compute_type')) {//如果只告诉了城市和月薪，调用函数计算所有的个税类型
    return $this->computeAll();
} else if ($this->getSlot('compute_type')) {//查询单个的个税函数
    return $this->computeOne();
}
```
以上代码是获取月薪，城市，个税类型的槽位值的代码示例，当槽位的值不存在的时候，会调用$this->nlu->ask($params) 函数询问槽位，如果用户语音说出槽位值，但是系统还是没有获取到，reprompt语句会继续询问一次。

## 使用示例：

唤醒技能:打开个人所得税
### 示例1：
用户：打开个人所得税

Bot:所得税为您服务,可以查询个税、公积金、养老等个税类型，准备好了，你可以对我说“我月薪10000"

用户:我在北京月薪8000

Bot:养老保险金个人缴纳400.00元，单位缴纳950.00元;医疗保险金个人缴纳100.00元，单位缴纳500.00元;失业保险金个人缴纳10.00元，单位缴纳40.00元;住房公积金个人缴纳600.00元，单位缴纳600.00元;工伤保险金单位缴纳20.00元;生育保险金单位缴纳40.00元;个人所得税缴纳234.00元;税后月薪6656.00元。



### 示例2：
用户:打开个人所得税

Bot:所得税为您服务,可以查询个税、公积金、养老等个税类型，准备好了，你可以对我说“我月薪10000"

用户:查询养老金

Bot:您的税前工资是多少呢?

用户:我月薪8000

Bot:请告诉我您所在城市是哪里呢

用户:我在北京
   
Bot:养老保险个人缴纳：400.00元,单位缴纳：950.00元

## 以下是常用的相关常用表达
*  打开个人所得税
*  我在北京月薪8000
*  上海月薪8000交多少税
*  在上海月薪8000交多少个税
*  查询养老金
*  查询医疗险
*  查询失业险
*  查询工伤险
*  查询生育险
*  查询公积金
*  查询个税
