<?php
return [
    'nlu' => [
        'domain' => 'rent_car',
        'intent' => 'rent_car.book',
        'slots' => [
            [
                'name' => 'start_point',
                'value' => '百度大厦',
            ],
            [
                'name' => 'end_point',
                'value' => '西二旗地铁站',
            ],
            [
                'name' => 'car_type',
                'value' => '出租车',
            ],
            [
                'name' => 'confirm_intent',
                'value' => '1',
            ],
        ]
    ],
    'confirm' => 1,
    'session' => [],
];
