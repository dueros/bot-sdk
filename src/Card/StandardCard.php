<?php

namespace Baidu\Duer\Botsdk\Card;

class StandardCard extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct() {
        $this->data['type'] = 'standard';
        parent::__construct(['title', 'content', 'image']);
    }
}
