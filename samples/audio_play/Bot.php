<?php
/**
 * @desc 这是一个将所有对话都代理给DuerOS来处理
 **/

require '../../vendor/autoload.php';
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Stop;

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->addHandler('LaunchRequest', function(){
            $this->waitAnswer();
            return [
					'outputSpeech' => '欢迎进入',
				];

        });


        $this->addHandler('#audio_play_intent', function(){
            $directive = new Play(Play::REPLACE_ALL); 
            $directive->setUrl('http://wwww');
            return [
                    'directives' => [$directive],
					'outputSpeech' => '正在为你播放歌曲',
				];
        });

        $this->addHandler('#audio_stop_intent', function(){
            $directive = new Stop(); 
            return [
                    'directives' => [$directive],
					'outputSpeech' => '已经停止播放',
				];
        });

        $this->addEventListener('AudioPlayer.PlaybackStarted', function($event){
            $offset = $event['offsetInMilliSeconds'];
            //todo sth，比如：记录已经开始播放
            //
            return [
                'outputSpeech' => '这是一个测试回复，表面bot已经收到了端上返回的AudioPlayer.PlaybackStarted event',
            ];
        });

        $this->addEventListener('AudioPlayer.PlaybackNearlyFinished', function($event){
            $offset = $event['offsetInMilliSeconds'];
            //todo sth，比如：返回一个播放enqueue
            //
            $directive = new Play(Play::ENQUEUE); 
            $directive->setUrl('http://wwww');
            return [
                    'directives' => [$directive],
				];
        });

    }
}
