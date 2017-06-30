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
        $this->data = $data['attributes']; 
        $this->sessionId = $data['sessonId'];
        $this->isNew = $data['new'];
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
    public function toResponse(){
        return [
            'attributes' => $this->data,
        ];
    }
}
