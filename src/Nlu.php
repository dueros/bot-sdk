<?php
/**
 * NLU解析query，分析的结果
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Nlu{
    /**
     * @desc 一般处于多轮的服务，会对一个slot进行询问。
     * 但是，会出现答非所问的情况，或者解析覆盖不到的地方
     * 如果出现上述情况，解析会填这个字段, value为int，计数出现了多少次没理解用户说的query
     **/
    const SLOT_NOT_UNDERSTAND = "da_system_not_understand";

    /**
     * 数据
     **/
    private $data = [];


    /**
     * @param array $data
     * @return null
     **/
    public function __construct($data) {
        $this->data = $data; 
    }

    /**
     * @desc 设置slot。如果不存在，新增一个slot
     * @param string $field
     * @param string $value
     * @param string $index 第几组slot
     * @return null
     **/
    public function setSlot($field, $value, $index=0){
        if(empty($field)){
            return;
        }
    
        $slots = &$this->data[$index]['slots'];
        if($slots[$field]) {
            $slots[$field]['value'] = $value;
            return;
        }
        
        $slots[$field] = [
            'name' => $field,
            'value' => $value,
        ];
    }

    /**
     * @ 获取一个slot对应的值
     * @param string $field
     * @return string
     **/
    public function getSlot($field, $index=0) {
        if(empty($field)){
            return;
        }

        $slots = $this->data[$index]['slots'];
        return $slots[$field]['value'];
    }


    /**
     * @desc 获取当前的intent 名
     * @param null
     * @return string
     **/
    public function getIntentName(){
        return $this->data[0]['name'];
    }

    /**
     * @desc 是否有询问用户
     * 通过askSlot判断
     *
     * @param null
     * @return boolean
     **/
    public function hasAsked(){
        return !!$this->askSlot; 
    }

    /**
     * @desc 询问某些槽位。如果有询问一些槽位，表明多轮进行中
     * @param string|array $slot
     * @return null
     **/
    public function ask($slot){
        if(!$slot) {
            return;
        }

        $this->askSlot = $slot;
    }

    /**
     * @desc 打包NLU交互协议，返回DuerOS，为第二轮用户回答提供上下文
     *       在Response 中被调用
     * @param null
     * @return array
     **/
    public function toDirective(){
        $intents=[];

        if($this->askSlot) {
            return [
                'type' => 'Dialog.ElicitSlot',
                'slotToElicit' => $this->askSlot,
                'updatedIntent' => [
                    'name' => $this->getIntentName(),
                    'slots' => $this->data[0]['slots'],
                ]
            ];    
        }
	}

    /**
     * @desc bot可以修改intent中slot对应的值，返回给DuerOS更新
     *       在Response 中被调用
     * @param null
     * @return array
     **/
    public function toUpdateIntent(){
        return [
            'intent' => $this->data 
        ]; 
    }
}
 
