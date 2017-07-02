<?php

namespace Baidu\Duer\Botsdk\Card;

class ImageCard extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($data=[]) {
        $this->data['type'] = 'image';
        parent::__construct();
    }

    public function addItem($src, $thumbnail=''){
        if(!$src) {
            return $this; 
        }

        if(!$this->data['list']) {
            $this->data['list'] = [];
        }

        $item = [];
        $item['src'] = $src;
        if($thumbnail) {
            $item['thumbnail'] = $thumbnail;
        }

        $this->data['list'][] = $item;
        return $this;
    }
}
