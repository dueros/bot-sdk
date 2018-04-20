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
use Baidu\Duer\Botsdk\Directive\AudioPlayer\PlayerInfo;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\PlayPauseButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\FavoriteButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\LyricButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\NextButoon;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\PreviousButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\RecommendButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\RefreshButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\ShowFavoriteListButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\ShowPlayListButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\RepeatButton;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\Control\ThumbsUpDownButton;

class PlayerInfoTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->playerInfo = new PlayerInfo();
    }	

    /**
     * @desc 测试setAudioItemType方法
     */
    function testSetAudioItemType(){
        $this->playerInfo->setAudioItemType('MUSIC');
        $this->assertEquals($this->playerInfo->getData()['content']['audioItemType'], 'MUSIC');
    }

     /**
     * @desc 测试setTitle方法
     */
    function testSetTitle(){
        $this->playerInfo->setTitle('title');
        $this->assertEquals($this->playerInfo->getData()['content']['title'], 'title');
    }

    /**
     * @desc 测试setTitleSubtext1方法
     */
    function testSetTitleSubtext1(){
        $this->playerInfo->setTitleSubtext1('titleSubtext1');
        $this->assertEquals($this->playerInfo->getData()['content']['titleSubtext1'], 'titleSubtext1');
    }

    /**
     * @desc 测试setTitleSubtext2方法
     */
    function testSetTitleSubtext2(){
        $this->playerInfo->setTitleSubtext2('titleSubtext2');
        $this->assertEquals($this->playerInfo->getData()['content']['titleSubtext2'], 'titleSubtext2');
    }

    /**
     * @desc 测试setLyric方法
     */
    function testSetLyric(){
        $this->playerInfo->setLyric('url');
        $this->assertEquals($this->playerInfo->getData()['content']['lyric']['url'], 'url');
    }

    /**
     * @desc 测试setMediaLengthInMs方法
     */
    function testSetMediaLengthInMs(){
        $this->playerInfo->setMediaLengthInMs(2000);
        $this->assertEquals($this->playerInfo->getData()['content']['mediaLengthInMilliseconds'], 2000);
    }

    /**
     * @desc 测试setArt方法
     */
    function testSetArt(){
        $this->playerInfo->setArt('src');
        $this->assertEquals($this->playerInfo->getData()['content']['art']['src'], 'src');
    }

    /**
     * @desc 测试setProvider方法
     */
    function testSetProvider(){
        $this->playerInfo->setProvider('name', 'logo');
        $this->assertEquals($this->playerInfo->getData()['content']['provider']['name'], 'name');
        $this->assertEquals($this->playerInfo->getData()['content']['provider']['logo']['src'], 'logo');
    }

    /**
     * @desc 测试setControls方法
     */
    function testSetControls(){
        $playPauseButton = new PlayPauseButton();
        $favoriteButton = new FavoriteButton();
        $lyricButton = new LyricButton();
        $nextButoon = new NextButoon();
        $previousButton = new PreviousButton();
        $recommendButton = new RecommendButton();
        $refreshButton = new RefreshButton();
        $showFavoriteListButton = new ShowFavoriteListButton();
        $showPlayListButton = new ShowPlayListButton();
        $repeatButton = new RepeatButton();
        $thumbsUpDownButton = new ThumbsUpDownButton();
        $controls = array(
            $playPauseButton,
            $showPlayListButton,
            $showFavoriteListButton,
            $refreshButton,
            $favoriteButton,
            $lyricButton,
            $nextButoon,
            $previousButton,
            $recommendButton,
            $repeatButton,
            $thumbsUpDownButton
        );

        $controlsData = array(
            array(
                'type' => 'BUTTON',
                'name' => 'PLAY_PAUSE',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'SHOW_PLAYLIST',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'SHOW_FAVORITE_LIST',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'REFRESH',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'FAVORITE',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'LYRIC',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'NEXT',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'PREVIOUS',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'RECOMMEND',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'RADIO_BUTTON',
                'name' => 'REPEAT',
                'selectedValue' => 'REPEAT_ONE',
            ),
            array(
                'type' => 'RADIO_BUTTON',
                'name' => 'THUMBS_UP_DOWN',
                'selectedValue' => 'THUMBS_UP',
            )
        );
        $this->playerInfo->setControls($controls);
        $this->assertEquals($this->playerInfo->getData()['controls'], $controlsData);
    }

    /**
     * @desc 测试addControl方法
     */
    function testAddControl(){
        $controlsData = array(
            array(
                'type' => 'BUTTON',
                'name' => 'PLAY_PAUSE',
                'enabled' => true,
                'selected' => false
            ),
            array(
                'type' => 'BUTTON',
                'name' => 'SHOW_PLAYLIST',
                'enabled' => true,
                'selected' => false
            )
        );
        $this->playerInfo->addControl(new PlayPauseButton());
        $this->playerInfo->addControl(new ShowPlayListButton());
        $this->assertEquals($this->playerInfo->getData()['controls'], $controlsData);
    }
}
