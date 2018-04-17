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
     * @param array $primaryText 一级文本结构体
     * @param array $secondaryText 二级文本结构体
     * @param array $tertiaryText 三级文本结构体
     * @return $this
     */
    public function setTextContent($primaryText, $secondaryText = [], $tertiaryText = []){
        if (! $primaryText) {
            return $this;
        }
        $this->data['textContent']['primaryText'] = $primaryText;

        if($secondaryText) {
            $this->data['textContent']['secondaryText'] = $secondaryText;
        }

        if($tertiaryText) {
            $this->data['textContent']['tertiaryText'] = $tertiaryText;
        }

    }


}
 
