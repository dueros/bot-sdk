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
 * @desc 
 **/
namespace Baidu\Duer\Botsdk;

class OutputAudio{

    const PLAY_BEFORE_TTS = 'PLAY_BEFORE_TTS';
    const PLAY_AFTER_TTS = 'PLAY_AFTER_TTS';
    const PLAY_WITH_TTS = 'PLAY_WITH_TTS';

    private $data = [];

     /**
     * @param string $playBehaviour 音频的播放方式
     * @param array $urls 音频信息的url地址
     **/
    public function __construct($playBehaviour, $urls) {
        $this->data['playBehaviour'] = self::PLAY_BEFORE_TTS;
        $this->setPlayBehaviour($playBehaviour);
        $this->setUrls($urls);
    }

    /**
     * @desc 设置音频的播放方式
     * @param string $playBehaviour 音频的播放方式
     **/
    public function setPlayBehaviour($playBehaviour){
        $arrPlayBehaviour = [self::PLAY_BEFORE_TTS, self::PLAY_AFTER_TTS, self::PLAY_WITH_TTS];
        if(in_array($playBehaviour, $arrPlayBehaviour)){
            $this->data['playBehaviour'] = $playBehaviour;
        }
    }

    /**
     * @desc 设置音频的播放方式
     * @param array $urls 音频信息的url地址 
     **/
    public function setUrls($urls){
        $this->data['audioItems'] = [];
        $_urls = [];
        if(!is_array($urls)){
            $_urls[] = $urls;
        }else{
            $_urls =$urls;
        }
        foreach($_urls as $url){
            $this->data['audioItems'][] = ['url' => $url];
        }
    }
    
    /**
     * @desc 获取数据
     * @return array 
     **/
    public function getData(){
        return $this->data;
    }
}
