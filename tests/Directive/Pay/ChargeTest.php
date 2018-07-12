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
 * @desc Charge类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class ChargeTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->charge = new Baidu\Duer\Botsdk\Directive\Pay\Charge('0.01', 'order_id_123', 'product_name', 'test_charge_description');
    }	

    /**
     * @desc 测试Charge
     */
    function testGetData(){
        $this->charge->setToken('test_token');
        $this->charge->setSellerNode('sellerNote');
        $this->charge->setSellerAuthorizationNote('sellerAuthorizationNote');
        $data = array(
            'type' => 'Connections.SendRequest',
            'name' => 'Charge',
            'token' => 'test_token',
            'payload' => array(
                'chargeBaiduPay' => array(
                    'authorizeAttributes' => array(
                        'authorizationAmount' => array(
                            'amount' => '0.01',
                            'currencyCode' => 'CNY'
                        ),
                        'sellerAuthorizationNote' => 'sellerAuthorizationNote' 
                    ),
                    'sellerOrderAttributes' => array(
                        'sellerOrderId' => 'order_id_123',
                        'productName' => 'product_name',
                        'description' => 'test_charge_description',
                        'sellerNote' => 'sellerNote'
                    )
                ),
            )
        );
        $this->assertEquals($this->charge->getData(), $data);
    }

}
