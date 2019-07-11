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
 * @desc 基础指令类, 被其他的Command继承
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;

/**
 * 基础指令类
 */
class BaseCommand {
    
    /**
     * @desc 基础指令类
     * @param string $type 指令类型
     */
    public function __construct($type) {
        $this->data['type'] = $type;
        $this->data['componentId'] = '';
    }
    
    /**
     * @desc 设置指令绑定的组件id
     * @param string $componentId 组件id
     */
    public function setComponentId($componentId) {
        if ($componentId) {
            $this->data['componentId'] = $componentId;
        }
    }

    /**
     * @desc 获取指令的data
     * @return array
     */
    public function getData() {
        return $this->data;
    }
}


