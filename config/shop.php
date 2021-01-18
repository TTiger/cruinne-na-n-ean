<?php

return [
    'products' => [
        'default' => 'rest',

        'clients' => [

            'rest' => [
                'api_key' => env('SHOPIFY_API_KEY'),
                'app_password' => env('SHOPIFY_APP_PASSWORD'),
                'app_secret' => env('SHOPIFY_APP_SECRET'),
                'base_uri' => 'https://universe-of-birds.myshopify.com/admin/api/2020-04/',
            ],

        ]
    ]
];
