<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../tools/genUsData.php';

use PHPUnit\Framework\TestCase;

class Bot extends Baidu\Duer\Botsdk\Bot{
    public function __construct($postData = []) {

        $data = [
            'nlu' => [
                'domain' => 'recharge',
                'intent' => 'recharge',
                'slots' => [
                    [
                        'name' => 'phone',
                        'value' => '12112121212',
                    ],
                    [
                        'name' => 'fee',
                        'value' => '100',
                    ],
                ]
            ],
            'session' => [],
        ];

        $template = json_decode(file_get_contents(dirname(__FILE__).'/template.json'), true);

        $domain = 'recharge';
        $postData = genUsData($template, $data);
        parent::__construct($domain, $postData);

        $this->addHandler('#recharge && slot.fee && slot.phone == "12132131"', 'trueFunc');
        $this->addHandler('#recharge && slot.fee && slot.phone == "121session.ga.a32131"', 'trueFunc');
        $this->addHandler('#recharge && slot.fee && slot.phone == "$gaa"', 'trueFunc');
        $this->addHandler('#recharge && slot.fee && slot.phone == "$gaa\"gaga\""', 'trueFunc');
        $this->addHandler('#recharge && slot.fee && slot.phone == "\\\$gaa\"gaga\""', 'trueFunc');
        $this->addHandler("#recharge && slot.fee && slot.phone == '\$gaa\'gagagaga\''", 'trueFunc');
    }

    public function trueFunc() {
        return true; 
    }
}

/**
 * @covers Validator::validateInt
 **/
class AddHandlerTest extends TestCase{
    /**
     * @param null
     * @return null
     **/
    public function testNoInt(){
        $bot = new Bot();
        $bot->setSlot('phone', '12132131');
        $this->assertTrue($bot->run(false));

        $bot->setSlot('phone', '121session.ga.a32131');
        $this->assertTrue($bot->run(false));

        $bot->setSlot('phone', '$gaa');
        $this->assertTrue($bot->run(false));

        $bot->setSlot('phone', '$gaa"gaga"');
        $this->assertTrue($bot->run(false));

        $bot->setSlot('phone', '\$gaa"gaga"');
        $this->assertTrue($bot->run(false));

        $bot->setSlot('phone', '$gaa"gaga"');
        $this->assertTrue($bot->run(false));
    }
}
