<?php

require '../../vendor/autoload.php';

use \Baidu\Duer\Botsdk\Card\TextCard;

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->log = new Baidu\Duer\Botsdk\Log([
            //日志存储路径
            'path' => 'log/',
            //日志打印最低输出级别
            'level' => Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        //test fatal log
        //$this->log->fatal("this is a fatal log");

        //log 一个字段
        $this->log->setField('query', $this->request->getQuery());
        $this->log->setField('session.status', $this->getSession('status'));
        //$this->addIntercept(new Baidu\Duer\Botsdk\Plugins\LoginIntercept());
        //$this->addIntercept(new BindCardIntercept());
        //$this->addIntercept(new Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());

        $this->addHandler('session.status == 1', function(){
            $this->setSession('status', 2);

            $card = new TextCard('这是第二轮对话的回复');
            return [
                'card' => $card 
            ];
        });

        $this->addHandler('session.status == 2', 'dialogThree');

        $this->addHandler('true', function(){
            if($this->request->getQuery() !== 'hello') {
                return; 
            }

            $this->setSession('status', 1);
            $card = new TextCard('hello');
            return [
                'card' => $card 
            ];
        });


    }

    /**
     * @param null
     * @return null
     **/
    public function dialogThree(){
        $this->setSession('status', 0); 

        $card = new TextCard('对话要结束啦，继续说"hello"开始');
        return [
            'card' => $card 
        ];
    }
}
