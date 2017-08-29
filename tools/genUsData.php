<?php

/**
 * @param null
 * @return null
 **/
function genUsData($usData, $sendData){
    if($sendData['query']) {
        $usData['query'] = $sendData['query'];
    }

    if($sendData['bot_name']) {
        $usData['bot_name'] = $sendData['bot_name'];
    }

    if($sendData['nlu']) {
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
    }

    //us confirm
    if($sendData['confirm']) {
        $usData['confirm'] = 1;
    }

    //session
    $session = [
        'status'=>0,
        "msg"=>"ok",
        'action'=>"set",
        'type'=>"string",
        'key'=>'',
        'list_sessions_str'=>[json_encode($sendData['session']?$sendData['session']:['empty'=>true], JSON_UNESCAPED_UNICODE)],
    ];

    $usData['bot_sessions'] = [$session];
    return $usData;
}


function genUsDataV2($usData, $sendData){
    $usData['request']['type'] = 'IntentRequest';
    if($sendData['type']) {
        $usData['request']['type'] = $sendData['type'];
    }

    if($sendData['request']) {
        $usData['request'] = array_merge($usData['request'], $sendData['request']);
    }

    if($sendData['query']) {
        $usData['request']['query']['original'] = $sendData['query'];
    }

    if($sendData['bot_name']) {
        $usData['context']['system']['bot']['botId'] = $sendData['bot_name'];
    }

    $intent = $sendData['intent'];
    if($intent) {
        //trick
        if($intent[0] === null) {
            $intent = [$intent]; 
        }

        $arr = [];
        foreach($intent as $item) {
            $i = [
                'name' => $item['name'],
                'slots' => [],
            ]; 

            foreach($item['slots'] as $slot) {
                $i['slots'][$slot['name']] = $slot;
            }

            $arr[] = $i;
        }

        $usData['request']['intents'] = $arr;
    }

    $session = $sendData['session'];
    if($session) {
        $usData['session']['attributes'] = $session;
    }

    return $usData;
}
