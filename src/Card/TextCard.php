<?php

namespace Baidu\Duer\Botsdk\Card;

class TextCard extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @param string $content  文本卡片显示的content
     * @return null
     **/
    public function __construct($content='') {
        $this->data['type'] = 'txt';
        $this->data['content'] = $content;
        parent::__construct(['content']);
    }
}
