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
 * @desc BaseListItem类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display;
use Baidu\Duer\Botsdk\Utils;

/**
 * @desc BaseListItem类
 */
class BaseMediaListItem{

    protected $data = [];

    /**
     * @desc __construct
     * @param string $title 音频|视频类型
     * @param string $titleSubtext1 音频|视频类型
     */
    public function __construct($title, $titleSubtext1) {
        $this->data['title'] = $title;
        $this->data['titleSubtext1'] = $titleSubtext1;
        $this->data['token'] = Utils::genToken();
    }

    /**
     * @desc 设置token
     * @param string $token
     */
    public function setToken($token){
        if(is_string($token) && $token){
            $this->data['token'] = $token; 
        }
    }

    /**
     * @desc 设置isFavorited
     * @param bool $bool
     */
    public function setFavorited($bool){
        if(is_bool($bool) && $bool){
            $this->data['isFavorited'] = $bool; 
        }
    }

    /**
     * @desc 设置image
     * @param string $image
     */
    public function setImage($image){
        if(is_string($image) && $image){
            $this->data['image']['src'] = $image; 
        }
    }

    /**
     * @desc 设置titleSubtext1
     * @param string $titleSubtext1
     */
    public function setTitleSubtext1($titleSubtext1){
        if(is_string($titleSubtext1) && $titleSubtext1){
            $this->data['titleSubtext1'] = $titleSubtext1;
        }
    }

    /**
     * @desc 设置titleSubtext2
     * @param string $titleSubtext2
     */
    public function setTitleSubtext2($titleSubtext2){
        if(is_string($titleSubtext2) && $titleSubtext2){
            $this->data['titleSubtext2'] = $titleSubtext2; 
        }
    }

    /**
     * @desc 获取data
     * @return array 
     */
    public function getData(){
        return $this->data;
    }

}
 

