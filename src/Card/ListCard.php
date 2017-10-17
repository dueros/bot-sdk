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
 * @desc 列表卡片类
 **/

namespace Baidu\Duer\Botsdk\Card;

class ListCard extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @apram null
     * @return null
     **/
    public function __construct() {
        $this->data['type'] = 'list';
        parent::__construct();
    }

    /**
     * 添加列表项
     * @example
     * <pre>
     * $item = new ListCardItem();
     * $item->setTitle('');
     * $item->setContent('');
     * $item->setUrl('');
     * $item->setImage('');
     *
     * $listCard = new ListCard();
     * $listCard->addItem($item);
     * </pre>
     * @param listCardItem ListCardItem 列表项
     * @return ListCard
     **/
    public function addItem($listCardItem){
        if($listCardItem instanceof ListCardItem) {

            if(!$this->data['list']) {
                $this->data['list'] = [];
            }

            $this->data['list'][] = $listCardItem->getData();
        }
        return $this;
    }
}
