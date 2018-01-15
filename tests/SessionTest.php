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
 * @desc Session类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class SessionTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/json/intent_request.json'), true);
        $this->session = new Baidu\Duer\Botsdk\Session($data['session']);
    }	

    /**
     * @desc 测试setData方法
     */
    function testSetData(){
        $this->session->setData('status', '1');
        $response = [
            'attributes' => [
                'status' => '1'
            ]
        ];
        $this->assertEquals($this->session->toResponse(), $response);
    }

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $this->session->setData('status', '1');
        $this->assertEquals($this->session->getData('status'), 1);
    }

}
