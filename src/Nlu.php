<?php
/**
 * DA解析query，分析的结果
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Nlu{
    /**
     * @desc 一般处于多轮的服务，会对一个slot进行询问。
     * 但是，会出现答非所问的情况，或者解析覆盖不到的地方
     * 如果出现上述情况，解析会填这个字段, value为int，计数出现了多少次
     **/
    const SLOT_NOT_UNDERSTAND = "da_system_not_understand";

    /**
     * 数据
     **/
    private $data = [];


    /**
     * @param array $data
     * @return null
     **/
    public function __construct($data) {
        $this->data = $data; 
    }

    /**
     * @desc 将复杂的NLU结构化简
     * @param array $data
     * @return array
     **/
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

    /**
     * @desc 设置slot。如果不存在，新增一个slot
     * @param string $field
     * @param string $value
     * @return null
     **/
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

    /**
     * @param string $field
     * @return string
     **/
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

    /**
     * @desc 获取当前domain的intent
     * @param null
     * @return string
     **/
    public function getIntent(){
        return $this->data['intent'];
    }

    /**
     * @desc 转化为复杂结果
     * @param null
     * @return array
     **/
    public function toQueryInfo(){
        $nlu = $this->data;
        if(!$nlu) {
            return; 
        }

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
                                    "key"=>$slot['name'],
                                    "type"=>"text",
                                    "score"=>100,
                                    "value"=>[
                                        [
                                            "name"=>$slot['name'],
                                            "value"=>$slot['value'],
                                            "type"=>"string",
                                            "source"=>"query",
                                            "session_num"=>0,
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
     * @desc 是否有询问用户
     * 通过ask判断
     *
     * @param null
     * @return boolean
     **/
    public function hasAsk(){
        return !!$this->ask; 
    }

    /**
     * @desc 询问某些槽位。如果有询问一些槽位，表明多轮进行中
     * @param string|array $slot
     * @return null
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
        $intent = $this->ask ? $this->ask : [self::SLOT_NOT_UNDERSTAND];
        array_splice($intent, -1, 0, $slot);
        $this->ask = array_values(array_unique($intent));
    }

    /**
     * @desc 给出一些选项，让用户进行选择
     * @param array $list , ['query' => '', 'slot' => '', 'value' => '']
     * @return null
     **/
    public function needSelect($list, $addAsk=true){
        if(array_keys($list) != range(0, count($list) - 1)){
            $list = [$list];
        }

        $this->select = array_map(function($s){
            $s['action'] = 'slot_merge';
            return $s;
        }, $list);

        //set ask
        if($addAsk){
            $this->needAsk(array_map(function($item){
                return $item['slot'];
            }, $list));
        }
    }

    /**
     * @desc 询问用户希望得到YES or NO的回答
     * @param array $slotYes eg: ['slot'=>'name of slot', 'value'=>'如果是肯定回答，填充的值']
     * @param array $slotNo eg: ['slot'=>'name of slot', 'value'=>'如果是否定回答，填充的值']
     * @return null
     **/
    public function needCheck($slotYes, $slotNo, $addAsk=true){
        $this->check = [
            'true_action'  => ['slot' => $slotYes['slot'], 'value' => $slotYes['value'].''],
            'false_action' => ['slot' => $slotNo['slot'], 'value' => $slotNo['value'].''],
        ];

        //set ask
        if($addAsk){
            $this->needAsk(array_map(function($item){
                return $item['slot'];
            }, [$slotYes, $slotNo]));
        }
    }

    /**
     * @desc 打包NLU交互协议
     * @param null
     * @return array
     **/
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
 
