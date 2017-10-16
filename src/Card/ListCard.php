<?php

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
