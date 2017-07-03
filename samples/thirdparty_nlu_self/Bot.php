<?php
/**
 * @desc 这是一个自己完成NLU解析，开发的bot例子
 * 将多轮对话的状态存储到session中
 * 第一轮，如果用户query是hello，进入服务；回复hello
 * 第二轮，进入了这个bot的多轮，处理返回，记录当前的状态到session
 * 第三轮，结束对话，提示用户可以say hello 开始对话
 **/

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

        //test fatal log，你可以这样来输出一个fatal日志
        //$this->log->fatal("this is a fatal log");

        //log 一个字段
        $this->log->setField('query', $this->request->getQuery());
        $this->log->setField('session.status', $this->getSession('status'));
        //你可以这样来添加一个插件
        //$this->addIntercept(new Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());

        //回调函数可以使用匿名函数
        $this->addHandler('session.status == 1', function(){
            $this->setSession('status', 2);

            $card = new TextCard('这是第二轮对话的回复');
            return [
                'card' => $card 
            ];
        });

        //回调函数还可以使用类的成员函数，比如：dialogThree
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
