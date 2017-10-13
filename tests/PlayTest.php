<?php
require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc Play类的测试类
 */
class PlayTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->play = new Baidu\Duer\Botsdk\Directive\AudioPlayer\Play('www.baidu.com');
    }	

	/**
     * @desc 测试setToken方法
     */
	function testSetToken(){
		$this->play->setToken('token');
		$this->assertEquals($this->play->getToken(), 'token');
	}

	/**
	 * @desc 测试getToken方法
	 */
	function testGetToken(){
		$this->play->setToken('token');
		$this->assertEquals($this->play->getToken(), 'token');
	}

	/**
     * @desc 测试setUrl方法
     */
	function testSetUrl(){
		$this->play->setUrl('www.test.com');
		$url = $this->play->getData()['audioItem']['stream']['url'];
		$this->assertEquals($url, 'www.test.com');
	}

	/**
     * @desc 测试setOffsetInMilliSeconds方法
     */
	function testSetOffsetInMilliSeconds(){
		$this->play->setOffsetInMilliSeconds(1000);
		$offset = $this->play->getData()['audioItem']['stream']['offsetInMilliSeconds'];
		$this->assertEquals($offset, 1000);
	}

}
