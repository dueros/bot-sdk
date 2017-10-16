<?php

namespace Baidu\Duer\Botsdk\Card;

class StandardCard extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @example
     * <pre>
     * $card = new StandardCard();
     * $card->setTitle('');
     * $card->setContent('');
     * $card->setImage('');
     * </pre>
     * @param null
     * @return null
     **/
    public function __construct() {
        $this->data['type'] = 'standard';
        parent::__construct(['title', 'content', 'image']);
    }
}
