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
 * @desc 用于生成Charge指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\Pay;

class Charge extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    const CODE_CNY = 'CNY';

    /**
     * @param string $amount
     * @param string $sellerOrderId
     * @param string $productName
     * @param string $description
     *
     * @return null
     **/
    public function __construct($amount, $sellerOrderId, $productName, $description) {
        parent::__construct('Connections.SendRequest');
        $this->data['name'] = 'Charge';
        $this->data['token'] = $this->genToken();
        $this->setAmount($amount);
        $this->setSellerOrderId($sellerOrderId);
        $this->setProductName($productName);
        $this->setDescription($description);
    }

    /**
     * 设置token
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token 视频的token
     * @return null
     **/
    public function setToken($token){
        if($token) {
            $this->data['token'] = $token;
        }
    }

    /**
     * 获取token
     * @desc 获取directive的token. 默认在构造时自动生成了token
     * @param null
     * @return string
     **/
    public function getToken(){
        return $this->data['videoItem']['stream']['token'];
    }

    /**
     * @param string $amount
     * @return null
     **/
    public function setAmount($amount, $currencyCode = self::CODE_CNY){
        if(is_numeric($amount) && $amount && is_string($currencyCode) && $currencyCode) {
            $this->data['payload']['chargeBaiduPay']['authorizeAttributes']['authorizationAmount']['amount'] = strval($amount);
            $this->data['payload']['chargeBaiduPay']['authorizeAttributes']['authorizationAmount']['currencyCode'] = $currencyCode;
        }
    }

    /**
     * @param string $sellerAuthorizationNote
     * @return null
     **/
    public function setSellerAuthorizationNote($sellerAuthorizationNote){
        if(is_string($sellerAuthorizationNote) && $sellerAuthorizationNote) {
            $this->data['payload']['chargeBaiduPay']['authorizeAttributes']['sellerAuthorizationNote'] = $sellerAuthorizationNote;
        }
    }



    /**
     * @param string $sellerOrderId
     * @return null
     **/
    public function setSellerOrderId($sellerOrderId){
        if(is_string($sellerOrderId) && $sellerOrderId) {
            $this->data['payload']['chargeBaiduPay']['sellerOrderAttributes']['sellerOrderId'] = $sellerOrderId;
        }
    }

    /**
     * @param string $productName
     * @return null
     **/
    public function setProductName($productName){
        if(is_string($productName) && $productName) {
            $this->data['payload']['chargeBaiduPay']['sellerOrderAttributes']['productName'] = $productName;
        }
    }

    /**
     * @param string $sellerOrderId
     * @return null
     **/
    public function setDescription($description){
        if(is_string($description) && $description) {
            $this->data['payload']['chargeBaiduPay']['sellerOrderAttributes']['description'] = $description;
        }
    }

    /**
     * @param string $sellerNode
     * @return null
     **/
    public function setSellerNode($sellerNote){
        if(is_string($sellerNote) && $sellerNote) {
            $this->data['payload']['chargeBaiduPay']['sellerOrderAttributes']['sellerNote'] = $sellerNote;
        }
    }


}
