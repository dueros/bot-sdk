<?php
return [
    'intent' => [
        'name' => 'sample_personal_income_tax.inquiry',
        'slots' => [
            [
                'name' => 'monthlysalary',
                'value' => '121212',
            ],
            [
                'name' => 'compute_type',
                'value' => '个税',
            ],
            [
                'name' => 'location',
                'value' => '北京',
            ],
        ]
    ],
    'session' => [],
];
