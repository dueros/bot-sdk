<?php
require 'vendor/autoload.php';

use \Baidu\Duer\Botsdk\Directive\DPL\Document;
use \Baidu\Duer\Botsdk\Directive\DPL\RenderDocument;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\SetStateCommand;
use \Baidu\Duer\Botsdk\Directive\DPL\ExecuteCommands;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\UpdateComponentCommand;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\ScrollCommand;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\SetPageCommand;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\ControlMediaCommand;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\AnimationCommand;
use \Baidu\Duer\Botsdk\Directive\DPL\Commands\ScrollToIndexCommand;

class Bot extends Baidu\Duer\Botsdk\Bot {
    
    protected static $VideoList = [
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video2.mp4",
            "index" => "video_list_1",
            "name"  => "葡萄酒",
            "desc"  => "人生就像一杯酒",
        ],
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video4.mp4",
            "index" => "video_list_2",
            "name"  => "初夏",
            "desc"  => "最美人间四月天",
        ],
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video3.mp4",
            "index" => "video_list_3",
            "name"  => "海",
            "desc"  => "我要和你一起看日出, 面向大海",
        ],
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video4.mp4",
            "index" => "video_list_4",
            "name"  => "心动的感觉",
            "desc"  => "你知道我对你不仅仅是喜欢",
        ],
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video5.mp4",
            "index" => "video_list_5",
            "name"  => "冷月",
            "desc"  => "曾经有一个美丽的女孩追求过我，但是我没有接受，现在后悔了",
        ],
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video6.mp4",
            "index" => "video_list_6",
            "name"  => "给大家讲一个笑话吧",
            "desc"  => "你就是一个笑话",
        ],
        [
            "src"   => "https://dbp-dict.bj.bcebos.com/video7.mp4",
            "index" => "video_list_7",
            "name"  => "加班，加班",
            "desc"  => "很可以",
        ]
    ];
    
    public function __construct($postData = []) {
        parent::__construct($postData);
        
        $this->addHandler('LaunchRequest', function(){
            $this->waitAnswer();
            return [
                'outputSpeech' => 'DPL演示'
            ];
        });
    
        $this->addHandler('#dpl_demo1', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive = $this->genDPLDirective('./doc/demo1.json');
            if ($directive) {
                return [
                    'directives'   => [$directive],
                    'outputSpeech' => '简单图片'
                ];
            }
        });
    
        //事件监听
        $this->addEventListener('UserEvent', 'handleUserEvent');
    
        $this->addDefaultEventListener(function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
        });
    
        //demo2 长文本
        $this->addHandler('#dpl_demo2', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive = $this->genDPLDirective('./doc/demo2.json');
            if ($directive) {
                return [
                    'directives' => [$directive],
                    'outputSpeech' => '长文本'
                ];
            }
        });
    
        //demo3 短文本
        $this->addHandler('#dpl_demo3', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive = $this->genDPLDirective('./doc/demo3.json');
            return [
                'directives' => [$directive],
                'outputSpeech' => '短文本'
            ];
        });
    
        //demo4 右图详情
        $this->addHandler('#dpl_demo4', function() {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive =  $this->genDPLDirective('./doc/demo3.json');
            return [
                'directives' => [$directive],
                'outputSpeech' => '右图详情'
            ];
        });
    
        //demo5 左图详情
        $this->addHandler('#dpl_demo5', function (){
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive = $this->genDPLDirective('./doc/demo5.json');
            return [
                'directives' => [$directive],
                'outputSpeech' => '左图详情'
            ];
        });
    
        //demo6 横向列表
        $this->addHandler('#dpl_demo6', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive = $this->genDPLDirective('./doc/demo6.json');
            return [
                'directives' => [$directive],
                'outputSpeech' =>  '横向列表'
            ];
        });
    
        $this->addHandler('#dpl_demo7', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $directive =  $this->genDPLDirective('./doc/demo7.json');
            return [
                'directives' => [$directive],
                'outputSpeech' => '视频相册'
            ];
        });
    
        //pull_scrollView
        $this->addHandler('#pull_scrollview', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $executeCommands = new ExecuteCommands();
            $scrollCommand = new ScrollCommand();
            $scrollCommand->setDistance("200dp");
            $scrollCommand->setComponentId("demo_pull_scrollview_compid");
            $executeCommands->setCommands($scrollCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '滑动窗口滑动'
            ];
        });
    
        //选择播放第几个
        $this->addHandler('#video_play', function (){
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $index = $this->getSlot('index');
            $index = $index - 1 >= 0 ? $index - 1 : 0;
            $executeCommands = new ExecuteCommands();
            $updateComponentCommand = new UpdateComponentCommand();
            $doc = new Document();
            $doc->getDocumentFromPath('./doc/update.json');
            $content = $doc->getData();
            $content['mainTemplate']['items'][0]['items'][0]['src'] = self::$VideoList[$index]['src'];
            $content['mainTemplate']['items'][0]['items'][1]['items'][0]['text'] = self::$VideoList[$index]['name'];
            $doc->initDocument($content);
            $updateComponentCommand->setDocument($doc);
            $updateComponentCommand->setComponentId("replaceComponentId");
            $executeCommands->setCommands($updateComponentCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '正在播放' . self::$VideoList[$index]['name']
            ];
        });
    
        //move_list
        //向上滑动列表
        $this->addHandler('#move_list', function() {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $direction = $this->getSlot('direction') ? $this->getSlot('direction') : '下';
            $distance = '100dp';
            if (in_array($direction, ['下', '后'])) {
                $distance = '-100dp';
            }
            $executeCommands = new ExecuteCommands();
            $scrollCommand = new ScrollCommand();
            $scrollCommand->setComponentId('demo_list_compid');
            $scrollCommand->setDistance($distance);
            $executeCommands->setCommands($scrollCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' =>  '向' . $direction . '滑动列表'
            ];
        });
        //go_list_top
        //回到列表顶部
        $this->addHandler('#go_list_top', function (){
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $executeCommands = new ExecuteCommands();
            $scrollToIndexCommand = new ScrollToIndexCommand();
            $scrollToIndexCommand->setComponentId('demo_list_compid');
            $scrollToIndexCommand->setAlign('first');
            $scrollToIndexCommand->setIndex(1);
            $executeCommands->setCommands($scrollToIndexCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' =>  '回到列表顶部'
            ];
        });
    
        //move_page
        //翻页
        $this->addHandler('#move_page', function() {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $direction = $this->getSlot('direction') ? $this->getSlot('direction') : '下';
            $val = 1;
            if (in_array($direction, ['右', '后'])) {
                $val = -1;
            }
            $executeCommands = new ExecuteCommands();
            $setPageCommand = new SetPageCommand();
            $setPageCommand->setComponentId('demo_move_page_compid');
            $setPageCommand->setPosition('relative');
            $setPageCommand->setValue($val);
            $executeCommands->setCommands($setPageCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '向' . $direction . '翻页'
            ];
        });
    
        //视频暂停
        $this->addHandler('#pause_video', function () {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            //demo_video_compid
            $controlMediaCommand = new ControlMediaCommand();
            $controlMediaCommand->setComponentId('demo_video_compid');
            $controlMediaCommand->setCommand('pause');
            $executeCommands = new ExecuteCommands();
            $executeCommands->setCommands($controlMediaCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '视频暂停播放'
            ];
        });
    
        //视频继续播放
        $this->addHandler('#video_continue', function (){
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            //demo_video_compid
            $controlMediaCommand = new ControlMediaCommand();
            $controlMediaCommand->setComponentId('demo_video_compid');
            $controlMediaCommand->setCommand('play');
            $executeCommands = new ExecuteCommands();
            $executeCommands->setCommands($controlMediaCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '视频继续播放'
            ];
        });
    
        //收藏
        $this->addHandler('#favourite_video', function() {
            $this->waitAnswer();
            $this->setExpectSpeech(false);
            $setStateCommand = new SetStateCommand();
            $setStateCommand->setComponentId("demo_image_compid");
            $setStateCommand->setState("src");
            $setStateCommand->setValue("https://dbp-dict.bj.bcebos.com/dpl%2F%E5%BF%83.png");
            $animationCommand = new AnimationCommand();
            $animationCommand->setComponentId("demo_image_compid");
            $animationCommand->setFrom("40dp");
            $animationCommand->setTo("10dp");
            $animationCommand->setEasing("ease-in");
            $animationCommand->setAttribute("height");
            $animationCommand->setDuration(500);
            $animationCommand->setRepeatCount('9');
            $animationCommand->setRepeatMode('reverse');
            $executeCommands = new ExecuteCommands();
            $executeCommands->setCommands([$setStateCommand, $animationCommand]);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '视频收藏'
            ];
        
        });
        
    }
    
    /**
     * 生成DPL.RenderDocument指令
     *
     * @param {string} $pathUrl 文档路径
     * @return RenderDocument
     */
    public function genDPLDirective($pathUrl) {
        $document = new Document();
        $renderDocument = new RenderDocument();
        $doc = $document->getDocumentFromPath($pathUrl);
        $document->initDocument($doc);
        $renderDocument->setDocument($document);
        return $renderDocument;
    }
    
    public function handleUserEvent($event) {
        $this->waitAnswer();
        $this->setExpectSpeech(false);
        $componentId = $event['payload']['componentId'];
        $executeCommands = new ExecuteCommands();
        if ($event['payload']['source']['type'] === 'Image' && strpos($componentId, 'video_list') !== false) {
            $index = substr($componentId, strlen($componentId) -1 , 1);
            $index = intval($index);
            $setStateCommand = new SetStateCommand();
            $setStateCommand->setComponentId("test_video_videoId1");
            $setStateCommand->setState('src');
            $setStateCommand->setValue(self::$VideoList[$index]);
            
            $updateComponentCommand = new UpdateComponentCommand();
            $doc = new Document();
            $content = $doc->getDocumentFromPath('./doc/update.json');
            $content['mainTemplate']['items'][0]['src'] = self::$VideoList[$index - 1]['src'];
            $content['mainTemplate']['items'][1]['items'][0]['text'] = self::$VideoList[$index - 1]['name'];
            $doc->initDocument($content);
            $updateComponentCommand->setDocument($doc);
            $updateComponentCommand->setComponentId("demo_video_compid");
            $executeCommands->setCommands($setStateCommand);
            $executeCommands->setCommands($updateComponentCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '播放新视频'
            ];
        }
        else if ($event['payload']['source']['type'] === 'Pager' && $componentId === 'demo_move_page_compid') {
            $controlMediaCommand = new ControlMediaCommand();
            $controlMediaCommand->setComponentId('demo_video_compid');
            if ($event['payload']['source']['value'] === '0' || $event['payload']['source']['value'] === '2') {
                $controlMediaCommand->setCommand('pause');
            }
            else if ($event['payload']['source']['value'] === '1') {
                $controlMediaCommand->setCommand('play');
            }
            $executeCommands->setCommands($controlMediaCommand);
            return [
                'directives' => [$executeCommands],
                'outputSpeech' => '切换视频状态'
            ];
        }
    }
}
        
    

    
