<?php

namespace Kreait\Firebase\Http;

use GuzzleHttp\Client as GuzzleHttpClient;

class Client extends GuzzleHttpClient
{
    public function __construct(array $config)
    {
        $defaults = [
            'exceptions' => true,
            'headers' => [
                'User-Agent' => 'firebase-php/1.0 '.\GuzzleHttp\default_user_agent()
            ]
        ];

        parent::__construct($defaults + $config);
    }
}
