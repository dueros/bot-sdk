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
 * @author tianlong02
 * */
require '../../../../../vendor/autoload.php';
use \Baidu\Duer\Botsdk\Card\TextCard;
use \Baidu\Duer\Botsdk\Card\StandardCard;
use \Baidu\Duer\Botsdk\Card\ListCard;
use \Baidu\Duer\Botsdk\Card\ListCardItem;

class Bot extends \Baidu\Duer\Botsdk\Bot {
    // 计算个税的URL
    private static $url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?ie=utf-8&resource_id=28259&req_from=app&query=个税计算器";
    // 支持的个税查询种类
    private static $inquiry_type = array(
        '全部缴纳项目' => 'all',
        '养老' => 'yanglaoxian',
        '医疗' => 'yiliaoxian',
        '失业' => 'shiyexian',
        '工伤' => 'gongshangxian',
        '生育' => 'shengyuxian',
        '公积金' => 'gongjijin',
        '个税' => 'geshui',
    );

    // 目前阿拉丁支持96个城市
    private static $city = array(
        '北京','长春','成都','儋州','广安','贵阳','合肥','滨州','昌江黎族自治县','池州',
        '大同','广元','邯郸','衡阳','亳州','长沙','滁州','德州','广州','杭州',
        '黄山','嘉兴','荆门','晋中','昆明','临沧','洛阳','眉山','攀枝花','萍乡',
        '吉林','金华','九江','莱芜','临沂','马鞍山','牡丹江','平顶山','济南','济宁',
        '酒泉','兰州','六安','茂名','南充','平凉','青岛','琼中黎族苗族自治县','三亚','汕头',
        '石家庄','天津','威海','芜湖','清远','曲靖','上海','韶关','十堰','铜陵',
        '文昌','琼海','衢州','上饶','深圳','泰安','潍坊','温州','厦门','咸宁',
        '宣城','宜昌','乐山','云浮','漳州','重庆','珠海','西安','邢台','许昌',
        '鹰潭','岳阳','枣庄','肇庆','周口','驻马店','湘西土家苗族自治州','宿州','烟台','永州',
        '运城','张掖','郑州','舟山','淄博','葫芦岛',
    );

