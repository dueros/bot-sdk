<?php

require '../../vendor/autoload.php';

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($domain, $postData = []) {
        parent::__construct($domain, $postData);
        //$this->addIntercept();

        $this->addHandler('#rent_car.book', function(){
            var_dump($this->getIntent()); 
        });

        $this->addHandler('#rent_car.book && session.status=="or\'d\"e\"r.e_d" && session.key == "gag li" && slot.key>1', function(){
        //$this->addHandler('#rent_car.book && session.status=="or\'d\"e\"r.e_d" && session.key == "gag li" && isset(slot.key) && slot.key>1', function(){
            //use $this 
            var_dump('gaga');
        });
    }
}
