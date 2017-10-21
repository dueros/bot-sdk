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
 * @desc 图像卡片类
 **/
namespace Baidu\Duer\Botsdk\Card;

class ImageCard extends \Baidu\Duer\Botsdk\Card\BaseCard{

    /**
     * @param null
     * @return null
     **/
    public function __construct() {
        $this->data['type'] = 'image';
        parent::__construct();
    }

    /**
     * 添加一个图片项
     * @param string $src 图片地址
     * @param string $thumbnail  图片缩率图地址
     * @return ImageCard
     **/
    public function addItem($src, $thumbnail=''){
        if(!$src) {
            return $this; 
        }

        if(!$this->data['list']) {
            $this->data['list'] = [];
        }

        $item = [];
        $item['src'] = $src;
        if($thumbnail) {
            $item['thumbnail'] = $thumbnail;
        }

        $this->data['list'][] = $item;
        return $this;
    }
}
