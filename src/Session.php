<?php
namespace Baidu\Duer\Botsdk;

class Session{
    use DataObject;

    public function __construct($data) {
        $this->data = $data; 
    }

    public function clear(){
        $this->data = []; 
    }

    public function toResponse($request){
        $data = $this->data;
        if(!$data) {
            $data = ['empty' => true]; 
        }

        return [
            'status'=>0,
            "msg"=>"ok",
            'action'=>"set",
            'type'=>"string",
            'key'=>$request->getUserId(),
            'list_sessions_str'=>[json_encode($data, JSON_UNESCAPED_UNICODE)],
        ];
    }
}
