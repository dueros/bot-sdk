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
 * @desc 
 **/
namespace Baidu\Duer\Botsdk\Extensions;

class TTSTemplate{

    private $data = [];

     /**
     * @param string $ttsKey 每个话术模板对应的唯一key
     * @param array $templateSlots 每个话术模板的槽位信息
     **/
    public function __construct($ttsKey) {
        $this->data['type'] = 'TTSTemplate';
        if($ttsKey && is_string($ttsKey)){
            $this->data['ttsTemplates']['ttsKey'] = $ttsKey;
        }
    }

    /**
     * @desc 添加TemplateSlot
     * @param string $slotKey 槽位名称
     * @param string $slotValue 槽位值
     **/
    public function addTemplateSlot($slotKey, $slotValue){
        if($slotKey && is_string($slotKey) && is_string($slotValue)){
            $templateSlot['slotKey'] = $slotKey;
            $templateSlot['slotValue'] = $slotValue;
            $this->data['ttsTemplates']['templateSlots'][] = $templateSlot;
        }
    }

    /**
     * @desc 设置话术模板key
     * @param string $ttsKey 每个话术模板对应的唯一key
     **/
    public function setTtsKey($ttsKey){
        if($ttsKey && is_string($ttsKey)){
            $this->data['ttsTemplates']['ttsKey'] = $ttsKey;
        }
    }

    /**
     * @desc 清除话术模板的槽位信息
     **/
    public function clearTemplateSlots(){
        $this->data['ttsTemplates']['templateSlots'] = [];
    }

    /**
     * @desc 获取数据
     * @return array 
     **/
    public function getData(){
        return $this->data;
    }
}
