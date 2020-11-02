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
 * @desc 用于生成Buy指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\Pay;

class Buy extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    /**
     * @param [type] $productId [description]
     * @param [type] $token     [description]
     */
    public function __construct($productId, $token = '') {
        parent::__construct('Connections.SendRequest.Buy');

        if (empty($token)) {
            $token = $this->genToken();
        }

        $this->data['token'] = $token;
        $this->data['payload']['productId'] = $productId;
    }
}
