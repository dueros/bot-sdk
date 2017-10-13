<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc StandardCard类的测试类
 */
class StandardCardTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
		$this->card = new Baidu\Duer\Botsdk\Card\StandardCard();
    }	

	/**
     * @desc 测试getData方法
     */
	function testGetData(){
		$this->card->setTitle('title');
        $this->card->setContent('这是StandardCard');
        $this->card->setImage('www.png');	
		$card = [
			'type' => 'standard',
			'title' => 'title',
			'content' => '这是StandardCard',
			'image' => 'www.png'
		];
		$this->assertEquals($this->card->getData(), $card);
	}

}
