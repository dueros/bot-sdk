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
namespace Baidu\Duer\Botsdk\Directive\VideoPlayer;

class Play extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    const REPLACE_ALL = 'REPLACE_ALL';
    const REPLACE_ENQUEUED = 'REPLACE_ENQUEUED';
    const ENQUEUE = 'ENQUEUE';

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
        parent::__construct('VideoPlayer.Play');
        $this->data['playBehavior'] = $playBehavior;

        $this->data['videoItem'] = [
            'stream' => [
                'url' => $url,
                'offsetInMilliseconds' => 0,
                'token' => $this->genToken(),
            ]
        ];
    }

    /**
     * 设置token
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token 视频的token
     * @return null
     **/
    public function setToken($token){
        if($token) {
            $this->data['videoItem']['stream']['token'] = $token;
        }
    }

    /**
     * 获取token
     * @desc 获取directive的token. 默认在构造时自动生成了token
     * @param null
     * @return string
     **/
    public function getToken(){
        return $this->data['videoItem']['stream']['token'];
    }

    /**
     * 设置视频标题
     * @param string $title 视频标题
     * @return null
     **/
    public function setTitle($title){
        if($title) {
            $this->data['videoItem']['title'] = $title;
        }
    }

    /**
     * 设置音频地址
     * @desc 设置directive的视频地址url
     * @param string $url 视频地址
     * @return null
     **/
    public function setUrl($url){
        if($url) {
            $this->data['videoItem']['stream']['url'] = $url;
        }
    }

    /**
     * @desc 设置directive的属性。从指定的offset开始进行播放
     * @param integer $milliseconds毫秒数。比如5分钟的视频，播放的长度是5*60*1000毫秒，选择起始的播放位置
     * @return null
     **/
    public function setOffsetInMilliseconds($milliseconds){
        if(is_numeric($milliseconds)) {
            $milliseconds= (int)$milliseconds;
            $this->data['videoItem']['stream']['offsetInMilliseconds'] = $milliseconds;
        }
    }

    /**
     * @desc ISO8601格式，表示stream过期时间
     * @param string expiryTime 
     * @return null
     **/
    public function setExpiryTime($expiryTime){
        if(is_string($expiryTime)) {
            $this->data['videoItem']['stream']['expiryTime'] = $expiryTime;
        }
    }

    /**
     * @desc 设置directive的属性。如果此字段存在，则设备端在播放该video item时，播放到所指定时间之后应该上报ProgressReportDelayElapsed事件；如果此字段不存在，则设备端端不需要上报ProgressReportDelayEsapsed事件
     * @param integer $reportDelayMs  毫秒数。
     * @return null
     **/
    public function setReportDelayInMs($reportDelayMs){
        if(is_numeric($reportDelayMs)) {
            $this->data['videoItem']['stream']['progressReport']['progressReportDelayInMilliseconds'] = intval($reportDelayMs);
        }
    }

 	/**
     * @desc 设置directive的属性。定时上报事件的间隔时间,如果此字段存在，则设备端在播放该video item时，每隔指定时间上报ProgressReportIntervalElapsed事件；如果此字段不存在，则设备端不需要上报ProgressReportIntervalElapsed事件
     * @param integer $intervalMs  毫秒数。
     * @return null
     **/
    public function setReportIntervalInMs($intervalMs){
        if(is_numeric($intervalMs)) {
            $this->data['videoItem']['stream']['progressReport']['progressReportIntervalInMilliseconds'] = intval($intervalMs);
        }
    }

    /**
     * @desc 设置directive的属性。如果此字段存在，则应当匹配前一个video item中的token，如果不匹配则不执行本Play指令
     * @param integer $previousToken毫秒数。
     * @return null
     **/
    public function setExpectedPreviousToken($previousToken){
        if(is_string($previousToken)) {
            $this->data['videoItem']['stream']['expectedPreviousToken'] = $previousToken;
        }
    }

    /**
     * @desc 设置播放暂停点数组
     * @param mixed $stopPoints
     */
    public function setStopPointsInMilliseconds($stopPoints){
        if($stopPoints && is_array($stopPoints)) {
            $this->data['videoItem']['stream']['stopPointsInMilliseconds'] = $stopPoints;
        }else if($stopPoints && is_numeric($stopPoints)){
            $this->data['videoItem']['stream']['stopPointsInMilliseconds'] = [intval($stopPoints)];
        }
    }

    /**
     * @desc 增加播放暂停点数组
     * @param mixed $stopPoints
     */
    public function addStopPointsInMilliseconds($stopPoints){
        if($stopPoints && is_numeric($stopPoints)){
            $stopPoints = [$stopPoints]; 
        }
        if($stopPoints && is_array($stopPoints)) {
            $currentStopPoints = isset($this->data['videoItem']['stream']['stopPointsInMilliseconds']) ? $this->data['videoItem']['stream']['stopPointsInMilliseconds'] : [];
            $currentStopPoints = array_merge($currentStopPoints, $stopPoints);
            $this->setStopPointsInMilliseconds($currentStopPoints);
        }
    }
}
