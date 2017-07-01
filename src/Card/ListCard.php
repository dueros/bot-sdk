<?php

namespace Baidu\Duer\Botsdk\Card;

class ListCard extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($data=[]) {
        $this->data['type'] = 'list';
        if($data['list'] && is_array($data['list'])) {
            foreach($data['list'] as $item) {
                $this->addItem($item); 
            }
        }

        parent::__construct($data);
    }

    public function addItem($arr){
        if(!$arr) {
            return $this; 
        }

        if(!$this->data['list']) {
            $this->data['list'] = [];
        }

        $item = [];
        $item['title'] = $arr['title'];
        $item['content'] = $arr['content'];

        if($arr['image']) {
            $item['image'] = $arr['image'];
        }

        if($arr['url']) {
            $item['url'] = $arr['url'];
        }

        $this->data['list'][] = $item;
        return $this;
    }
}
