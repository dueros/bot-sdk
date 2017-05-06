<?php

require '../../vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($domain, $postData = []) {
        parent::__construct($domain, $postData);
        //$this->addIntercept(new LoginIntercept());
        //$this->addIntercept(new BindCardIntercept());
        //$this->addIntercept(new DuerSessionIntercept());

        $this->addHandler('#rent_car.book && !slot.end_point', function(){
            $this->nlu->needAsk('end_point');
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

        $this->addHandler('#rent_car.book && !slot.car_type', 'askCarType');

        $this->addHandler('#rent_car.book && session.status=="or\'d\"e\"r.e_d" && session.key == "gag li" && slot.key>1', function(){
        //$this->addHandler('#rent_car.book && session.status=="or\'d\"e\"r.e_d" && session.key == "gag li" && isset(slot.key) && slot.key>1', function(){
            //use $this 
            var_dump('gaga');
        });
    }

    public function askCarType(){
        //搜索周边车辆，拿到报价
        //纪录车辆id到session
        $this->setSession('param', ['vip'=>11213, 'taxi'=>'323992']);

        $this->nlu->needAsk('car_type');
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
