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
 * @desc 用于生成Hint指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display;

class Hint extends \Baidu\Duer\Botsdk\Directive\BaseDirective{
    /**
     * @desc 构造函数
     * @param string|array $text
     */
    public function __construct($text) {
        parent::__construct('Hint');
        if(is_string($text) && $text){
            $text = [$text]; 
        }
        if(is_array($text)){
            foreach ($text as $value) {
                $item['type'] = 'PlainText';
                $item['text'] = $value;
                $this->data['hints'][] = $item; 
            } 
        }
    }
}
 

