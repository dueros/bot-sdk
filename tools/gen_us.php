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
 **/

require_once dirname(__FILE__) . '/genUsData.php';

if(php_sapi_name()=='cli'){
    $file = $argv[1];
    if(!$file) {
        exit(1);
    }

    $filename = $file;

    if(!file_exists($filename)) {
        echo "文件不存在\n";
        exit(1);
    }

    $sendData = require $filename;

    $template = dirname($filename) . '/template.json';
    if(!file_exists($template)) {
        echo "没有找到template.json\n";
        exit(1); 
    }

    $usData = json_decode(file_get_contents($template), true);
    print json_encode(genUsData($usData, $sendData));
}


