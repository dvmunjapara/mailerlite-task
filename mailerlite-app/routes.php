<?php

return [
    'GET /' => ['SubscriberController', 'index'],
    'GET /subscribers' => ['SubscriberController', 'index'],
    'GET /subscriber' => ['SubscriberController', 'show'],
    'POST /subscribe' => ['SubscriberController', 'store'],
];

