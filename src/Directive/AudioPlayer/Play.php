<?php

namespace Baidu\Duer\Botsdk\Directive\AudioPlayer;

class Play extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    const REPLACE_ALL = 'REPLACE_ALL';
    const REPLACE_ENQUEUED = 'REPLACE_ENQUEUED';
    const ENQUEUE = 'ENQUEUE';

    /**
     * @param string $playBehavior ，默认替换所有
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
                //'streamFormat' => '',  
                'url' => $url,
                'offsetInMilliSeconds' => 0,
                'token' => $this->genToken(),
            ]
        ];
    }

    /**
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
     * @desc 获取directive的token. 默认在构造时自动生成了token
     * @param null
     * @return string
     **/
    public function getToken(){
        return $this->data['audioItem']['stream']['token'];
    }

    /**
     * @desc 设置directive的音频地址url
     * @param string $url
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
}
