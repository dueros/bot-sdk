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
 * @desc 卡片的基类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

class BodyTemplate2 extends \Baidu\Duer\Botsdk\Directive\Display\Template\BaseTemplate {
    /**
     * BodyTemplate2 constructor.
     */
    public function __construct() {
        $this->data['type'] = 'BodyTemplate2';
        parent::__construct(['token', 'title', 'content']);
    }

    /**
     * @desc 设置图片
     * @param string $url
     * @param string $widthPixels
     * @param string $heightPixels
     * @return array
     */
    public function setImage($url, $widthPixels = '', $heightPixels = ''){
        if(!$url) {
            return [];
        }
        $this->data['image']['url'] = $url;
        if($widthPixels){
            $this->data['image']['widthPixels'] = $widthPixels;
        }
        if($heightPixels){
            $this->data['image']['heightPixels'] = $heightPixels;
        }
    }

}
 
