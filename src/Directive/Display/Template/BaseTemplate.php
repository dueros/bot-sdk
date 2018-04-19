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
 * @desc 模版基类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

abstract class BaseTemplate{
    protected $data=[];
    protected $supportSetField = [];

    //文本类型
    const PLAIN_TEXT = 'PlainText';
    const RICH_TEXT = 'RichText';

    protected static $textTypeArr = array(
        self::PLAIN_TEXT,
        self::RICH_TEXT,
    );

    /**
     * BaseTemplate constructor.
     * @param array $fields 允许通过魔术方法设置的字段
     */
    public function __construct($fields=[]) {
        $this->supportSetField = $fields;
    }

    /**
     * @desc 设置背景图片
     * @param string $url
     * @param string $widthPixels
     * @param string $heightPixels
     * @return $this|array
     */
    public function setBackGroundImage($url = '', $widthPixels = '', $heightPixels = ''){
        $this->data['backgroundImage']['url'] = $url;
        if($widthPixels){
            $this->data['backgroundImage']['widthPixels'] = $widthPixels;
        }
        if($heightPixels){
            $this->data['backgroundImage']['heightPixels'] = $heightPixels;
        }
        return $this;
    }



    /**
     * @desc 返回数据
     * @param string $key
     * @return array|mixed
     */
    public function getData($key=''){
        if($key) {
            return $this->data[$key]; 
        }

        return $this->data; 
    }

    /**
     * @desc 魔术方法
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments){
        $operation = substr($name, 0, 3);
        $field = lcfirst(substr($name, 3));
        if($operation == 'set' && in_array($field, $this->supportSetField)) {
            $this->data[$field] = $arguments[0];
            return $this;
        }

        throw new \Exception("$name function not found");
    }
}
 
