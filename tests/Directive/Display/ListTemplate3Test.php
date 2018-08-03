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
 * @desc ListTemplate3类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

use Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplate3;
use Baidu\Duer\Botsdk\Directive\Display\Template\ListTemplateItem;
use Baidu\Duer\Botsdk\Directive\Display\Template\Tag\FreeTag;
use Baidu\Duer\Botsdk\Directive\Display\Template\Tag\PayTag;
use Baidu\Duer\Botsdk\Directive\Display\Template\Tag\CustomTag;

class ListTemplate3Test extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
    }	

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $listTemplate = new ListTemplate3();
        $fileContent = file_get_contents('json/list_template3.json');
        $data = json_decode($fileContent, true);

        $listTemplate = new ListTemplate3();
        $listTemplate->setToken('test_token');
        $listTemplate->setBackGroundImage('www. backgroundImage.com');
        $listTemplate->setTitle('title');


        $freeTag = new FreeTag();
        $payTag = new PayTag();

        $tags = array(
            $freeTag, $payTag 
        );

        $listTemplateItem = new ListTemplateItem();
        $listTemplateItem->setToken('token1');
        $listTemplateItem->setImage('www.image1.com', 200, 200);
        $listTemplateItem->setImageTags($tags);
        
        $listTemplateItem->setContent('text1');

        $listTemplate->addItem($listTemplateItem);

        $customTag = new CustomTag('自定义');
        $customTag->setColor('#000000');
        $customTag->setBackgroundColor('#FFFFFF');

        $tags = array(
            $customTag
        );

        $listTemplateItem = new ListTemplateItem();
        $listTemplateItem->setToken('token2');
        $listTemplateItem->setImage('www.image2.com', 200, 200);
        $listTemplateItem->setImageTags($tags);
        
        $listTemplateItem->setContent('text2');

        $listTemplate->addItem($listTemplateItem);
         
            
        $this->assertEquals($listTemplate->getData(), $data);
    }

}
