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
 * @desc 用于生成TraitPlayerInfo的类
 **/
namespace Baidu\Duer\Botsdk\Directive\Base;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\BaseButton;
use Baidu\Duer\Botsdk\Directive\Base\BasePlayerInfoContent;

/**
 * @desc TraitPlayerInfo类
 */
trait TraitPlayerInfo{

    protected $data;

    protected $content;

    protected $controls = [];

    /**
     * @desc 设置控件列表
     * @param Control|array $control 控件列表 
     */
    public function setControls($controls){
        $this->controls = [];
        if($controls instanceof BaseButton){
            $this->controls[] = $controls;
        }
        if(is_array($controls)){
            foreach($controls as $control){
                if($control instanceof BaseButton){
                   $this->controls[] = $control; 
                }
            }
        } 
    }

    /**
     * @desc 增加一个控件
     * @param Control $control 控件
     */
    public function addControl($control){
        if($control instanceof BaseButton){
            $this->controls[] = $control;
        }
    }

    /**
     * @desc 设置content
     * @param BasePlayerInfoContent $content
     */
    public function setContent($content){
        if($content instanceof BasePlayerInfoContent){
            $this->content = $content;
        }
    }

    /**
     * @desc 获取data
     * @return array 
     */
    public function getData(){
        if(!$this->data){
            $this->data = []; 
        }

        if($this->content){
            $this->data['content'] = $this->content->getData();
        }
        if($this->controls){
            foreach($this->controls as $control){
                $this->data['controls'][] = $control->getData();
            }
        }

        return $this->data;
    }

}
 

