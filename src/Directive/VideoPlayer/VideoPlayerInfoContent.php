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
 * @desc 用于生成VideoPlayerInfoContent的类
 **/
namespace Baidu\Duer\Botsdk\Directive\VideoPlayer;
use Baidu\Duer\Botsdk\Directive\Base\BasePlayerInfoContent;
/**
 * @desc VideoPlayerInfoContent类
 */
class VideoPlayerInfoContent extends BasePlayerInfoContent{

    /**
     * @desc __construct
     * @param string $title 
     * @param int $mediaLengthInMilliseconds
     */
    public function __construct($title = '', $mediaLengthInMilliseconds = 0) {
        $this->setTitle($title);
        $this->setMediaLengthInMilliseconds($mediaLengthInMilliseconds);
    }

    /**
     * @desc 设置title值
     * @param string $title 视频的标题
     */
    public function setTitle($title){
        if(is_string($title)){
            $this->data['title'] = $title;
        }
    }

    /**
     * @desc 设置视频流的长度
     * @param int $mediaLengthInMilliseconds 视频流的长度
     */
    public function setMediaLengthInMilliseconds($mediaLengthInMilliseconds){
        if(is_numeric($mediaLengthInMilliseconds) && $mediaLengthInMilliseconds){
            $this->data['mediaLengthInMilliseconds'] = intval($mediaLengthInMilliseconds);
        }
    }

}
 

