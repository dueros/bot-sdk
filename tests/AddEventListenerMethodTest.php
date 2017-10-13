<?php

ini_set("display_errors", "On");
ini_set('track_errors', true);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc AddEventListenerMethodTestBot继承Baidu\Duer\Botsdk\Bot用于测试addEventListener
 **/
class AddEventListenerMethodTestBot extends Baidu\Duer\Botsdk\Bot{
	/**
     * @param array $postData us对bot的数据。默认可以为空
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

		$this->addEventListener('AudioPlayer.PlaybackNearlyFinished','nearlyFinishedFunc');	
  	}

	/**
     * @return array
     **/	
	public function nearlyFinishedFunc(){
		return [
            'outputSpeech' => '这是一个测试回复，表面bot已经收到了端上返回的AudioPlayer.PlaybackStarted event',
        ];
	}
}

class AddEventListenerMethodTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
		$data = json_decode(file_get_contents(dirname(__FILE__).'/audio_player.json'), true);
        $this->bot = new AddEventListenerMethodTestBot($data);
    }
	
	/**
     * @desc  用于测试addEventListener方法
     **/	
	function testAddEventListener(){
		$ret = $this->bot->run();
		$rt = '{"version":"2.0","context":{"updateIntent":null},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":{"type":"PlainText","text":"这是一个测试回复，表面bot已经收到了端上返回的AudioPlayer.PlaybackStarted event"},"reprompt":null}}';
        $this->assertEquals($ret, $rt);	
	}

}
