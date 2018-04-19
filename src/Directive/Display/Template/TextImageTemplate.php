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
 * @desc 卡片的基类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

abstract class TextImageTemplate extends \Baidu\Duer\Botsdk\Directive\Display\Template\BaseTemplate {
    /**
     * TextImageTemplate constructor.
     * @param string $type
     */
    public function __construct($type) {
        $this->data['type'] = $type;
        parent::__construct(['token', 'title']);
    }

    /**
     * @desc 设置图片
     * @param string $url
     * @param string $widthPixels
     * @param string $heightPixels
     */
    public function setImage($url, $widthPixels = '', $heightPixels = ''){
        $imageStructure = $this->createImageStructure($url, $widthPixels, $heightPixels);
        $this->data['image'] = $imageStructure;
    }

    /**
     * @desc 设置文本
     * @param string $type 文本类型
     */
    public function setPlainContent($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        $this->data['content'] =$textStructure ;
    }

}
 
