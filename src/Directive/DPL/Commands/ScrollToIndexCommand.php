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
 * @desc 滚动到指定的index指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;

/**
 * 滚动到指定的index指令
 */
class ScrollToIndexCommand extends BaseCommand {
    
    protected static $alignArr = array(
        'first',
        'center',
        'last',
        'visible'
    );
    
    /**
     * @desc ScrollToIndexCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('ScrollToIndex');
    }

    /**
     * @desc 设置index索引值
     * @param number $index index索引值
     */
    public function setIndex($index) {
        if (is_numeric($index)) {
            $this->data['index'] = $index;
        }
    }

    /**
     * @desc 设置滚动后视图的位置
     * @param string $align 视图的位置
     */
    public function setAlign($align) {
        if (in_array($align, self::$alignArr)) {
            $this->data['align'] = $align;
        }
    }
}


