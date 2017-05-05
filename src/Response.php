<?php
class UsResponse{
    private $ret;
    private $request;
    private $session;
    private $nlu;
    private $sourceType;
    private $confirm;
    private $shouldEndSession;

    public function __construct($request, $session, $nlu){
        $this->request = $request;
        $this->session = $session;
        $this->nlu = $nlu;
        $this->sourceType = $this->request->getBotName();
    }

    public function setConfirm(){
        $this->confirm = 1; 
    }

    public function setShouldEndSession($val=true){
        if($val === false) {
            $this->shouldEndSession = false; 
        }
    }

    private static function convertViews2ResultList($views){
        $resultList=array_map(function($view){
            if($view['type']=="txt"){
                return [
                    'result_type'=>"txt",
                    "result_content"=>[
                        'answer'=>$view['content'],
                    ],
                ];
            }
            if($view['type']=="list"){
                return [
                    "result_type"=>"multi_news",
                    "result_content"=>[
                        "objects"=>array_map(function($item){
                            return array_filter([
                                "title"=>$item['title'],
                                "desc"=>$item['summary'],
                                "url"=>$item['url'],
                                "img_url"=>$item['image'],
                            ]);
                        }, $view['list']),
                    ],
                ];
            }
            return null;
        },$views);
        $resultList=array_values(array_filter($resultList));
        return $resultList;
    }
    private static function convertNlu2QueryInfo($nlu){
        
    }

    /** 
     * $data =
     * result_list:
     * views: view和resultlist2选一
     * directives: 可选
     * resource: 可选
     * speech: 可选
     * should_end_session:可选，默认true
     * confidence 可选
     */
    public function build($data){
        if(!isset($data['result_list']) && !isset($data['views'])){
            return false;
        }
        $msgData=[
            'type'=>"server",
        ];
        foreach(['resource',"directives",'views','result_list','speech'] as $key){
            if(isset($data[$key])){
                $msgData[$key] = $data[$key];
            }else{
                $msgData[$key] = [];
            }
        }
        if(!isset($data['result_list']) || !$data['result_list']){
            $msgData['result_list'] = self::convertViews2ResultList($data['views']);
        }


        /*if(isset($data['nlu'])){
            $service_query_info=self::convertNlu2QueryInfo($data['nlu']);
            $responseData['ret'][$request->getBotName()]['service_query_info']=$service_query_info;
        }
        if(isset($data['should_end_session'])){
            $responseData['ret'][$request->getBotName()]['should_end_session']=$data['should_end_session'];
        }
         */

        //$this->source_sub_type=$data['source_sub_type'];
        /*if (!isset($data['form_confidence'])) {
            $this->form_confidence = 0;
        } else {
            $this->form_confidence = $data['form_confidence'];
        }*/

        //TODO: 后续这个还有用？ 现在直接全部写死为1，
        //确认US没有用，就删除
        $resultNum = 1;
        $pageNum = 1;
        $pageCount = 1;
        //

        $confidence=isset($data['confidence'])?$data['confidence']:300;

        $msgData['user_id'] = $this->request->getUserId();
        $msgData['bot_global_state'] = $data['bot_global_state']?$data['bot_global_state']:[];
        $msgData['bot_intent'] = $data['bot_intent']?$data['bot_intent']:[];

        $result = [
            "confidence"=>$confidence,
            "source_type"=>$this->sourceType,
            "content"=>json_encode($msgData, JSON_UNESCAPED_UNICODE),
            'stategy_middle_data'=>self::getMiddleData($msgData),
        ];

        if ($this->confirm) {
            $result['confirm'] = 1;
        }

        $ret=[
            'status'=>0,
            "msg"=>"ok",
            'data'=>[
                'directives'=>$msgData['directives'],
                'speech'=>$msgData['speech'],
                'resource'=>$msgData['resource'],
                'views'=>$msgData['views'],
                'result_list'=>[$result],
                'page_num' => 1,
                'page_cnt' => 1,
                'result_num' => 1,
                'service_query_info'=>[$this->nlu->toQueryInfo()],
                //'server_query_intent'=>json_encode($server_query_intent[0]?$server_query_intent[0]:"",JSON_UNESCAPED_UNICODE),
                'server_query_intent'=>json_encode($this->nlu->toQueryIntent(), JSON_UNESCAPED_UNICODE),
            ],
            'bot_sessions'=>[$this->session->toResponse()],
        ];
        if($this->shouldEndSession === false){
            $ret['should_end_session'] = false;
        }
        
        $str=json_encode($ret, JSON_UNESCAPED_UNICODE);
        return $str;
    }

    public static function getMiddleData($msgData){
        $result_list=$msgData['result_list'];
        $ret=[];
        foreach($result_list as $result){
            $content=$result['result_content'];
            $source_type = $result['result_type'];
            $url="";
            if(isset($content['url'])){
                $url=$content['url'];
            }
            if(isset($content['link'])){
                $url=$content['link'];
            }
            $ret[]= [
                'title'=>isset($content['title'])?$content['title']:"",
                'subtitle'=>isset($content['subtitle'])?$content['subtitle']:"",
                'answer'=>isset($content['answer'])?$content['answer']:"",
                'url'=>$url,
            ] ;

            if ($source_type == 'multi_normal') {
                $one_obj = $result['result_content']['objects'][0];
                if (empty($ret[0]['title']) && !empty($one_obj['name'])) {
                    $ret[0]['title'] = $one_obj['name'];
                }
                if (empty($ret[0]['subtitle']) && !empty($one_obj['desc'])) {
                    $ret[0]['subtitle'] = $one_obj['name'];
                }
                if (empty($ret[0]['url']) && !empty($one_obj['url'])) {
                    $ret[0]['url'] = $one_obj['url'];
                }
            }
        }

        if (!empty($result_list[0]['source_type'])) {
            if ($result_list[0]['source_type'] == 'music') {
                if (empty($ret[0]['title'])){
                    $ret[0]['title'] = isset($ret[1]['title'])?$ret[1]['title']:"";
                }
                if (empty($ret[0]['url'])) {
                    $ret[0]['url'] = isset($ret[1]['url'])?$ret[1]['url']:"";
                }
                if (empty($ret[0]['subtitle'])) {
                    $ret[0]['subtitle'] = "music";
                }
            }
        }

        $res_middle_data = ['raw_answer'=>$ret];
        $bot_global_state = $msgData['bot_global_state'];
        if (!empty($bot_global_state)) {
             $res_middle_data["bot_global_state"] = $bot_global_state;
        }
        $bot_intent = $msgData['bot_intent'];
        if (!empty($bot_intent)) {
             $res_middle_data["bot_intent"] = $bot_intent;
        }

        $bot_answer = $msgData['bot_answer'];
        if (!empty($bot_answer)) {
             $res_middle_data["bot_answer"] = $bot_answer;
        }
        return $res_middle_data;
    }
}
