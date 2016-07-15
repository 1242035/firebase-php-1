<?php

namespace Kreait\Firebase\Http\Auth;

use GuzzleHttp\Psr7;
use Kreait\Firebase\Http\Auth;
use Psr\Http\Message\RequestInterface;

final class CustomToken implements Auth
{
    /**
     * @var string
     */
    private $token;

    public function __construct(string $uid, array $claims = [])
    {
        $authOverride = ['uid' => $uid];

        if (count($claims)) {
            $authOverride['token'] = $claims;
        }

        $this->token = json_encode($authOverride);
    }

    public function authenticateRequest(RequestInterface $request): RequestInterface
    {
        $uri = $request->getUri();

        $queryParams = ['auth_variable_override' => $this->token] + Psr7\parse_query($uri->getQuery());
        $queryString = Psr7\build_query($queryParams);

        $newUri = $uri->withQuery($queryString);

        return $request->withUri($newUri);
    }
}
