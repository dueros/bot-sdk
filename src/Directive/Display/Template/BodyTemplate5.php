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
 * @desc 图片模板类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;

class BodyTemplate5 extends \Baidu\Duer\Botsdk\Directive\Display\Template\BaseTemplate {
    /**
     * @example
     * <pre>
     * $bodyTemplate = new BodyTemplate5();
     * $bodyTemplate->setToken('token');
     * $bodyTemplate->addImages('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg'') //设置images数组
     * $bodyTemplate->setBackGroundImage('https://skillstore.cdn.bcebos.com/icon/100/c709eed1-c07a-be4a-b242-0b0d8b777041.jpg'');
     * $bodyTemplate->setTitle('托尔斯泰的格言');
     * </pre>
     * BodyTemplate5 constructor.
     */
    public function __construct() {
        $this->data['type'] = 'BodyTemplate5';
        parent::__construct(['token', 'title']);
    }

    /**
     * @desc 添加图片
     * @param string $url 图片地址
     * @param string $widthPixels 图片宽度
     * @param string $heightPixels 图片高度
     */
    public function addImages($url, $widthPixels = '', $heightPixels = '') {
        if(!isset($this->data['images']) || !$this->data['images']) {
            $this->data['images'] = [];
        }
        $imageStructure = $this->createImageStructure($url, $widthPixels, $heightPixels);
        if($imageStructure) {
            $this->data['images'][] = $imageStructure;
        }
    }

}
 
