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
 * @desc 媒体控制指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;

/**
 * 媒体控制指令
 */
class ControlMediaCommand extends BaseCommand {
    
    protected static $commands = array (
        'play',
        'pause',
        'next',
        'previous',
        'screenBulletOn',
        'screenBulletOff'
    );
    
    /**
     * @desc ControlMedia 构造方法.
     */
    public function __construct() {
        parent::__construct('ControlMedia');
        $this->data = [
            'componentId' => '',
            'command' => ''
        ];
    }
    
    /**
     * @desc 设置Command
     * @param string $command 名称
     */
    public function setCommand($command) {
        if (in_array($command, self::$commands)) {
            $this->data['command'] = $command;
        }
    }
}


