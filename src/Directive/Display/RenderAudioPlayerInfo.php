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
use Baidu\Duer\Botsdk\Directive\AudioPlayer\AudioPlayerInfoContent;
class RenderAudioPlayerInfo extends BaseRenderPlayerInfo{

    /**
     * @param BasePlayerInfoContent $content  内容
     * @param string $controls 控件
     * @return null
     **/
    public function __construct($content = null, $controls = []) {
        parent::__construct('Display.RenderAudioPlayerInfo', $content, $controls);
    }

}
