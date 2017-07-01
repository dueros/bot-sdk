<?php

namespace Baidu\Duer\Botsdk\Card;

class Image extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($data=[]) {
        $this->data['type'] = 'image';

        if($data['list'] && is_array($data['list'])) {
            foreach($data['list'] as $item) {
                $this->addItem($item['src'], $item['thumb']); 
            }
        }

        parent::__construct($data);
    }

    public function addItem($src, $thumb=''){
        if(!$src) {
            return $this; 
        }

        if(!$this->data['list']) {
            $this->data['list'] = [];
        }

        $item = [];
        $item['src'] = $src;
        if($thumb) {
            $item['thumb'] = $thumb;
        }

        $this->data['list'][] = $item;
        return $this;
    }
}
