<?php
/**
 * DA解析query，分析的结果
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Nlu{
    /**
     * @desc 一般处于多轮的服务，会对一个slot进行询问。
     * 但是，会出现答非所问的情况，或者解析覆盖不到的地方
     * 如果出现上述情况，解析会填这个字段, value为int，计数出现了多少次
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
     * @param string $field
     * @return string
     **/
    public function getScore($field, $index=0) {
        if(empty($field)){
            return;
        }

        $slots = $this->data[$index]['slots'];
        return $slots[$field]['score'];
    }

    /**
     * @desc 获取当前domain的intent
     * @param null
     * @return string
     **/
    public function getIntent(){
        return $this->data[0]['name'];
    }

    /**
     * @desc 是否有询问用户
     * 通过ask判断
     *
     * @param null
     * @return boolean
     **/
    public function hasAsk(){
        return !!$this->ask; 
    }

    /**
     * @desc 询问某些槽位。如果有询问一些槽位，表明多轮进行中
     * @param string|array $slot
     * @return null
     **/
    public function needAsk($slot){
        if(!$slot) {
            return;
        }

        $this->ask = $slot;
    }

    /**
     * @desc 打包NLU交互协议
     * @param null
     * @return array
     **/
    public function toDirective(){
        $intents=[];

        if($this->ask) {
            return [
                'type' => 'Dialog.ElicitSlot',
                'slotToElicit' => $this->ask,
                'updatedIntent' => [
                    'name' => $this->getIntent(),
                    'slots' => $this->data[0]['slots'],
                ]
            ];    
        }
	}

    public function toUpdateIntent(){
        return [
            'intent' => $this->data 
        ]; 
    }
}
 
