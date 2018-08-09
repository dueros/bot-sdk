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
 * @desc tag类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template\Tag;

class BaseTag{
    protected $data=[];

    /**
     * BaseTemplate constructor.
     * @param array $fields 允许通过魔术方法设置的字段
     */
    public function __construct($type, $text = '') {
        $this->setType($type);
        $this->setText($text);
    }

    /**
     * @desc 设置类型
     * @param string $text
     */
    public function setType($type){
        if($type && is_string($type)){
            $this->data['type'] = $type;
        }
    }


    /**
     * @desc 设置文本
     * @param string $text
     */
    public function setText($text){
        if($text && is_string($text)){
            $this->data['text'] = $text;
        }
    }

    /**
     * @desc 设置color
     * @param string $color
     */
    public function setColor($color){
        if($color && is_string($color)){
            $this->data['color'] = $color;
        }
    }

    /**
     * @desc 设置backgroundColor
     * @param string $backgroundColor
     */
    public function setBackgroundColor($backgroundColor){
        if($backgroundColor && is_string($backgroundColor)){
            $this->data['backgroundColor'] = $backgroundColor;
        }
    }

    /**
     * @desc 返回数据
     * @return array
     */
    public function getData(){
        return $this->data; 
    }

}
 
