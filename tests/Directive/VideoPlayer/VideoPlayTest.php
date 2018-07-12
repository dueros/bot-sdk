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
 * @desc Play类的测试类
 */
require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use Baidu\Duer\Botsdk\Directive\VideoPlayer\Play;

class VideoPlayTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->play = new Play('www.baidu.com');
    }	

    /**
     * @desc 测试setToken方法
     */
    function testSetToken(){
        $this->play->setToken('token');
        $this->assertEquals($this->play->getToken(), 'token');
    }

    /**
     * @desc 测试getToken方法
     */
    function testGetToken(){
        $this->play->setToken('token');
        $this->assertEquals($this->play->getToken(), 'token');
    }

    /**
     * @desc 测试setUrl方法
     */
    function testSetUrl(){
        $this->play->setUrl('www.test.com');
        $url = $this->play->getData()['videoItem']['stream']['url'];
        $this->assertEquals($url, 'www.test.com');
    }

    /**
     * @desc 测试setOffsetInMilliSeconds方法
     */
    function testSetOffsetInMilliSeconds(){
        $this->play->setOffsetInMilliSeconds(1000);
        $offset = $this->play->getData()['videoItem']['stream']['offsetInMilliseconds'];
        $this->assertEquals($offset, 1000);
    }

    /**
     * @desc 测试SetExpiryTime方法
     */
    public function testSetExpiryTime(){
        $this->play->setExpiryTime('20180303T173008+08');
        $expiryTime = $this->play->getData()['videoItem']['stream']['expiryTime'];
        $this->assertEquals($expiryTime, '20180303T173008+08');
    }

    /**
     * @desc 测试setReportDelayInMs方法
     */
    public function testSetReportDelayInMs(){
        $this->play->setReportDelayInMs(2000);
        $reportDelayMs = $this->play->getData()['videoItem']['stream']['progressReport']['progressReportDelayInMilliseconds'];
        $this->assertEquals($reportDelayMs, 2000);
    }

    /**
     * @desc 测试setReportIntervalInMs方法
     */
    public function testSetReportIntervalInMs(){
        $this->play->setReportIntervalInMs(2000);
        $intervalMs = $this->play->getData()['videoItem']['stream']['progressReport']['progressReportIntervalInMilliseconds'];
        $this->assertEquals($intervalMs, 2000);
    }

    /**
     * @desc 测试setExpectedPreviousToken方法
     */
    public function testSetExpectedPreviousToken(){
        $this->play->setExpectedPreviousToken('token');
        $previousToken = $this->play->getData()['videoItem']['stream']['expectedPreviousToken'];
        $this->assertEquals($previousToken, 'token');
    }

    /**
     * @desc 测试setStopPointsInMilliseconds方法
     */
    public function testSetStopPointsInMilliseconds(){
        $this->play->addStopPointsInMilliseconds(4000);
        $stopPoints = [1000, 2000];
        $this->play->setStopPointsInMilliseconds($stopPoints);
        $currentStopPoints = $this->play->getData()['videoItem']['stream']['stopPointsInMilliseconds'];
        $this->assertEquals($currentStopPoints, $stopPoints);
    }

    /**
     * @desc 测试addStopPointsInMilliseconds方法
     */
    public function testAddStopPointsInMilliseconds(){
        $play = new Play('www.baidu.com');
        $stopPoints = [1000, 2000];
        $res = [1000, 2000, 3000];
        $play->addStopPointsInMilliseconds($stopPoints);
        $play->addStopPointsInMilliseconds(3000);
        $currentStopPoints = $play->getData()['videoItem']['stream']['stopPointsInMilliseconds'];
        $this->assertEquals($currentStopPoints, $res);
    }
}
