<?php
return [
    'nlu' => [
        'domain' => 'personal_income_tax',
        'intent' => 'personal_income_tax.inquiry',
        'slots' => [
            [
                'name' => 'location',
                'value' => '北京',
            ],
            [
                'name' => 'compute_type',
                'value' => '个税',
            ],
        ]
    ],
    'session' => [],
];
