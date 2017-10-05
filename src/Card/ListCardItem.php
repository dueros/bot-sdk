<?php
namespace Baidu\Duer\Botsdk\Card;

class ListCardItem extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     *@example
     * <pre>
     * $item = new ListCardItem();
     * $item->setTitle('');
     * $item->setContent('');
     * $item->setUrl('');
     * $item->setImage('');
     * </pre>
     *
     * @param null 
     * @return null
     **/
    public function __construct() {
        parent::__construct(['title', 'content', 'url', 'image']);
    }
}
 
