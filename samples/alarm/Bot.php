<?php

require '../../vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($postData = []) {
        parent::__construct('remind', $postData);
        //$this->addIntercept(new LoginIntercept());
        //$this->addIntercept(new DuerSessionIntercept());
        //
        date_default_timezone_set('Asia/Shanghai');

        $this->addHandler('#remind && slot.remind_time', function(){
            $remindTime = $this->getSlot('remind_time');
            return [
                'views' => [$this->getTxtView('创建中')],
                'directives' => [
                    'header' => [
                        'namespace' => 'Alerts',
                        'name' => 'SetAlert',
                        'message_id' => "msg id", 
                    ],
                    'payload' => [
                        'token' => 'token',
                        'type' => 'ALARM',
                        'scheduled_time' => $remindTime,// 闹钟设置的时间
                        'content' => date('Y-m-d H:i:s',  $remindTime) . '提醒你', 
                    ],
                ],
            ];
        });

        $this->addHandler('#remind', function(){
            $this->nlu->needAsk('remind_time');
            return [
                'views' => [$this->getTxtView('要几点的闹钟呢?')]
            ];
        });

        $this->addEventListener('Alerts.SetAlertSucceeded', function($event){
            //do sth. eg. set alert status 
            //var_dump($event);
            return [
                'views' => [$this->getTxtView('闹钟创建成功')]
            ];
        });
    }
}
