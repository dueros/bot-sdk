<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc DuerSessionInterceptTestBot继承Baidu\Duer\Botsdk\Bot
 */
class DuerSessionInterceptTestBot extends Baidu\Duer\Botsdk\Bot{
	
	/**
     * @param array $postData us对bot的数据。默认可以为空，sdk自行获取
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);
		$this->setSlot('da_system_not_understand', 1);
		$this->setSlot('bot_not_understand', 1);
		$this->addIntercept(new \Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());	
  	}
	
}

/**
 * @desc DuerSessionIntercept类的测试类
 */
class DuerSessionInterceptTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
		$data = json_decode(file_get_contents(dirname(__FILE__).'/intent_request.json'), true);
        $this->bot = new DuerSessionInterceptTestBot($data);
    }	

	/**
	 * @desc 用于测试addIntercept方法
	 */
	function testAddIntercept(){
		$ret = $this->bot->run();
		$rt = '{"version":"2.0","context":{"updateIntent":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"},"da_system_not_understand":{"name":"da_system_not_understand","value":1},"bot_not_understand":{"name":"bot_not_understand","value":1}}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"非常抱歉，不明白你说的意思，已经取消了本次服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"非常抱歉，不明白你说的意思，已经取消了本次服务"},"reprompt":null}}';
        $this->assertEquals($ret, $rt);	
	}

}
