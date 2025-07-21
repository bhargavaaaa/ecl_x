<?php

return [
    'user_types' => [
        'web_users' => 0,
        'catalogue_users' => 1
    ],
    'order_types' => [
        'stock' => 1,
        'make_to_order' => 2,
        'exhibition' => 3,
        'open' => 4
    ],
    'order_statuses' => [
        'pending' => 1,
        'wip' => 2,
        'rejected' => 3,
        'completed' => 4
    ],
    'gold_rates' => [
        "100.00" => '999',
        "99.50" => '995'
    ],
    'item_groups' => [
        "jewellery" => 1,
        "semi_finish" => 2,
        "metal" => 3,
        "stone" => 4,
        "consumable" => 5,
    ],
    'metal_item_group' => 3,
    'mail_from_email' => env('MAIL_FROM_EMAIL', 'order@gbtechnologies.in'),
    'company_name' => 'Tanvi Silver India',
    'address' => 'Tanvi Silver India, Aji Industries',
    'city_id' => 1011,
    'state_id' => 12,
    'country_id' => 101,
    'gst' => 'ASDFG435578FHG2435'
];
