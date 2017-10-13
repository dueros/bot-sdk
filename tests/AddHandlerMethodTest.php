<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc 用于测试addHandler的Bot类
 **/	
class AddHandlerMethodTestBot extends Baidu\Duer\Botsdk\Bot{
	/**
     * @param array $postData us对bot的数据。默认可以为空
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

		$this->addHandler('#intentName', 'intentNameFunc');
		
  	}

	/**
     * @return array
     **/	
	public function intentNameFunc(){
		$card = new \Baidu\Duer\Botsdk\Card\TextCard("测试服务");
		return [
            'card' => $card,
            'outputSpeech' => '测试服务，欢迎光临',
        ];
	}
}
/**
 * @desc addHandler测试类
 **/	
class AddHandlerMethodTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
		$data = json_decode(file_get_contents(dirname(__FILE__).'/intent_request.json'), true);
        $this->bot = new AddHandlerMethodTestBot($data);
    }	

	/**
	 * @desc addHandler测试方法
	 **/	
	function testAddHandler(){
		$ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{"updateIntent":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"测试服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"测试服务，欢迎光临"},"reprompt":null}}'; 
		$this->assertEquals($ret, $rt);	
	}

}
