<?php
/**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @desc tax个税服务
 * @author qinwei01@baidu.com
 * */
require '../../../../../vendor/autoload.php';
require 'Pinyin.php';
use \Baidu\Duer\Botsdk\Card\StandardCard;
use \Baidu\Duer\Botsdk\Card\ListCard;
use \Baidu\Duer\Botsdk\Card\ListCardItem;

class Bot extends \Baidu\Duer\Botsdk\Bot
{
    const TAX = '个税';
    // 计算个税的URL
    private static $url = "http://salarycalculator.sinaapp.com/calculate?is_gjj=true&is_exgjj=false&factor_exgjj=0.08";
    // 支持的个税查询种类
    private static $inquiry_type = array(
        '养老' => array(
            'imageUrl' => 'http://www.eduche.com/myimg/article/954/1_ewiyjb1457440029660451.jpg',
            'title' => '养老金查询',
            'content' => '养老金个人缴纳{personal_yanglao}元，单位缴纳{org_yanglao}元',
        ),
        '医疗' => array(
            'imageUrl' => 'http://img.bx58.com/attached/image/20171219151554_0337.jpg',
            'title' => '医疗险查询',
            'content' => '医疗保险金个人缴纳{personal_yiliao}元，单位缴纳{org_yiliao}元',
        ),
        '失业' => array(
            'imageUrl' => 'http://a1.att.hudong.com/31/45/01300000251852122593450806682.jpg',
            'title' => '失业险查询',
            'content' => '失业保险金个人缴纳{personal_shiye}元，单位缴纳{org_shiye}元',
        ),
        '工伤' => array(
            'imageUrl' => 'http://www.gov.cn/fuwu/2017-08/25/5220266/images/7c22a746f343422f8ccd6223f3d2c189.jpg',
            'title' => '工伤险查询',
            'content' => '工伤保险金单位缴纳{org_gongshang}元',
        ),
        '生育' => array(
            'imageUrl' => 'http://uploads.cnrencai.com/allimg/201607/7-160G415134HX.png',
            'title' => '生育险查询',
            'content' => '生育保险金单位缴纳{org_shengyu}元'
        ),
        '公积金' => array(
            'imageUrl' => 'http://img9.jiwu.com/jiwu_news_pics/20161122/1463210079297_000.jpg',
            'title' => '公积金查询',
            'content' => '住房公积金个人缴纳{personal_gjj}元,单位缴纳{org_gjj}元',
        ),
        '个税' => array(
            'imageUrl' => 'http://img.25pp.com/uploadfile/soft/images/2012/0412/20120412011113208.jpg',
            'title' => '个税查询',
            'content' => '个人所得税缴纳{tax}元',
        )
    );

