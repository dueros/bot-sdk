<?php
namespace Baidu\Duer\Botsdk;

class Nlu{
    const SLOT_NOT_UNDERSTAND = "da_system_not_understand";
    private $data = [];
    public function __construct($data) {
        $this->data = $data; 
    }

    public static function parseQueryInfo($data){
        $slot = $data['result_list'][0]['result_list'];
        $ret = [
            'domain' => $data['type'],
            'intent' => $data['result_list'][0]['type'],
            'slots' => array_map(function($item){
                return [
                    'name' => $item['value'][0]['name'],
                    'value' => $item['value'][0]['value'],
                ];
            },$slot?$slot:[]),
        ]; 

        return new self($ret);
    }

    public function setSlot($field, $value){
        if(empty($field)){
            return;
        }
    
        foreach($this->data['slots'] as &$slot) {
            if($slot['name'] == $field) {
                $slot['value'] = $value;
                return;
            }
        }

        $this->data['slots'][] = [
            'name' => $field,
            'value' => $value,
        ];
    }

    public function getSlot($field) {
        if(empty($field)){
            return;
        }

        foreach($this->data['slots'] as &$slot) {
            if($slot['name'] == $field) {
                return $slot['value'];
            }
        }
    }

    public function getIntent(){
        return $this->data['intent'];
    }

    public function toQueryInfo(){
        $nlu = $this->data;
        $service_query_info = [
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
                                    "key"=>$slot['key'],
                                    "type"=>"text",
                                    "score"=>100,
                                    "value"=>[
                                        [
                                            "name"=>$slot['key'],
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
        return $service_query_info;
    }

    /**
     * @desc 询问某些槽位。如果有询问一些槽位，表明多轮进行中
     * @param string|array $slot
     **/
    public function needAsk($slot){
        if(!$slot) {
            return;
        }

        if(is_string($slot)) {
            $slot = [$slot]; 
        }

        if(!is_array($slot)) {
            return; 
        }
        $intent = [self::SLOT_NOT_UNDERSTAND];
        array_splice($intent, -1, 0, $slot);
        $this->ask = $intent;
    }

    /**
     * @desc 给出一些选项，让用户进行选择
     * @param array $list , ['query' => '', 'slot' => '', 'value' => '']
     * @return
     **/
    public function needSelect($list){
        if(array_keys($list) != range(0, count($list) - 1)){
            $list = [$list];
        }

        $this->select = array_map(function($s){
            $s['action'] = 'slot_merge';
            return $s;
        }, $list);
    }

    /**
     * @desc 询问用户希望得到YES or NO的回答
     * @param array $slotYes eg: ['slot'=>'name of slot', 'value'=>'如果是肯定回答，填充的值']
     * @param array $slotNo eg: ['slot'=>'name of slot', 'value'=>'如果是否定回答，填充的值']
     * @return null
     **/
    public function needCheck($slotYes, $slotNo){
        $this->check = [
            'true_action'  => ['slot' => $slotYes['slot'], 'value' => $slotYes['value'].''],
            'false_action' => ['slot' => $slotNo['slot'], 'value' => $slotNo['value'].''],
        ];
    }

    public function toQueryIntent(){
        $intents=[];

        //ask
        $askSlots=$this->ask;
        if(!empty($askSlots)){
            $askIntent=['intent_name'=>'ask','params'=>['slot'=>[]]];
            foreach ($askSlots as $askSlot){
                $askIntent['params']['slot'][]=$askSlot;
            }
            $intents[]=$askIntent;
        }

        //select
        $selectValues=$this->select;
        if(!empty($selectValues)){
            $selectIntent=['intent_name'=>'select','params'=>['select_set'=>[]]];
            foreach ($selectValues as $selectValue){
                $query=$selectValue['query'];
                $actions=$selectValue['action'];
                if(!is_array($actions)){
                    $actions=[['name'=>$selectValue['action'],'intent'=>$selectValue['intent'],'slot'=>$selectValue['slot'],'value'=>$selectValue['value']]];
                }
                $selectIntent['params']['select_set'][]=[
                    'query'=>$query,
                    'action'=>$actions
                ];
            }
            $intents[]=$selectIntent;
        }

        //check
        $checkValues=$this->check;
        if(!empty($checkValues['true_action']) && !empty($checkValues['false_action'])){
            $trueAction=$checkValues['true_action'];
            $falseAction=$checkValues['false_action'];

            $checkIntent=['intent_name'=>'check','params'=>['true_action'=>[],'false_action'=>[]]];
            $checkIntent['params']['true_action']=[
                [
                    'name'=>'slot_merge',
                    'slot'=>$trueAction['slot'],
                    'value'=>$trueAction['value']
                ]
            ];
            $checkIntent['params']['false_action']=[
                [
                    'name'=>'slot_merge',
                    'slot'=>$falseAction['slot'],
                    'value'=>$falseAction['value']
                ]
            ];
            $intents[]=$checkIntent;
        }

        $ret = [];
        $ret['intent'] = $intents;
        //$ret['session_timeout'] = $this->bot_conf['session_timeout'];
        $ret['session_timeout'] = 300;
		return $ret;
	}
}
 
