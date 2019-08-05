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
 * @desc DPL渲染指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL;
use \Baidu\Duer\Botsdk\Directive\BaseDirective;

/**
 * DPL渲染指令
 */
class RenderDocument extends BaseDirective {
    /**
     * @desc RenderDocument 构造方法.
     */
    public function __construct() {
        parent::__construct('DPL.RenderDocument');
        $this->data['token'] = $this->genToken();
    }

    /**
     * @desc 设置token
     * @param string $token token
     */
    public function setToken($token) {
        if (is_string($token)) {
            $this->data['token'] = $token;
        }
    }

    /**
     * @desc 设置文档对象
     * @param Document $document 文档对象
     */
    public function setDocument($document) {
        if ($document instanceof Document) {
            $this->data['document'] = $document->getData();
        }
    }

    /**
     * @desc 设置数据源
     * @param array dataSources 数据源
     */
    public function setDataSources($dataSources) {
        if (is_array($dataSources)) {
            $this->data['dataSources'] = $dataSources;
        }
    }
}


