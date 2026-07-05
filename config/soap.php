<?php

return [
    'host' => env('SOAP_HOST', '127.0.0.1'),
    'port' => env('SOAP_PORT', 7878),
    'user' => env('SOAP_USER', ''),
    'password' => env('SOAP_PASSWORD', ''),
    'uri' => env('SOAP_URI', 'urn:AC'),
    'timeout' => env('SOAP_TIMEOUT', 5),
];
