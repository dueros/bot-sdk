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

class RenderVideoList extends \Baidu\Duer\Botsdk\Directive\BaseDirective{
    const REPLACE = 'REPLACE';
    const APPEND = 'APPEND';
    const PREPEND = 'PREPEND';

    private $videoItems = [];

    /**
     * @param string $title 列表的标题
     * @param string $behavior 默认替换所有
     *               REPLACE: 清空当前的列表，再渲染，用于首次第一页数据的渲染
     *               APPEND: 当前列表不变，在当前的列表后面渲染，用于往后翻页的渲染
     *               PREPEND:当前列表不变，在当前的列表前面渲染，用于往前翻页的渲染 
     *
     * @return null
     **/
    public function __construct($title, $behavior = self::REPLACE) {
        parent::__construct('Display.RenderVideoList');
        $data = array(
            'token' => $this->genToken(),
            'title' => $title,
            'behavior' => $behavior,
            'size' => 0,
            'videoItems' => []
        );
        $this->data = array_merge($this->data, $data);
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

    /**
     * 增加audioItem
     * @param AudioItem $audioItem
     */
    public function addVideoItem($videoItem){
        if($videoItem instanceof VideoItem){
            $this->videoItems[] = $videoItem; 
            ++$this->data['size'];
        } 
    }

    /**
     * 获取数据
     * @return array
     */
    public function getData(){
        if($this->videoItems){
            foreach($this->videoItems as $videoItem){
                $this->data['videoItems'][] = $videoItem->getData(); 
            } 
        }
        return $this->data;
    }

}
