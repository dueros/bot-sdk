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
 * @desc 用于生成AudioPlayerInfoContent的类
 **/
namespace Baidu\Duer\Botsdk\Directive\AudioPlayer;
use Baidu\Duer\Botsdk\Directive\Base\BasePlayerInfoContent;
/**
 * @desc AudioPlayerInfoContent类
 */
class AudioPlayerInfoContent extends BasePlayerInfoContent{

    const AUDIO_TYPE_MUSIC = 'MUSIC';

    const FORMAT_LRC = 'LRC';

    /**
     * @desc __construct
     * @param string $type 音频类型
     */
    public function __construct() {
        $this->data['audioItemType'] = self::AUDIO_TYPE_MUSIC;
    }

    /**
     * @desc 设置audioItemType值
     * @param string $type 类型值
     */ 
    public function setAudioItemType($type){
        $this->data['audioItemType'] = $type;
    }

    /**
     * @desc 设置title值
     * @param string $title 音频的标题
     */
    public function setTitle($title){
        if(is_string($title)){
            $this->data['title'] = $title;
        }
    }

    /**
     * @desc 设置音频子标题1
     * @param string $titleSubtext1 音频子标题1
     */
    public function setTitleSubtext1($titleSubtext1){
        if(is_string($titleSubtext1)){
            $this->data['titleSubtext1'] = $titleSubtext1;
        }
    }

    /**
     * @desc 设置音频子标题2
     * @param string $titleSubtext2 音频子标题2
     */
    public function setTitleSubtext2($titleSubtext2){
        if(is_string($titleSubtext2)){
            $this->data['titleSubtext2'] = $titleSubtext2;
        }
    }

    /**
     * @desc 设置歌词url
     * @param string $url 歌词url
     */
    public function setLyric($url){
        if(is_string($url)){
            $this->data['lyric']['url'] = $url;
            $this->data['lyric']['format'] = self::FORMAT_LRC;
        }
    }

    /**
     * @desc 设置音频流的长度，单位为ms
     * @param int $mediaLengthInMs 音频流的长度，单位为ms
     */
    public function setMediaLengthInMs($mediaLengthInMs){
         if(is_numeric($mediaLengthInMs)){
            $mediaLengthInMs = intval($mediaLengthInMs);
            $this->data['mediaLengthInMilliseconds'] = $mediaLengthInMs;
        }
    }

    /**
     * @desc 设置音频封面图片
     * @param string $src 图片地址
     */
    public function setArt($src){
        if(is_string($src)){
            $this->data['art']['src'] = $src;
        }
    }

    /**
     * @desc 设置资源提供方信息
     * @param string $name 资源提供方的名字
     * @param string $logo 资源提供方的logo
     */
    public function setProvider($name, $logo = ''){
        if(is_string($name)){
            $this->data['provider']['name'] = $name;
        }
        if(is_string($logo)){
            $this->data['provider']['logo']['src'] = $logo;
        }
    }

}
 

