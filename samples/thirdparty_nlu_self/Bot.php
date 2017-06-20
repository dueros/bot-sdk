<?php

require '../../vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($postData = []) {
        //domain = false 不使用度秘提供的解析，自己完成query分析
        parent::__construct(false, $postData);

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

            return [
                'views' => [$this->getTxtView('这是第二轮对话的回复')]
            ];
        });

        $this->addHandler('session.status == 2', 'dialogThree');

        $this->addHandler('true', function(){
            if($this->request->getQuery() !== 'hello') {
                return; 
            }

            $this->setSession('status', 1);
            return [
                'views' => [$this->getTxtView('hello')]
            ];
        });


    }

    /**
     * @param null
     * @return null
     **/
    public function dialogThree(){
        $this->setSession('status', 0); 

        return [
            'views' => [$this->getTxtView('对话要结束啦，继续说"hello"开始')]
        ];
    }
}
