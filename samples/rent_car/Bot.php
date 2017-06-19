<?php

require '../../vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($domain, $postData = []) {
        parent::__construct($domain, $postData);

        $this->log = new Baidu\Duer\Botsdk\Log([
            //日志存储路径
            'path' => 'log/',
            //日志打印最低输出级别
            'level' => Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        //test fatal log
        $this->log->fatal("this is a fatal log");

        //log 一个字段
        $this->log->setField('query', $this->request->getQuery());
        //$this->addIntercept(new Baidu\Duer\Botsdk\Plugins\LoginIntercept());
        //$this->addIntercept(new BindCardIntercept());
        $this->addIntercept(new Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());

        $this->addHandler('#rent_car.book && !slot.end_point', function(){
            //记录执行时间-start
            $this->log->markStart('lbs_t');
            $this->nlu->needAsk('end_point');
            //记录执行时间-end
            $this->log->markEnd('lbs_t');
            return [
                'views' => [$this->getTxtView('打车去哪呢')]
            ];
        });

        $this->addHandler('#rent_car.book && !slot.start_point', function(){
            if($this->request->getLocation()){
                return;
            }

            $this->nlu->needAsk(['start_point', 'end_point'/*有可能出现用户改目的地的情况*/]);
            return [
                'views' => [$this->getTxtView('从哪出发呢')]
            ];
        });

        //问车型
        $this->addHandler('#rent_car.book && !slot.car_type', 'askCarType');

        //下单前确认
        $this->addHandler('#rent_car.book && !slot.confirm_intent', function(){
            $this->nlu->needCheck([
                'slot' => 'confirm_intent',
                'value' => '1',
            ], [
                'slot' => 'abort',
                'value' => '1',
            ]);

            return [
                'views' => [$this->getTxtView('你确认要叫车吗？')]
            ];
        });

        //下单
        $this->addHandler('#rent_car.book', function(){
            if(!$this->effectConfirmed()) {
                return $this->declareEffect(); 
            }

            //create order
            return [
                'views' => [$this->getTxtView('下单去啦，很快就有车来接你了')]
            ];
        });
    }

    /**
     * @param null
     * @return null
     **/
    public function askCarType(){
        //搜索周边车辆，拿到报价
        //纪录车辆id到session
        $this->setSession('param', ['vip'=>11213, 'taxi'=>'323992']);

        $this->nlu->needAsk('content');
        //引导用户
        /**
         * 为什么要引导用户？
         * QU解析不一定能够搞定全部的query，提供一些特征query，能提高解析的准确率。
         **/
        $this->nlu->needSelect([
            ['query' => '出租车', 'slot' => 'car_type', 'value' => '出租车'],
            ['query' => '出租', 'slot' => 'car_type', 'value' => '出租车'],
            ['query' => '专车', 'slot' => 'car_type', 'value' => '专车'],
        ]);

        return [
            'views' => [$this->getTxtView('查询车辆了，提供列表让用户选择。坐啥车呢')]
        ];
    }
}
