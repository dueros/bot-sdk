<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc ListCard类测试类
 */
class ListCardTest extends PHPUnit_Framework_TestCase{

	/**
	 * @before
	 */
	public function setupSomeFixtures()
	{
		$this->card = new Baidu\Duer\Botsdk\Card\ListCard();
	}	

	/**
     * @desc 测试addItem方法
     */
	function testAddItem(){
		$item = new Baidu\Duer\Botsdk\Card\ListCardItem();
		$item->setTitle('title1');
		$item->setContent('这是ListCardItem1');
		$item->setUrl('http://www.baidu.com');
		$item->setImage('www.png1');

		$item1 = new Baidu\Duer\Botsdk\Card\ListCardItem();
		$item1->setTitle('title2');
		$item1->setContent('这是ListCardItem2');
		$item1->setUrl('http://www.baidu.com');
		$item1->setImage('www.png2');

		$this->card->addItem($item);	
		$this->card->addItem($item1);	
		$card = [
			'type' => 'list',
			'list' =>  [
				[
					'title' => 'title1', 
					'content' => '这是ListCardItem1', 
					'url' => 'http://www.baidu.com', 
					'image' => 'www.png1'
				],
				[
					'title' => 'title2', 
					'content' => '这是ListCardItem2', 
					'url' => 'http://www.baidu.com', 
					'image' => 'www.png2'
				]
			]
		];
		$this->assertEquals($this->card->getData(), $card);
	}

}
