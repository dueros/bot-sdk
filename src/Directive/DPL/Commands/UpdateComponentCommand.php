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
 * @desc 异步更新指令
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL\Commands;
use  Baidu\Duer\Botsdk\Directive\DPL\Document;

/**
 * 异步更新指令
 */
class UpdateComponentCommand extends BaseCommand {
    
    /**
     * @desc UpdateComponentCommand 构造方法.
     */
    public function __construct() {
        parent::__construct('UpdateComponent');
    }

    /**
     * @desc 设置替换文档
     * @param Document $doc 替换的文档对象
     */
    public function setDocument($doc) {
        if ($doc instanceof Document) {
            $this->data['document'] = $doc->getData();
        }
    }
}


