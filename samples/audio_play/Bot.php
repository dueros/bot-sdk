<?php
/**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @desc 这是一个将所有对话都代理给DuerOS来处理
 **/

require '../../../../../vendor/autoload.php';
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
            $directive = new Play('http://wwww'); 
            //$directive->setUrl('http://wwww');
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
            $directive = new Play('http://www', Play::ENQUEUE); 
            //$directive->setUrl('http://wwww');
            return [
                    'directives' => [$directive],
				];
        });

    }
}
