<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc TextCard类的测试类
 */
class TextCardTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
		$this->card = new Baidu\Duer\Botsdk\Card\TextCard('这是TextCard');
    }	

	/**
     * @desc 测试addCueWords方法
     */
	function testAddCueWords(){
		$this->card->addCueWords(['cuewords1', 'cuewords2']);	
		$card = [
			'type' => 'txt',
			'content' =>  '这是TextCard',
			'cueWords' => ['cuewords1', 'cuewords2'] 
		];
		$this->assertEquals($this->card->getData(), $card);
	}

	/**
	 * @desc 测试setAnchor方法
	 */
	function testSetAnchor(){
		$this->card->setAnchor('http://www.baidu.com', '百度');	
		$card = [
			'type' => 'txt',
			'content' =>  '这是TextCard',
			'url' => 'http://www.baidu.com',
  	        'anchorText' => '百度'
		];
		$this->assertEquals($this->card->getData(), $card);

	}

	/**
     * @desc 测试getData方法
     */
	function testGetData(){
		$card = [
			'type' => 'txt',
			'content' =>  '这是TextCard',
		];
		$this->assertEquals($this->card->getData(), $card);
	}

}
