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
 * @desc 拦截器
 * 通过重载before，能够在处理通过addHandler，addEventListener添加的回调之前，定义一些逻辑。
 * 通过重载after能够对回调函数的返回值，进行统一的处理
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

abstract class Intercept{
    /**
     * @param Bot $bot
     * @return mixed
     * 如果返回非null，跳过后面addHandler，addEventListener添加的回调
     **/
    public function preprocess($bot) {
    
    }

    /**
     * @desc 在调用response->build 之前统一对handler的输出结果进行修改
     * @param Bot $bot
     * @param array
     * @return array
     **/
    public function postprocess($bot, $result){
        return $result;
    }
}
