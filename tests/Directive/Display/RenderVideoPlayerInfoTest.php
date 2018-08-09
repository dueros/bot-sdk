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
 * @desc RenderVideoPlayerInfoTest类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use Baidu\Duer\Botsdk\Directive\VideoPlayer\VideoPlayerInfoContent;
use Baidu\Duer\Botsdk\Directive\Display\RenderVideoPlayerInfo;
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

class RenderVideoPlayerInfoTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->renderVideoPlayerInfo = new RenderVideoPlayerInfo();
    }	

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $fileContent = file_get_contents('json/render_video_player_info.json');
        $data = json_decode($fileContent, true);
        $content = new VideoPlayerInfoContent('title');
        $content->setMediaLengthInMilliseconds(10000);

        $playPauseButton = new PlayPauseButton();
        $playlistButton = new ShowPlayListButton();
        $controls = array(
            $playPauseButton,
            $playlistButton
        );

        $this->renderVideoPlayerInfo->setToken('test_token');
        $this->renderVideoPlayerInfo->setContent($content);
        $this->renderVideoPlayerInfo->setControls($controls);

        $this->assertEquals($this->renderVideoPlayerInfo->getData(), $data);
    }

}
