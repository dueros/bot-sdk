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
 * @desc Stop类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use \Baidu\Duer\Botsdk\Directive\Permission\AskForPermissionsConsent;

class AskForPermissionsConsentTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
    }	

    /**
     * @desc 测试addPermission方法
     */
    function testAddPermission(){
        $data = array(
            'type' => 'Permission.AskForPermissionsConsent',
            'permissions' => array(
                array(
                    'name' => 'RECORD::SPEECH'
                ),
                array(
                    'name' => 'READ::DEVICE:LOCATION'
                )
            ),
            'token' => 'test_token'
        );
        $directive = new AskForPermissionsConsent();
        $directive->addPermission('RECORD::SPEECH');
        $directive->addPermission('READ::DEVICE:LOCATION');
        $directive->setToken('test_token');
        $this->assertEquals($directive->getData(), $data);
    }

}
