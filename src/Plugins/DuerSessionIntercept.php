<?php
/**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @desc 度秘多轮优先，异常退出逻辑
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk\Plugins;

use \Baidu\Duer\Botsdk\Card\TextCard;

class DuerSessionIntercept extends \Baidu\Duer\Botsdk\Intercept{

    /**
     * @param string $tip
     * @param integer $threshold
     * @return null
     **/
    public function __construct($tip="非常抱歉，不明白你说的意思，已经取消了本次服务", $threshold=2) {
        $this->tip = $tip;
        $this->threshold = $threshold;
    }

    /**
     * @desc 在处理回调之前，bot-sdk会先调用preprocess，
     *       如果preprocess返回不为null，终止事件路由，将preprocess的输出作为最终输出
     *
     * @param Bot $bot
     * @return mixed
     **/
    public function preprocess($bot){
        if(!$this->threshold) {
            return; 
        }

        //NLU尝试slot提取，异常次数
        $daException = $bot->getSlot('da_system_not_understand');
        //bot 自身slot检查，不合法次数
        $botException = $bot->getSlot('bot_not_understand');
        $count = ($daException?$daException:0) + ($botException?$botException:0);

        if($count >= $this->threshold) {
            $bot->clearSessionAttribute();
            $bot->endDialog();
            $card = new TextCard($this->tip);

            return [ 
                'card' => $card,
            ];
        }
    }
}
 
