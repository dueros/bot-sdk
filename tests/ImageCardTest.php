<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc ImageCard类的测试类
 */
class ImageCardTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
		$this->card = new Baidu\Duer\Botsdk\Card\ImageCard();
    }
	
	/**
     * @desc 测试addItem方法
     */
	function testAddItem(){
		$this->card->addItem('www.png');	
		$card = [
			'type' => 'image',
			'list' =>  [['src' => 'www.png']]
		];
		$this->assertEquals($this->card->getData(), $card);

		$this->card->addItem('www.png','www.thumbnail');	
		$card = [
			'type' => 'image',
			'list' =>  [['src' => 'www.png'],['src' => 'www.png', 'thumbnail' => 'www.thumbnail']]
		];
		$this->assertEquals($this->card->getData(), $card);
	}

}
