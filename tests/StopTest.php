<?php
require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc Stop类的测试类
 */
class StopTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->stop = new Baidu\Duer\Botsdk\Directive\AudioPlayer\Stop();
    }	

	/**
	 * @desc 测试getData方法
	 */
	function testGetData(){
		$data = ['type' => 'AudioPlayer.Stop'];
		$this->assertEquals($this->stop->getData(), $data);
	}

}
