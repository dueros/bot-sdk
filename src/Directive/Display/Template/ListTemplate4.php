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
 * @desc 纵向列表模板基类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

class ListTemplate4 extends \Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplate {
    /**
     * @example
     * <pre>
     * $listTemplate = new ListTemplate4();
     * $listTemplate->setToken('token');
     * $listTemplate->setBackGroundImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg');
     * $listTemplate->setTitle(''托尔斯泰的格言'');
     *
     * //设置列表数组listItems其中一项
     * $listTemplateItem = new ListTemplateItem();
     * $listTemplateItem->setToken('token');
     * $listTemplateItem->setContent('一级标题');

     * //把listTemplateItem添加到模版listItems
     * $listTemplate->addItem($listTemplateItem);
     * </pre>
     *
     * ListTemplate4 constructor.
     */
    public function __construct() {
        parent::__construct('ListTemplate4');
    }

}
 
