<?php

namespace Baidu\Duer\Botsdk\Card;

class TxtCard extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($str='') {
        $this->data['type'] = 'txt';
        $this->data['content'] = $str;
        parent::__construct(['content']);
    }
}