    /**
     * @param null
     * @return null
     * */
    public function __construct($postData = []) {
        //parent::__construct($postData, file_get_contents(dirname(__file__).'/../../src/privkey.pem'));
        //parent::__construct(file_get_contents(dirname(__file__).'/../../src/privkey.pem'));
        parent::__construct();
        $this->log = new \Baidu\Duer\Botsdk\Log([
            // 日志存储路径
            'path' => 'log/',
            // 日志打印最低输出级别
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        // 记录这次请求的query
        $this->log->setField('query', $this->request->getQuery());
        //$this->addIntercept(new \Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());
        $this->addLaunchHandler(function(){
            $card = new ListCard();
            $item = new ListCardItem();
            $item->setTitle('title')
                ->setContent('content')
                ->setUrl('http://www')
                ->setImage('http://www.png');

            $card->addItem($item);
            $this->waitAnswer();
            return [
                'card' => $card,
                //'outputSpeech' => '<speak>欢迎光临</speak>' 
                'outputSpeech' => '所得税为您服务',
            ];

        });

        $this->addSessionEndedHandler(function(){
            return null; 
        });

        // 在匹配到intent的情况下，首先询问月薪
        $this->addIntentHandler('personal_income_tax.inquiry', function() {
            if(!$this->getSlot('monthlysalary')) {
                $this->nlu->ask('monthlysalary');
                $card = new TextCard('您的税前工资是多少呢？');
                $card->addCueWords(['20000','10000']);
                return [
                    'card' => $card,
                    'reprompt' => '您的税前工资是多少呢？',
                    'resource' => [
                        'type' => 1,
                    ],
                ];
            }else if(!$this->getSlot('location')) {
                // 在存在monthlysalary槽位的情况下，首先验证monthlysalary槽位值是否合法，然后询问location槽位
                $ret = $this->checkMonthlysalary();
                if ($ret != null) {
                    return $ret;
                }
                $this->nlu->ask('location');
                $card = new StandardCard();
                $card->setTitle('title');
                $card->setContent('content');
                $card->setImage('http://www...');
                $card->setAnchor('http://www.baidu.com');
                return [
                    //'card' => new TextCard('您所在城市是哪里呢？'),
                    'card' => $card,
                    'outputSpeech' => '您所在城市是哪里呢？',
                ];
            }else if(!$this->getSlot('compute_type')) {
                // 在存在location槽位的情况下，首先验证location槽位是否在支持的城市列表中，然后询问compute_type槽位
                $ret = $this->checkLocation();
                if ($ret != null) {
                    return $ret;
                }
                $this->nlu->ask('compute_type');
                return [
                    'card' => new TextCard('请选择您要查询的个税种类')
                ];
            }else {
                return $this->compute(); 
            }
        });
    }

    /**
     * @desc 工资合法性检查,非int类型以及小于等于0的值均不合法
     * @param null
     * @return null
     * */
    public function checkMonthlysalary() {
        $monthlysalary = $this->getSlot('monthlysalary');
        $value = intval($monthlysalary);
        if ($value <= 0) {
            $this->nlu->ask('monthlysalary');
            return [
                'card' => new TextCard('输入的工资不正确，请重新输入：')
            ];
        }
    }

    /**
     * @desc 城市合法性检查
     * @param null
     * @return null
     * */
    public function checkLocation() {
        // 判断是否在支持的城市列表中
        $location = $this->getSlot('location');
        if (!in_array($location, self::$city)) {
            $this->nlu->ask('location');
            return [
                'card' => new TextCard("该城市不存在，请重新选择城市：")
            ];
        }
    }

    /**
     * @desc 计算个税结果,在满足三槽位的情况下依次验证三槽位是否合法
     * @parma null
     * @return null
     * */
    public function compute() {
        // 验证月薪是否符合格式
        $ret = $this->checkMonthlysalary();
        if ($ret != null) {
            return $ret;
        }
        // location槽位存在的情况下，判断该城市是否存在
        $ret = $this->checkLocation();
        if ($ret != null) {
            return $ret;
        }
        // compute_type槽位存在的情况下，判断计算类型是否存在
        $compute_type = $this->getSlot('compute_type');
        if (!isset(self::$inquiry_type[$compute_type])) {
            $this->nlu->ask('compute_type');
            return [
                'card' => new TextCard("请重新选择查询的个税种类：")
            ];
        }
        $monthlysalary = intval($this->getSlot('monthlysalary'));
        $location = $this->getSlot('location');
        // 构造请求的url
        $url = self::$url 
            . '&monthlysalary=' . $monthlysalary 
            . '&location=' . $location 
            . '&compute_type=' . self::$inquiry_type[$compute_type];
        $this->log->markStart('url_t');
        $res = file_get_contents($url);
        //$res = Utils::curlGet($url, 2000);
        $this->log->markEnd('url_t');
        $data = json_decode($res, true);
        $pay_details = $data[data][0][resultData][tplData][pay_details];
        $views = '';
        if ($compute_type !== "个税" && $compute_type !== "全部缴纳项目" ) {
            foreach($pay_details as $pay_detail) {
                $views = $pay_detail[col1] 
                    . "：个人缴纳" . $pay_detail[col2_input] . "%=" . $pay_detail[col2_value] 
                    . "，单位缴纳" . $pay_detail[col3_input] . "%=" . $pay_detail[col3_value];
            }
        } else if ($compute_type == "个税") {
            $num = count($pay_details);
            $obj = $pay_details[$num - 1];
            $views .= $obj[col1] . ": " . $obj[col2_value];
        } else if ($compute_type == "全部缴纳项目") {
            $num = count($pay_details);
            for ($i = 0; $i < $num - 1; $i++) {
                $obj = $pay_details[$i];
                $views .= $obj[col1]
                    . "：个人缴纳" . $obj[col2_input] . "%=" . $obj[col2_value] 
                    . "，单位缴纳" . $obj[col3_input] . "%=" . $obj[col3_value]
                    . "\n";
            }
            $obj = $pay_details[$num - 1];
            $views .= $obj[col1] . ": " . $obj[col2_value];
        }
        return [
            'card' => new TextCard($views)
        ];
    }
}
