<?php

return [
    'name' => 'WareHouse',
    'shipments' => [
        'bosta' => [
            'api_uri' => env('BOSTA_URI', 'https://api.bosta.co/api/v0/'),
            'api_key' => env('BOSTA_AUTH', ''),
            'delivery_type' => env('BOST_DELIVERY_TYPE', 15),
            'pickup_address' => [
                'address' => env('BOSTA_PICKUP_ADDRESS', ''),
                'floor' => env('BOSTA_PICKUP_FLOOR', 1),
                'apartment' => env('BOSTA_PICKUP_APARTMENT', 1),
                'zone' => env('BOSTA_PICKUP_ZONE', ''),
                'district' => env('BOSTA_PICKUP_DISTRICT', ''),
            ],
            'return_address' => [
                'address' => env('BOSTA_RETURN_ADDRESS', env('BOSTA_PICKUP_ADDRESS', '')),
                'floor' => env('BOSTA_RETURN_FLOOR', env('BOSTA_PICKUP_FLOOR', 1)),
                'apartment' => env('BOSTA_RETURN_APARTMENT', env('BOSTA_PICKUP_APARTMENT', 1)),
                'zone' => env('BOSTA_RETURN_ZONE', env('BOSTA_PICKUP_ZONE', '')),
                'district' => env('BOSTA_RETURN_DISTRICT', env('BOSTA_PICKUP_DISTRICT', '')),
            ],
            'webhook_url' => env('BOSTA_WEBHOOK_URL', ''),
        ],
        'aramex' => [
            'shipment_path' => env('ARAMEX_SHIPMENT_PATH', storage_path('aramex/shipping-services-api-wsdl.wsdl')),
            'tracking_path' => env('ARAMEX_TRACKING_PATH', storage_path('aramex/shipments-tracking-api-wsdl.wsdl')),
            'account_currency_code' => env('ARAMEX_ACCOUNT_CURRENCY_CODE', 'EGP'),
            'account_country_code' => env('ARAMEX_ACCOUNT_COUNTRY_CODE', 'EG'),
            'account_number' => env('ARAMEX_ACCOUNT_NUM', ''),
            'account_entity' => env('ARAMEX_ACCOUNT_ENTITY', ''),
            'account_pin' => env('ARAMEX_ACCOUNT_PIN', ''),
            'account_username' => env('ARAMEX_ACCOUNT_USERNAME', ''),
            'account_password' => env('ARAMEX_ACCOUNT_PASSWORD', ''),
            'version' => env('ARAMEX_VERSION', ''),
            'pickup_address' => [
                'address' => env('ARAMEX_PICKUP_ADDRESS', ''),
                'floor' => env('ARAMEX_PICKUP_FLOOR', 1),
                'apartment' => env('ARAMEX_PICKUP_APARTMENT', 1),
                'zone' => env('ARAMEX_PICKUP_ZONE', ''),
                'district' => env('ARAMEX_PICKUP_DISTRICT', ''),
            ],
            'contact' => [
                'department' => env('ARAMEX_DEPARETMENT_TEAM', ''),
                'person_name' => env('ARAMEX_PERSON_NAME', ''),
                'title' => env('ARAMEX_TITLE', ''),
                'company_name' => env('ARAMEX_COMPANY_NAME', ''),
                'phone' => env('ARAMEX_PHONE_NUMBER', ''),
                'cell_phone' => env('ARAMEX_CELL_PHONE', env('ARAMEX_PHONE_NUMBER', '')),
                'email' => env('ARAMEX_EMAIL', ''),
            ],
            'package_type' => env('ARAMEX_PACKAGE_TYPE', 'Clothes'),
        ],
    ],
    'payments' => [
        'we_accept' => [
            'base_url' => env('WE_ACCEPT_BASE_URL'),
            'api_key' => env('WE_ACCEPT_API_KEY', ''),
            'user_name' => env('WE_ACCEPT_USERNAME', ''),
            'password' => env('WE_ACCEPT_PASSWORD', ''),
            'iframe_id' => env('WE_ACCEPT_IFRAME'),
            'integration_card_id' => env('WE_ACCEPT_INTEGRATION'),
            'secret_key' => env('WE_ACCEPT_SECRETE_KEY'),
        ]
    ]
];
