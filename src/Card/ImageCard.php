<?php

namespace Baidu\Duer\Botsdk\Card;

class ImageCard extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @param null
     * @return null
     **/
    public function __construct() {
        $this->data['type'] = 'image';
        parent::__construct();
    }

    /**
     * @param string $src 图片地址
     * @param string $thumbnail  图片缩率图地址
     * @return self
     **/
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
