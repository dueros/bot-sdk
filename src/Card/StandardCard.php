<?php

namespace Baidu\Duer\Botsdk\Card;

class StandardCard extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @param null
     * @return null
     **/
    public function __construct() {
        $this->data['type'] = 'standard';
        parent::__construct(['title', 'content', 'image']);
    }
}
