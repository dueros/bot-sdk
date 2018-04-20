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
 * @desc 文本展现模板类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

class BodyTemplate1 extends \Baidu\Duer\Botsdk\Directive\Display\Template\BaseTemplate {

    //文本位置
    const TOP_LEFT = 'TOP-LEFT';
    const CENTER = 'CENTER';
    const BOTTOM_LEFT = 'BOTTOM-LEFT';

    protected static $positionArr = array(
        self::TOP_LEFT,
        self::BOTTOM_LEFT,
        self::CENTER,
    );

    /**
     * @example
     * <pre>
     * $bodyTemplate = new BodyTemplate1();
     * $bodyTemplate->setToken('token');
     * $bodyTemplate->setBackGroundImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg');
     * $bodyTemplate->setTitle('托尔斯泰的格言');
     * $bodyTemplate->setPlainTextContent('拖尔斯泰-理想的书籍是智慧的钥匙'); //设置plain类型的文本
     * </pre>
     * BodyTemplate1 constructor.
     */
    public function __construct() {
        $this->data['type'] = 'BodyTemplate1';
        parent::__construct(['token', 'title']);
    }

    /**
     * @desc 设置plain类型的文本结构
     * @param string $text 文本内容
     * @param string $position
     * @return $this
     */
    public function setPlainTextContent($text, $position = self::BOTTOM_LEFT){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        if(!$textStructure){
            return $this;
        }
        $this->data['textContent']['text'] = $textStructure;

        if(in_array($position, self::$positionArr)){
            $this->data['textContent']['position'] = $position ;
        } else {
            $this->data['textContent']['position'] = self::TOP_LEFT;
        }
        return $this;
    }

}
 
