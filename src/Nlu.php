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
 * @desc NLU解析query，分析的结果
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
     * 记录返回的指令
     **/
    private $directive = [];


    /**
     * @param array $data
     * @return null
     **/
    public function __construct($data) {
        $this->data = $data; 
    }

    /**
     * 设置槽位
     * @desc 设置slot。如果不存在，新增一个slot
     * @param string $field 槽位名
     * @param string $value 槽位值
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
     * 获取槽位
     * @desc 获取一个slot对应的值
     * @param string $field 槽位名
     * @return string 槽位值
     **/
    public function getSlot($field, $index=0) {
        if(empty($field)){
            return;
        }

        $slots = isset($this->data[$index]['slots'])?$this->data[$index]['slots']:[];
        return isset($slots[$field]['value'])?$slots[$field]['value']:'';
    }

	 /**
     * 获取槽位的确认状态
     * @desc 获取一个slot对应的confirmationStatus
     * @param string $field 槽位名
     * @return string 槽位的confirmationStatus
     **/
    public function getSlotConfirmationStatus($field, $index=0) {
        if(empty($field)){
            return;
        }

        $slots = isset($this->data[$index]['slots'])?$this->data[$index]['slots']:[];
        return isset($slots[$field]['confirmationStatus'])?$slots[$field]['confirmationStatus']:'NONE';
    }

	 /**
     * 获取意图的确认状态
     * @desc 获取一个intent对应的confirmationStatus
     * @param $index
     * @return string 意图的confirmationStatus
     **/
    public function getIntentConfirmationStatus($index=0) {
        return isset($this->data[$index]['confirmationStatus'])?$this->data[$index]['confirmationStatus']:'NONE';
    }

    /**
     * @desc 获取当前的intent 名
     * @param null
     * @return string
     **/
    public function getIntentName(){
        return isset($this->data[0]['name'])?$this->data[0]['name']:'';
    }

    /**
     * 是否询问过用户
     * @desc 是否有询问用户
     *
     * @param null
     * @return boolean
     **/
    public function hasAsked(){
        return !!$this->directive; 
    }

    /**
     * 询问一个特定的槽位
     * @desc 询问某些槽位。如果有询问一些槽位，表明多轮进行中
     * @param string|array $slot 槽位名
     * @return null
     **/
    public function ask($slot){
        if(!$slot) {
            return;
        }

        $this->askSlot = $slot;
        $this->directive = [
            'type' => 'Dialog.ElicitSlot',
            'slotToElicit' => $slot,
            'updatedIntent' => $this->getUpdateIntent(),
        ];
    }

    /**
     * @desc 打包NLU交互协议，返回DuerOS，为第二轮用户回答提供上下文
     *       在Response 中被调用
     * @param null
     * @return array
     **/
    public function toDirective(){
        $intents=[];

        return $this->directive;
	}

    /**
     * @desc 私有。构造返回的update intent 数据结构
     * @param null
     * @return array
     **/
    private function getUpdateIntent(){
        return [
                'name' => $this->getIntentName(),
                'slots' => isset($this->data[0]['slots'])?$this->data[0]['slots']:[],
            ];
    }

    /**
     * @desc bot可以修改intent中slot对应的值，返回给DuerOS更新
     *       在Response 中被调用
     * @param null
     * @return array
     **/
    public function toUpdateIntent(){
        return [
            'intent' => isset($this->data[0])?$this->data[0]:[]
        ]; 
    }

    /**
     * @desc 设置delegate某个槽位或确认意图。
     * @param null
     * @return null
     **/
    public function setDelegate(){
        $this->directive = [
            'type' => 'Dialog.Delegate',
            'updatedIntent' => $this->getUpdateIntent(),
        ];
    }

    /**
     * @desc 设置对一个槽位的确认
     * @param string $field 槽位名
     * @return null
     **/
    public function setConfirmSlot($field){
        $slots = isset($this->data[0]['slots'])?$this->data[0]['slots']:[];

        if(array_key_exists($field, $slots)) {
            $this->directive = [
                'type' => 'Dialog.ConfirmSlot',
                'slotToConfirm' => $field,
                'updatedIntent' => $this->getUpdateIntent(),
            ];
        }
    }

    /**
     * @desc 设置confirm 意图。询问用户是否对意图确认，设置后需要自行返回outputSpeech
     * @param null
     * @return
     **/
    public function setConfirmIntent() {
        $this->directive = [
            'type' => 'Dialog.ConfirmIntent',
            'updatedIntent' => $this->getUpdateIntent(),
        ]; 
    }
}
 
