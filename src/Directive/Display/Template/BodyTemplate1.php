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
     * BodyTemplate1 constructor.
     */
    public function __construct() {
        $this->data['type'] = 'BodyTemplate1';
        parent::__construct(['token', 'title']);
    }


    /**
     * @desc 设置文本结构
     * @param string $type 文本类型
     * @param string $text 文本内容
     * @param string $position 文本位置
     */
    public function setTextContent($type = self::PLAIN_TEXT, $text = '', $position = self::TOP_LEFT){
        if(in_array($type, self::$textTypeArr)){
            $this->data['textContent']['text']['type'] = $type;
        } else {
            $this->data['textContent']['text']['type'] = self::PLAIN_TEXT;
        }

        $this->data['textContent']['text']['text'] = $text;

        if(in_array($position, self::$positionArr)){
            $this->data['textContent']['text']['position'] = $position ;
        } else {
            $this->data['textContent']['text']['position'] = self::TOP_LEFT;
        }
    }

}
 
