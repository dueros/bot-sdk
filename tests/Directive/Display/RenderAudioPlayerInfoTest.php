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
 * @desc RenderAudioPlayerInfoTest类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use Baidu\Duer\Botsdk\Directive\AudioPlayer\AudioPlayerInfoContent;
use Baidu\Duer\Botsdk\Directive\Display\RenderAudioPlayerInfo;
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

class RenderAudioPlayerInfoTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->renderAudioPlayerInfo = new RenderAudioPlayerInfo();
    }	

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $fileContent = file_get_contents('json/render_audio_player_info.json');
        $data = json_decode($fileContent, true);

        $content = new AudioPlayerInfoContent();
        $content->setTitle('title');
        $content->setTitleSubtext1('titleSubtext1');
        $content->setTitleSubtext2('titleSubtext2');
        $content->setLyric('www.lyric.com');
        $content->setMediaLengthInMs(10000);
        $content->setArt('www.art.com');
        $content->setProvider('provider', 'www.logo.com');

        $playPauseButton = new PlayPauseButton();
        $repeatButton = new RepeatButton();
        $controls = array(
            $playPauseButton,
            $repeatButton
        );

        $this->renderAudioPlayerInfo->setToken('test_token');
        $this->renderAudioPlayerInfo->setContent($content);
        $this->renderAudioPlayerInfo->setControls($controls);

        $this->assertEquals($this->renderAudioPlayerInfo->getData(), $data);
    }

}
