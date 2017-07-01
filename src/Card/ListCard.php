<?php

namespace Baidu\Duer\Botsdk\Card;

class ListCard extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($data=[]) {
        $this->data['type'] = 'list';
        parent::__construct();
    }

    /**
     * @listCardItem ListCardItem 列表项
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
