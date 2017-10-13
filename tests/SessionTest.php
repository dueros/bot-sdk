<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
 
require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc Session类的测试类
 */
class SessionTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/intent_request.json'), true);
        $this->session = new Baidu\Duer\Botsdk\Session($data['session']);
    }	

	/**
     * @desc 测试setData方法
     */
	function testSetData(){
		$this->session->setData('status', '1');
		$response = [
            'attributes' => [
				'status' => '1'
			]
        ];
		$this->assertEquals($this->session->toResponse(), $response);
	}

	/**
     * @desc 测试getData方法
     */
	function testGetData(){
		$this->session->setData('status', '1');
		$this->assertEquals($this->session->getData('status'), 1);
	}

}
