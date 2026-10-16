<?php
return [
    'whatsapp' => env('WHATSAPP_NUMBER', '201000000000'), // رقم بدون + مع كود الدولة
    'currency' => env('STORE_CURRENCY', 'EGP'),
    'shipping' => (float) env('SHIPPING_FEE', 50),
    'free_shipping_over' => (float) env('FREE_SHIPPING_OVER', 1500),
];
