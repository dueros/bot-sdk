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
 * @desc 用于生成Play指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\AudioPlayer;

class Play extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    const REPLACE_ALL = 'REPLACE_ALL';
    const REPLACE_ENQUEUED = 'REPLACE_ENQUEUED';
    const ENQUEUE = 'ENQUEUE';
    const STREAM_FORMAT_MP3 = 'AUDIO_MP3';
    const STREAM_FORMAT_M3U8 = 'AUDIO_M3U8';
    const STREAM_FORMAT_M4A = 'AUDIO_M4A';

    /**
     * @param string $url 音频播放地址
     * @param string $playBehavior 默认替换所有
     *               REPLACE_ALL: 立即停止当前播放并清除播放队列，立即播放指令中的audio item。
     *               ENQUEUE: 将audio item添加到当前队列的尾部。
     *               REPLACE_ENQUEUED: 替换播放队列中的所有audio item，但不影响当前正在播放的audio item。
     *
     * @return null
     **/
    public function __construct($url, $playBehavior = self::REPLACE_ALL) {
        parent::__construct('AudioPlayer.Play');
        $this->data['playBehavior'] = $playBehavior;

        $this->data['audioItem'] = [
            'stream' => [
                'streamFormat' => self::STREAM_FORMAT_MP3,  
                'url' => $url,
                'offsetInMilliSeconds' => 0,
                'token' => $this->genToken(),
            ]
        ];
    }

    /**
     * 设置token
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token 音频的token
     * @return null
     **/
    public function setToken($token){
        if($token) {
            $this->data['audioItem']['stream']['token'] = $token;
        }
    }

    /**
     * 获取token
     * @desc 获取directive的token. 默认在构造时自动生成了token
     * @param null
     * @return string
     **/
    public function getToken(){
        return $this->data['audioItem']['stream']['token'];
    }

    /**
     * 设置音频地址
     * @desc 设置directive的音频地址url
     * @param string $url 音频地址
     * @return null
     **/
    public function setUrl($url){
        if($url) {
            $this->data['audioItem']['stream']['url'] = $url;
        }
    }

    /**
     * @desc 设置directive的属性。从指定的offset开始进行播放
     * @param integer $milliSeconds  毫秒数。比如5分钟的歌曲，播放的长度是5*60*1000毫秒，选择起始的播放位置
     * @return null
     **/
    public function setOffsetInMilliSeconds($milliSeconds){
        if(is_numeric($milliSeconds)) {
            $milliSeconds = (int)$milliSeconds;
            $this->data['audioItem']['stream']['offsetInMilliSeconds'] = $milliSeconds;
        }
    }

 	/**
     * @desc 设置directive的属性。定时上报事件的间隔时间
     * @param integer $intervalMs  毫秒数。
     * @return null
     **/
    public function setProgressReportIntervalMs($intervalMs){
        if(is_numeric($intervalMs)) {
            $intervalMs = (int)$intervalMs;
            $this->data['audioItem']['stream']['progressReportIntervalMs'] = $intervalMs;
        }
    }

	/**
     * @desc 设置directive的属性。音频流格式，streamFormat 默认STREAM_FORMAT_MP3
     * @param enum $streamFormat  取值: STREAM_FORMAT_MP3、STREAM_FORMAT_M3U8、STREAM_FORMAT_M4A
     * @return null
     **/
    public function setStreamFormat($streamFormat = self::STREAM_FORMAT_MP3){
		$streamFormatArray = [self::STREAM_FORMAT_MP3, self::STREAM_FORMAT_M3U8, self::STREAM_FORMAT_M4A];
        if(in_array($streamFormat, $streamFormatArray)) {
            $this->data['audioItem']['stream']['streamFormat'] = $streamFormat;
        }
    }

}
