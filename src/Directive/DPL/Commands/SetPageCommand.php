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
 * @desc 翻页指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;
/**
 * 翻页指令
 */
class SetPageCommand extends BaseCommand {
    
    protected static $positionArr = array (
        'relative',
        'absolute'
    );
    
    /**
     * @desc SetPageCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('SetPage');
    }

    /**
     * @desc 设置属性值
     * @param string $position 相对或者绝对
     */
    public function setPosition($position) {
        if (in_array($position, self::$positionArr)) {
            $this->data['position'] = $position;
        }
    }

    /**
     * @desc 设置切换步长
     * @param number $value 步长
     */
    public function setValue($value) {
        if (is_numeric($value)) {
            $this->data['value'] = $value;
        }
    }
}


