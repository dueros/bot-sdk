<?php
return [
    'intent' => [
        'name' => 'personal_income_tax.inquiry',
        'slots' => [
            [
                'name' => 'monthlysalary',
                'value' => '121212',
            ],
            [
                'name' => 'compute_type',
                'value' => '个税',
            ],
        ]
    ],
    'session' => [],
];
