<?php
/**
 * 短期记忆。中控来维护，如果回复了bot的结果，session才会生效
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
        if(!$data || $data['empty'] == true) {
            $data = []; 
        }
        $this->data = $data; 
    }

    /**
     * @param null
     * @return null
     **/
    public function clear(){
        $this->data = []; 
    }

    /**
     * @desc 打包sesson
     * @param Request $request
     * @return array
     **/
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
