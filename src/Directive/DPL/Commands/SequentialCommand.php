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
 * @desc 串行执行指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;

/**
 * 串行执行指令
 */
class SequentialCommand extends BaseCommand {
    
    /**
     * @desc SequentialCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('Sequential');
    }

    /**
     * @desc 设置延迟执行时间
     * @param number $delayMs 延迟执行时间
     */
    public function setDelayInMilliseconds($delayMs) {
        if (is_numeric($delayMs)) {
            $this->data['delayInMilliseconds'] = $delayMs;
        }
    }

    /**
     * @desc 设置滚动的距离
     * @param number $repeatCount 重复执行次数
     */
    public function setRepeatCount($repeatCount) {
        if (is_numeric($repeatCount)) {
            $this->data['repeatCount'] = $repeatCount;
        }
    }

    /**
     * @desc 设置Command动作项
     * @param array $commands 指令项
     */
    public function setCommands($commands) {
        if ($commands instanceof BaseCommand) {
            $this->data['commands'] = [$commands->getData()];
        }
        if (is_array($commands)) {
            foreach ($commands as $command) {
                if ($command instanceof BaseCommand) {
                    $this->data['commands'][] = $command->getData();
                }
            }
        }
    }
}


