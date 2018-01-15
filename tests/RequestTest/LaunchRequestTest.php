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
 * @desc LaunchRequest类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class LaunchRequestTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->data = json_decode(file_get_contents(dirname(__FILE__).'/../json/launch.json'), true);
        $this->request = new Baidu\Duer\Botsdk\Request($this->data);	
    }

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $this->assertEquals($this->request->getData(), $this->data);
    }

    /**
     * @deprecated  sdk更新后测试
     * @测试getUserProfil方法
     */
    function testGetUserProfile(){

    }

    /**
     * @desc 测试
     */
    function testGetSession(){
        $session = new Baidu\Duer\Botsdk\Session($this->data['session']);
        $this->assertEquals($this->request->getSession(), $session);
    }

    /**
     * @deprecated sdk更新后测试
     * @desc 测试getDeviceData方法
     */
    function testGetDeviceData(){

    }

    /**
     * @测试getDeviceId方法
     */
    function testGetDeviceId(){
        $this->assertEquals($this->request->getDeviceId(), 'deviceId');
    }

    /**
     * @desc 测试getUserInfo方法
     */
    function testGetUserInfo(){
        $userInfo = [
            "account" => [
                "baidu" => [
                    "baiduUid" => "baiduUid"
                ]
            ],
            "location" => [
                "geo" => [
                    "bd09ll" => [
                        "longitude" => 12.12,
                        "latitude" => 34.12
                    ],
                    "wgs84" => [
                        "longitude" => 12.12,
                        "latitude" => 34.12
                    ],
                    "bd09mc" => [
                        "longitude" => 111112.12,
                        "latitude" => 322224.12
                    ]	
                ]
            ]
        ];
        $this->assertEquals($this->request->getUserInfo(), $userInfo);
    }

    /**
     * @desc 测试getBaiduUid方法
     */
    function testGetBaiduUid(){
        $this->assertEquals($this->request->getBaiduUid(), 'baiduUid');
    }
    /**
     * @desc 测试getType方法
     */
    function testGetType(){
        $this->assertEquals($this->request->getType(), 'LaunchRequest');
    }

    /**
     * @desc 测试getUserId方法
     */
    function testGetUserId(){
        $this->assertEquals($this->request->getUserId(), 'userId');
    }

    /**
     * @desc 测试getCuid方法
     */
    function testGetCuid(){
        $this->assertEquals($this->request->getCuid(), 'cuid');
    }

    /**
     * @desc 测试getLocation方法
     */
    function testGetLocation(){
        $location = [
            "geo" => [
                "bd09ll" => [
                    "longitude" => 12.12,
                    "latitude" => 34.12
                ],
                "wgs84" => [
                    "longitude" => 12.12,
                    "latitude" => 34.12
                ],
                "bd09mc" => [
                    "longitude" => 111112.12,
                    "latitude" => 322224.12
                ]	
            ]
        ];
        $this->assertEquals($this->request->getLocation(), $location);
    }

    /**
     * @desc 测试isLaunchRequest方法
     */
    function testIsLaunchRequest(){
        $this->assertTrue($this->request->isLaunchRequest());
    }

    /**
     * @desc 测试isSessionEndRequest方法
     */
    function testIsSessionEndRequest(){
        $this->assertFalse($this->request->isSessionEndRequest());
    }

    /**
     * @desc 测试isSessionEndedRequest方法
     */
    function testIsSessionEndedRequest(){
        $this->assertFalse($this->request->isSessionEndedRequest());
    }

    /**
     * @desc 测试getLogId方法
     */
    function testGetLogId(){
        $this->assertEquals($this->request->getLogId(), 'requestId');
    }

    /**
     * @desc 测试getBotId方法
     */
    function testGetBotId(){
        $this->assertEquals($this->request->getBotId(), 'botId');
    }

    /**
     * @desc 测试getOriginalDeviceId方法
     */
    function testGetOriginalDeviceId(){
        $this->assertEquals($this->request->getOriginalDeviceId(), 'originalDeviceId');
    }


    /**
     * @desc 测试getAccessToken方法
     */
    function testGetAccessToken(){
        $this->assertEquals($this->request->getAccessToken(), 'access_token');
    }

    /**
     * @desc 测试getExternalAccessTokens方法
     */
    function testGetExternalAccessTokens(){
        $externalAccessTokens = [[
            "oauthInfoId" => "oauthInfoId",
            "accessToken" => "accessToken"
        ]];
        $this->assertEquals($this->request->getExternalAccessTokens(), $externalAccessTokens);
    }
}
