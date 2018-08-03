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
 * @desc 用于生成RenderAudioPlayerInfo指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display;

use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\BaseButton;
use Baidu\Duer\Botsdk\Directive\Base\BasePlayerInfoContent;
use Baidu\Duer\Botsdk\Directive\Base\TraitPlayerInfo;

class BaseRenderPlayerInfo extends \Baidu\Duer\Botsdk\Directive\BaseDirective{
    use TraitPlayerInfo;

    /**
     * @param string $type 指令类型
     * @param BasePlayerInfoContent $content  内容
     * @param string $controls 控件
     * @return null
     **/
    public function __construct($type, $content = null, $controls = []) {
        parent::__construct($type);
        $this->data['token'] = $this->genToken();
        $this->setContent($content);
        $this->setControls($controls);
    }

    /**
     * 设置token
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token 视频的token
     * @return null
     **/
    public function setToken($token){
        if($token) {
            $this->data['token'] = $token;
        }
    }
    
}
