<?php

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

//for da_query_info
$daQueryInfo = $usData['data']['da_query_info'];

$daQueryInfo = array_filter($daQueryInfo, function ($item) use ($sendData){
    return $item['type'] != $sendData['nlu']['domain'];
});

$daQueryInfo = array_values($daQueryInfo);

$nlu = $sendData['nlu'];
$daQueryInfo[] = [
    "query"=> "",
    "type"=> $nlu['domain'],
    "result_list"=> [
        [
            "type"=> $nlu['domain'],
            "score"=> 100,
            "result_list"=> [
                [
                    "type"=> $nlu['intent'],
                    "score"=> 100,
                    "content"=> "",
                    "result_list"=> array_map(function($slot){
                        return [
                            "key"=>$slot['name'],
                            "type"=>"text",
                            "score"=>100,
                            "value"=>[
                                [
                                    "name"=>$slot['name'],
                                    "value"=>$slot['value'],
                                ],
                            ],
                        ];
                    },$nlu['slots']?$nlu['slots']:[])
                    ]
                ]
            ]
        ]
    ];


$usData['data']['da_query_info'] = $daQueryInfo;

//us confirm
if($sendData['confirm']) {
    $usData['confirm'] = 1;
}

print json_encode($usData);
