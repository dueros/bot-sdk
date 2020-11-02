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
 * @desc DPL文档对象
 **/
namespace Baidu\Duer\Botsdk\Directive\DPL;

/**
 * DPL文档对象
 */
class Document {
    /**
     * @desc Document 构造方法.
     * @param array $doc 初始化json
     */
    public function __construct($doc = []) {
        $this->data = [];
        if (is_array($doc)) {
            $this->data = $doc;
        }
    }

    /**
     * @desc 从path中读取document配置文件生成文档对象
     * @param string $path 绝对路径
     */
    public function getDocumentFromPath($path) {
        $doc = json_decode(file_get_contents($path), true);
        $this->data = $doc;
    }

    /**
     * @desc 获取data
     * @return array 返回文档对象数据
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @desc 初始化文档对象数据
     * @param array $data 初始化数据
     */
    public function initDocument($data) {
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    /**
     * @desc 设置模版渲染停留时间
     * @param number $duration 初始化数据
     */
    public function setDocumentDuration($duration) {
        if (is_numeric('number')) {
            $this->data['duration'] = $duration;
        }
    }
}


