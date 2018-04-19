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
 * RepeatButton类
 */
class RepeatButton extends RadioButton{

    const NAME = 'REPEAT';

    const REPEAT_ONE = 'REPEAT_ONE';
    const REPEAT_ALL = 'REPEAT_ALL';
    const REPEAT_SHUFFLE = 'SHUFFLE';

    protected static $repeatArr = array(
        self::REPEAT_ONE,
        self::REPEAT_ALL,
        self::REPEAT_SHUFFLE,
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
        if(in_array($selectedValue, self::$repeatArr)){
            $this->data['selectedValue'] = $selectedValue;
        } else{
            $this->data['selectedValue'] = self::REPEAT_ONE;
        }
    }
}


