<?php

namespace Kreait\Firebase\Http;

use Psr\Http\Message\RequestInterface;

interface Auth
{
    public function authenticateRequest(RequestInterface $request): RequestInterface;
}
