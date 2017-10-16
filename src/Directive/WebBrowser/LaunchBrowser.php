<?php

namespace Baidu\Duer\Botsdk\Directive\WebBrowser;

class LaunchBrowser extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    /**
     * @param string $url 连接地址
     *
     * @return null
     **/
    public function __construct($url) {
        parent::__construct('WebBrowser.LaunchBrowser');

        $this->data['url'] = $url;
        $this->data['token'] = $this->genToken();
    }

    /**
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token token
     * @return null
     **/
    public function setToken($token){
        if($token) {
            $this->data['token'] = $token;
        }
    }

    /**
     * @desc 获取directive的token. 默认在构造时自动生成了token
     * @param null
     * @return string
     **/
    public function getToken(){
        return $this->data['token'];
    }
}
