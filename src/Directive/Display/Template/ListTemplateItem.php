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
 * @desc ListTemplateItem类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template;
use Baidu\Duer\Botsdk\Directive\Display\Template\Tag\BaseTag;

class ListTemplateItem extends \Baidu\Duer\Botsdk\Directive\Display\Template\BaseTemplate {

    protected $imageTags;

    /**
     * ListTemplateItem constructor.
     */
    public function __construct() {
        parent::__construct(['token']);
    }


    /**
     * @desc 设置图片
     * @param string $url
     * @param string $widthPixels
     * @param string $heightPixels
     */
    public function setImage($url, $widthPixels = '', $heightPixels = ''){
        $imageStructure = $this->createImageStructure($url, $widthPixels, $heightPixels);
        if($imageStructure) {
            $this->data['image'] = $imageStructure;
        }
    }

    /**
     * @desc 设置图片tags
     * @param mixed $tags
     * @return null
     */
    public function setImageTags($tags){
        if(!$tags){
            return; 
        }
        if(!is_array($tags)){
            $tags = [$tags]; 
        }
        foreach($tags as $tag){
            if($tag instanceof BaseTag){
                $this->imageTags[] = $tag;
            }
        }
    }

    /**
     * @desc 设置列表元素一级标题
     * @param string $text 文本内容
     */
    public function setPlainPrimaryText($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        if($textStructure) {
            $this->data['textContent']['primaryText'] = $textStructure;
        }
    }

    /**
     * @desc 设置列表元素二级标题
     * @param string $text 文本内容
     */
    public function setPlainSecondaryText($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        if($textStructure) {
            $this->data['textContent']['secondaryText'] = $textStructure;
        }
    }

    /**
     * @desc 设置列表元素三级标题
     * @param string $text 文本内容
     */
    public function setPlainTertiaryText($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        if($textStructure) {
            $this->data['textContent']['tertiaryText'] = $textStructure;
        }
    }

    /**
     * @desc 设置列表元素标题
     * @param string $text 文本内容
     */
    public function setContent($text){
        $textStructure = $this->createTextStructure($text, self::PLAIN_TEXT);
        if($textStructure) {
            $this->data['content'] = $textStructure;
        }
    }

    /**
     * @desc 返回数据
     * @param string $key
     * @return array|mixed
     */
    public function getData($key=''){
        if(isset($this->data['image']) && $this->imageTags){
            $this->data['image']['tags'] = $this->getImageTagsData($this->imageTags); 
        }
        if($key) {
            return $this->data[$key];
        }
        
        return $this->data;
    }

    /**
     * @desc 获取imageTags的数据
     * @param array $tags
     * @return array
     */
    protected function getImageTagsData($tags){
        $data = [];
        if(!$tags || !is_array($tags)){
            return $data; 
        } 
        foreach($tags as $tag){
            $data[] = $tag->getData(); 
        }
        return $data;
    }

    /**
     * @desc 设置当前元素的名字
     * @param string $anchorWord 名称
     */
    public function setAnchorWord($anchorWord){
        if($anchorWord && is_string($anchorWord)) {
            $this->data['anchorWord'] = $anchorWord;
        }
    }

}
 
