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
 * @desc 自动翻页指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;

/**
 * 自动翻页指令
 */
class SetStateCommand extends BaseCommand {
    
    /**
     * @desc SetStateCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('SetState');
    }

    /**
     * @desc 设置属性名称
     * @param  string $state 属性名称
     */
    public function setState($state) {
        if ($state) {
            $this->data['state'] = $state;
        }
    }

    /**
     * @desc 设置属性值
     * @param string $value 属性值
     */
    public function setValue($value) {
        if ($value) {
            $this->data['value'] = $value;
        }
    }
}


