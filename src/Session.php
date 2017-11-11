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
 * @desc 短期记忆。DuerOS来维护，如果回复了bot的结果，session才会生效
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Session{
    use DataObject;

    /**
     * @param array $data
     * @return null
     **/
    public function __construct($data) {
        $this->data = isset($data['attributes'])?$data['attributes']:[]; 
        $this->sessionId = isset($data['sessionId'])?$data['sessionId']:'';
        $this->isNew = isset($data['new'])?$data['new']:false;
    }

    /**
     * 清空session
     * @desc 清空session，最终返回DuerOS的response session的attributes为空
     * @param null
     * @return null
     **/
    public function clear(){
        $this->data = []; 
    }

    /**
     * @desc 打包sesson，将session对象输出，返回Response中需要的session格式
     * @param Request $request
     * @return array
     **/
    public function toResponse(){
        return [
            'attributes' => $this->data?$this->data:(object)[],
        ];
    }
}
