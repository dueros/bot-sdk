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
     */
    public function setBackGroundImage($url, $widthPixels = '', $heightPixels = ''){
        $image = $this->createImageStructure($url, $widthPixels, $heightPixels);
        if($image) {
            $this->data['backgroundImage'] = $image;
        }
    }

    /**
     * @desc 构造图片结构体
     * @param string $url
     * @param string $widthPixels
     * @param string $heightPixels
     * @return array
     */
    protected function createImageStructure($url, $widthPixels, $heightPixels){
        if(!$url){
            return;
        }

        $image['url'] = $url;
        if($widthPixels){
            $image['widthPixels'] = $widthPixels;
        }
        if($heightPixels){
            $image['heightPixels'] = $heightPixels;
        }
        return $image;
    }

    /**
     * @desc 构造文本结构体
     * @param string $content 文本内容
     * @param string $type 文本类型
     * @return array
     */
    protected function createTextStructure($content, $type = self::PLAIN_TEXT){
        if(!$content){
            return;
        }

        if(in_array($type, self::$textTypeArr)){
            $text['type'] = $type;
        } else {
            $text['type'] = self::PLAIN_TEXT;
        }
        $text['text'] = $content;
        return $text;
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
 
