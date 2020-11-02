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
 * @desc 用于生成Record.RecordSpeech指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;

/**
 * 自动翻页指令
 */
class ScrollCommand extends BaseCommand {
    
    /**
     * @desc ScrollCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('Scroll');
    }

    /**
     * @desc 设置滚动的距离
     * @param string $distance 滚动的距离
     */
    public function setDistance($distance) {
        if (is_string($distance)) {
            $this->data['distance'] = $distance;
        }
    }
}


