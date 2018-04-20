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
 * @desc 上图下文模版类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

class BodyTemplate2 extends \Baidu\Duer\Botsdk\Directive\Display\Template\TextImageTemplate {
    /**
     * @example
     * <pre>
     * $bodyTemplate = new BodyTemplate2();
     * $bodyTemplate->setToken('token');
     * $bodyTemplate->setImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg');
     * $bodyTemplate->setBackGroundImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg');
     * $bodyTemplate->setTitle('托尔斯泰的格言');
     * $bodyTemplate->setPlainContent('拖尔斯泰-理想的书籍是智慧的钥匙'); //设置plain类型的文本
     * </pre>
     * BodyTemplate2 constructor.
     */
    public function __construct() {
        parent::__construct('BodyTemplate2');
    }

}
 
