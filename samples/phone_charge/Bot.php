<?php

require '../../vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($postData = []) {
        $domain = 'recharge';
        parent::__construct($domain, $postData);

        //调试可以不开启验证
        //$this->addIntercept(new Baidu\Duer\Botsdk\Plugins\LoginIntercept());
        $this->addIntercept(new Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());

        //下单
        $this->addHandler('#recharge && slot.fee && slot.phone', 'createOrder');

        //问电话号码
        $this->addHandler('#recharge && slot.fee', 'askPhone'); 
        //问金额
        $this->addHandler('#recharge', 'askFee');

    }

    public function createOrder(){
        //下面的操作有毒，需要确认下一定会出我的结果吗
        if(!$this->effectConfirmed()) {
            return $this->declareEffect(); 
        }

        $phone = $this->getSlot('phone');
        $fee = $this->getSlot('fee');
        //create order
        return [
            'views' => [$this->getTxtView('下单去啦，电话：'.$phone.', 金额：'.$fee)]
        ];
    }
    public function askPhone(){
        $this->nlu->needAsk('phone');
        return [
            'views' => [$this->getTxtView('手机号是多少呢？')]
        ];   
    }

    public function askFee(){
        //可能一句话就包含了phone和fee
        //这个时候说电话号码也是可以的
        $this->nlu->needAsk('phone');

        $this->nlu->needSelect([
            ['query' => '一百元', 'slot' => 'fee', 'value' => '100'],
            ['query' => '五十', 'slot' => 'fee', 'value' => '50'],
            ['query' => '三十', 'slot' => 'fee', 'value' => '30'],
        ]);

        return [
            'views' => [$this->getTxtView('充多少钱呢？')]
        ];
    }
}
