<?php

namespace Baidu\Duer\Botsdk;

abstract class Intercept{
    public function before($bot) {
    
    }

    public function after($bot, $result){
        return $result;
    }
}