    /**
     * @param $postData
     * @return 
     * */
    public function __construct($postData = []){
        parent::__construct();
        $this->log = new \Baidu\Duer\Botsdk\Log([
            // 日志存储路径
            'path' => 'log/',
            // 日志打印最低输出级别
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        // 记录这次请求的query
        $this->log->setField('query', $this->request->getQuery());
        //打印请求体
        $this->log->setField('getdata', json_encode($this->request->getData()));

        $this->addLaunchHandler(function () {
            $this->waitAnswer();
            return [
                'outputSpeech' => '所得税为你服务,告诉我月薪是多少,就可以查询个税、公积金、养老等个税类型。',
            ];
        });

        //说退出时的响应
        $this->addSessionEndedHandler(function () {
            $card = $this->getStandardCard('个税查询', '欢迎下次使用');
            return [
                'card' => $card,
                'outputSpeech' => '欢迎下次使用',
            ];
        });

        // 在匹配到intent的情况下，首先询问月薪
        $this->addIntentHandler('personal_income_tax.inquiry', 'computeTax');
    }

    /**
     * @desc 询问槽位值，并调用相应的函数计算个税
     * @param null
     * @return array
     * */
    public function computeTax(){
        if (!$this->getSlot('monthsalary')) {
            $this->nlu->ask('monthsalary');
            $card = $this->getStandardCard('个税查询', '你的税前工资是多少呢?');
            $this->waitAnswer();
            return [
                'card' => $card,
                'outputSpeech' => '你的税前工资是多少呢？',
                'reprompt' => '你的税前工资是多少呢？'
            ];
        } else if (!$this->getSlot('sys.city')) {
            // 在存在monthlysalary槽位的情况下，首先验证monthlysalary槽位值是否合法，然后询问城市city槽位
            $ret = $this->checkMonthlysalary();
            if ($ret != null) {
                return $ret;
            }
            $this->nlu->ask('sys.city');
            $card = $this->getStandardCard('个税查询', '你在哪个城市缴税呢?');
            $this->waitAnswer();
            return [
                'card' => $card,
                'outputSpeech' => '你在哪个城市缴税呢?',
                'reprompt' => '你在哪个城市缴税呢?'
            ];
        } else if (!$this->getSlot('compute_type') || $this->getSlot('compute_type') == self::TAX) {
            return $this->computeAll();
        } else if ($this->getSlot('compute_type')) {//查询单个的个税函数
            return $this->computeOne();
        }
    }

    /**
     * @desc 计算单独要查询的个税种类
     * @param null
     * @return array
     * */
    public function computeOne(){
        //获取个税的所有数据
        $data = $this->getTaxData();
        if (!$data){
            $this->nlu->ask('sys.city');
            $card = $this->getStandardCard('当前不支持此城市的查询', '当前不支持此城市的查询，请选择其他城市');
            return [
                'card' => $card,
                'outputSpeech' => '当前不支持此城市的查询，请选择其他城市？',
            ];
        }
        //获取个税类型槽位
        $taxType = $this->getSlot('compute_type');
        $imageUrl = self::$inquiry_type[$taxType]['imageUrl'];
        $content = self::$inquiry_type[$taxType]['content'];
        $result =$this->processTemplate($content, $data);
        $title = $taxType . '查询';
        $card = $this->getStandardCard($title, $result, $imageUrl);
        $this->waitAnswer();
        return [
            'card' => $card,
            'outputSpeech' => $result,
        ];
    }

    /**
     * @desc 计算所有的个税类型
     * @param null
     * @return array
     * */
    public function computeAll(){
        //获取个税数据
        $data = $this->getTaxData();
        if (!$data){
            $this->nlu->ask('sys.city');
            $card = $this->getStandardCard('当前不支持此城市的查询', '当前不支持此城市的查询，请选择其他城市');
            return [
                'card' => $card,
                'outputSpeech' => '当前不支持此城市的查询，请选择其他城市？',
            ];
        }
        $result = '';
        foreach(self::$inquiry_type as $value){
            $content = $this->processTemplate($value['content'], $data);
            $result .=  $content;
        }
        //获取ListCard数据
        $card = $this->getListCard($data);
        return [
            'card' => $card,
            'outputSpeech' => $result,
        ];
    }

    /**
     * @desc 根据参数获取个税的所有数据
     * @return $data 获取的数据
     * */
    public function getTaxData(){
        //获取月薪槽位
        $monthlysalary = $this->getSlot('monthsalary');
        $ret = $this->checkMonthlysalary();
        if ($ret != null) {
            return $ret;
        }
        //获取城市槽位
        $location = $this->getSlot('sys.city');
        $city = json_decode($location, true)['city'];
        $city = Pinyin::getPinyin($city);
        //带参数的URL
        $url = self::$url . '&base_gjj=' . $monthlysalary. '&origin_salary=' . $monthlysalary . '&city=' . $city;
        $this->log->markStart('url_t');
        $data = @file_get_contents($url);
        $this->log->markEnd('url_t');
        $data = json_decode($data, true);
        return $data;
    }

    /**
     * @desc 工资合法性检查,非int类型以及小于等于0的值均不合法
     * @param null
     * @return array
     * */
    public function checkMonthlysalary(){
        $monthlysalary = $this->getSlot('monthsalary');
        $value = intval($monthlysalary);
        if ($value <= 0) {
            $this->nlu->ask('monthsalary');
            $card = $this->getStandardCard('个税查询', '输入的工资不正确，请重新输入');
            return [
                'card' => $card,
            ];
        }
    }

    /**
     * @desc 将所有的个税类型计算出来放入ListCard
     * @param $data
     * @return $card
     * */
    public function getListCard($data){
        $card = new ListCard();
        foreach(self::$inquiry_type as $value){
            $content = $this->processTemplate($value['content'], $data);
            $cardItem = new ListCardItem();
            $cardItem->setTitle($value['title']);
            $cardItem->setContent($content);
            $cardItem->setImage($value['imageUrl']);
            $card->addItem($cardItem);
        }
        return $card;
    }

    /**
     * @desc 返回标准卡片
     * @param $title
     * @param $content
     * @return $card
     */
    public function getStandardCard($title, $content, $imageUrl = ''){
        $card = new StandardCard();
        $card->setTitle($title);
        $card->setContent($content);
        if($imageUrl){
            $card->setImage($imageUrl);
        }
        return $card;
    }

    /**
     * @desc 模版处理函数
     * @param $content要替换的模版字符串，string类型,例如:养老金个人缴纳{personal_yanglao}元，
     * 单位缴纳{org_yanglao}元
     * @param $data要替换模版的变量值，array类型,例如
     * $data = [
     *      'personal_yanglao' => 200,
     *      'org_yanglao' => 100,
     *    ];
     * @return mixed 例如养老金个人缴纳200元，单位缴纳100元
     */
    public function processTemplate($content, $data){
        return preg_replace_callback("/\{(\w+)\}/", function($matches) use($data){
            return $data[$matches[1]];
        }, $content);
    }

}
