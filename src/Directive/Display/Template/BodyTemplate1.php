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
    public function __construct() {
        $this->data['type'] = 'BodyTemplate1';
        parent::__construct(['token', 'title', 'backgroundImage']);
    }

    /**
     * @param string $position 文本垂直方向的位置
     * @param array $text 文字内容对应的结构
     */
    public function setTextContent($position, $text){
        if($position && $text) {
            $this->data['textContent']['position'] = $position;
            $this->data['textContent']['text'] = $text;
        }
    }

}
 
