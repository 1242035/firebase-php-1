<?php

namespace Kreait\Firebase\Storage;

use GuzzleHttp\HandlerStack;
use Kreait\Firebase\Filesystem\GoogleStorageAdapter;
use Kreait\Firebase\Google;
use Kreait\Firebase\Http\Auth;
use Kreait\Firebase\Http\Middleware;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin;

class Factory
{
    private $bucketName;
    private $googleClientFactory;

    public function __construct(Google\ServiceAccount $serviceAccount, Google\ClientFactory $googleClientFactory = null)
    {
        $this->googleClientFactory = $googleClientFactory ?? new Google\ClientFactory($serviceAccount);
        $this->bucketName = sprintf('%s.appspot.com', $serviceAccount->getProjectId());
    }

    public function createGoogleStorageFilesystem(Auth $customAuth = null): Filesystem
    {
        $client = $this->googleClientFactory->createStorageClient();

        if ($customAuth) {
            $httpClientConfig = $client->getHttpClient()->getConfig();
            /** @var HandlerStack $stack */
            $stack = $httpClientConfig['handler'];
            $stack->push(Middleware::auth($customAuth), 'custom_auth');
        }

        $service = new \Google_Service_Storage($client);

        $adapter = new GoogleStorageAdapter($service, $this->bucketName);

        $filesystem = new Filesystem($adapter);
        $filesystem->addPlugin(new Plugin\GetWithMetadata());
        $filesystem->addPlugin(new Plugin\ListFiles());
        $filesystem->addPlugin(new Plugin\ListPaths());
        $filesystem->addPlugin(new Plugin\ListWith());

        return $filesystem;
    }
}
