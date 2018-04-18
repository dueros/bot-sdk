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
 **/
namespace Baidu\Duer\Botsdk\Directive\AudioPlayer\Control;
/**
 * ThumbsUpDownButton类
 */
class ThumbsUpDownButton extends RadioButton{

    const NAME = 'THUMBS_UP_DOWN';

    const THUMBS_UP = 'THUMBS_UP';
    const THUMBS_DOWN = 'THUMBS_DOWN';

    protected static $thumbsArr = array(
        self::THUMBS_UP,
        self::THUMBS_DOWN,
    );

    /**
     * @desc __construct
     * @param string $selectedValue 选中的选项值
     */
    public function __construct($selectedValue = '') {
        parent::__construct(self::NAME, $selectedValue);
    }

    /**
     * @desc 设置选中的选项值
     * @param string $selectedValue 选中的选项值
     */
    public function setSelectedValue($selectedValue){
        if(in_array($selectedValue, self::$thumbsArr)){
            $this->data['selectedValue'] = $selectedValue;
        } else {
            $this->data['selectedValue'] = self::THUMBS_UP; 
        }
    }
}


