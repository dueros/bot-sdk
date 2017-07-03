<?php
namespace Baidu\Duer\Botsdk\Card;

class ListCardItem extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @param null 
     * @return null
     **/
    public function __construct() {
        parent::__construct(['title', 'content', 'url', 'image']);
    }
}
 
