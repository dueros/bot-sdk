<?php

namespace Baidu\Duer\Botsdk\Card;

class Standard extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($data) {
        $this->data['type'] = 'standard';
        $this->data['content'] = $data['content'];
        $this->data['title'] = $data['title'];
        $this->addImage($data['image']);
        parent::__construct($data);
    }

    public function addImage($src) {
        if($src) {
            $this->data['image'] = $src;
        }
        return $this;
    }
}
