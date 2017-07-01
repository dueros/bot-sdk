<?php

namespace Baidu\Duer\Botsdk\Card;

class Txt extends \Baidu\Duer\Botsdk\Card\Base{

    public function __construct($data) {
        if(is_string($data)) {
            $data = ['content'=>$data];
        }

        $this->data['type'] = 'txt';
        $this->data['content'] = $data['content'];
        parent::__construct($data);
    }
}
