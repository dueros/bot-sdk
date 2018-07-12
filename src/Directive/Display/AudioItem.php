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
 **/
namespace Baidu\Duer\Botsdk\Directive\Display;
use Baidu\Duer\Botsdk\Utils;

/**
 * @desc AudioItem类
 */
class AudioItem extends BaseMediaListItem{

    /**
     * @desc __construct
     * @param string $title 音频类型
     * @param string $titleSubtext1 音频类型
     */
    public function __construct($title, $titleSubtext1) {
        parent::__construct($title, $titleSubtext1);
    }

    /**
     * @desc 设置isMusicVideo
     * @param bool $bool
     */
    public function setMusicVideoTag($bool){
        if(is_bool($bool) && $bool){
            $this->data['isMusicVideo'] = $bool; 
        }
    }

}
 

