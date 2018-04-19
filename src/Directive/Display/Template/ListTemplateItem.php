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

class ListTemplateItem extends \Baidu\Duer\Botsdk\Directive\Display\Template\BaseTemplate {
    /**
     * ListTemplateItem constructor.
     */
    public function __construct() {
        parent::__construct(['token', 'image']);
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
     * @desc 设置列表元素一级标题
     * @param string $text 文本内容
     */
    public function setPlainPrimaryText($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        $this->data['textContent']['primaryText'] = $textStructure;
    }

    /**
     * @desc 设置列表元素二级标题
     * @param string $text 文本内容
     */
    public function setPlainSecondaryText($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        $this->data['textContent']['secondaryText'] = $textStructure;
    }

    /**
     * @desc 设置列表元素三级标题
     * @param string $text 文本内容
     */
    public function setPlainTertiaryText($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        $this->data['textContent']['tertiaryText'] = $textStructure;
    }

}
 
