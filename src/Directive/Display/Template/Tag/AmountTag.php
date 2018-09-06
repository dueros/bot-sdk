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
 * @desc tag类
 **/
namespace Baidu\Duer\Botsdk\Directive\Display\Template\Tag;

class AmountTag extends BaseTag{

    const AMOUNT_TYPE = 'AMOUNT';

    /**
     * BaseTemplate constructor.
     * @param string $amount
     */
    public function __construct($amount) {
        parent::__construct(self::AMOUNT_TYPE, $amount);
    }

}
 
