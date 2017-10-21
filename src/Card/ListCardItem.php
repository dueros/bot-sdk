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
 * @desc ListCard的列表项类
 **/
namespace Baidu\Duer\Botsdk\Card;

class ListCardItem extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     *@example
     * <pre>
     * $item = new ListCardItem();
     * $item->setTitle('');
     * $item->setContent('');
     * $item->setUrl('');
     * $item->setImage('');
     * </pre>
     *
     * @param null 
     * @return null
     **/
    public function __construct() {
        parent::__construct(['title', 'content', 'url', 'image']);
    }
}
 
