<?php
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

    $template = dirname($filename) . '/template-v2.json';
    if(!file_exists($template)) {
        echo "没有找到template-v2.json\n";
        exit(1); 
    }

    $usData = json_decode(file_get_contents($template), true);
    print json_encode(genUsDataV2($usData, $sendData));
}


