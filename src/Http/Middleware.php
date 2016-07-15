<?php

namespace Kreait\Firebase\Http;

use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;

final class Middleware
{
    /**
     * Ensures that the ".json" suffix is added to URIs and that the content type is set correctly
     *
     * @return callable
     */
    public static function ensureJson()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options = []) use ($handler) {
                $uri = $request->getUri();
                $path = $uri->getPath();

                if (!preg_match('@\.json$@i', $path)) {
                    $uri = $uri->withPath($path.'.json');
                    $request = $request->withUri($uri);
                }

                $request = $request->withHeader('Content-Type', 'application/json');

                return $handler($request, $options);
            };
        };
    }

    public static function auth(Auth $auth)
    {
        return function (callable $handler) use ($auth) {
            return function (RequestInterface $request, array $options = []) use ($handler, $auth) {
                $request = $auth->authenticateRequest($request);

                return $handler($request, $options);
            };
        };
    }

    /**
     * @see https://firebase.google.com/docs/database/rest/save-data#print
     */
    public static function suppressResponseOutput()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options = []) use ($handler) {
                if (!in_array($request->getMethod(), ['GET', 'POST', 'DELETE'], true)) {
                    $request = self::addQueryParameters($request, ['print' => 'silent']);
                }

                return $handler($request, $options);
            };
        };
    }

    /**
     * @see https://firebase.google.com/docs/database/rest/save-data#print
     */
    public static function prettifyResponseJson()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options = []) use ($handler) {
                $request = self::addQueryParameters($request, ['print' => 'pretty']);

                return $handler($request, $options);
            };
        };
    }



    private static function addQueryParameters(RequestInterface $request, array $params): RequestInterface
    {
        $uri = $request->getUri();

        $params += Psr7\parse_query($uri->getQuery());
        $queryString = Psr7\build_query($params);

        $newUri = $uri->withQuery($queryString);

        return $request->withUri($newUri);
    }
}
