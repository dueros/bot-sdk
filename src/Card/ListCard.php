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
     * @param listCardItem ListCardItem 列表项
     * @return self
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
