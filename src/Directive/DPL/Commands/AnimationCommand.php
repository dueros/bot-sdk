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
 * @desc 动画指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;
/**
 * 动画指令
 */
class AnimationCommand extends BaseCommand {
    protected static $easingArr = array(
        'linear',
        'ease',
        'ease-in',
        'ease-out'
    );
    
    protected static $repeatModeArr = array(
        'restart',
        'reverse'
    );
    
    /**
     * @desc AnimationCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('Animation');
        $this->data = [
            'attribute' => '',
            'from' => '',
            'to' => '',
            'easing' => 'linear',
            'duration' => 1000,
            'repeatCount' => 'infinite',
            'repeatMode' => 'restart',
            'onComplete' => []
        ];
    }
    
    /**
     * @desc 设置动画属性
     * @param string $attribute 文本内容
     */
    public function setAttribute($attribute) {
        if (is_string($attribute)) {
            $this->data['attribute'] = $attribute;
        }
    }

    /**
     * @desc 设置动画作用属性的起始值
     * @param string $from 动画作用属性的起始值
     */
    public function setFrom($from) {
        if ($from) {
            $this->data['from'] = $from;
        }
    }

    /**
     * @desc 设置动画作用属性的结束值
     * @param string $to 动画作用属性的结束值
     */
    public function setTo($to) {
        if ($to) {
            $this->data['to'] = $to;
        }
    }

    /**
     * @desc 设置描述动画执行的速度的类型
     * @param string $easing 描述动画执行的速度的类型
     */
    public function setEasing($easing) {
        if (strpos($easing,'cubic-bezier') !== false) {
            $this->data['easing'] = $easing;
        }
        if (in_array($easing, self::$easingArr)) {
            $this->data['easing'] = $easing;
        }
    }

    /**
     * @desc 设置动画执行的时间
     * @param number $duration 动画执行的时间
     */
    public function setDuration($duration) {
        if (is_numeric($duration)) {
            $this->data['duration'] = $duration;
        }
    }

    /**
     * @desc 设置动画重复的次数
     * @param string $repeatCount 动画重复的次数
     */
    public function setRepeatCount($repeatCount) {
        if ($repeatCount) {
            $this->data['repeatCount'] = $repeatCount;
        }
    }

    /**
     * @desc 设置动画重复方式
     * @param string $repeatMode 动画重复方式
     */
    public function setRepeatMode($repeatMode) {
        if (in_array($repeatMode, self::$repeatModeArr)) {
            $this->data['repeatMode'] = $repeatMode;
        }
    }

    /**
     * @desc 设置动画结束后需要触发的commands, 如果repeatCount为infinite, 将不会触发onComplete
     * @param BaseCommand|array $commands 动画结束后需要触发的commands
     */
    public function addCompleteCommands($commands) {
        if ($commands instanceof BaseCommand) {
            $this->data['onComplete'] = [$commands->getData()];
        }

        if (is_array($commands)) {
            foreach ($commands as $command) {
                if ($command instanceof BaseCommand) {
                    $this->data['onComplete'][] = $command->getData();
                }
            }
        }
    }
}


