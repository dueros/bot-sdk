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
namespace Baidu\Duer\Botsdk\Directive\Record;
/**
 * 录音Record.RecordSpeech指令
 * 语音录音完成后，会收到Record.RecordSpeechFinished事件
 */
class RecordSpeech extends \Baidu\Duer\Botsdk\Directive\BaseDirective{
    public function __construct() {
        parent::__construct('Record.RecordSpeech');
        $this->data['token'] = $this->genToken();
    }

    /**
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token token
     * @return null
     **/
    public function setToken($token){
        if($token && is_string($token)) {
            $this->data['token'] = $token;
        }
    }
}
 

