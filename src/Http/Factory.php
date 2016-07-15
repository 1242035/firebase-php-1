<?php

namespace Kreait\Firebase\Http;

use GuzzleHttp\HandlerStack;
use Kreait\Firebase\Google;

class Factory
{
    private $databaseUri;
    private $googleClientFactory;

    public function __construct(Google\ServiceAccount $serviceAccount, Google\ClientFactory $googleClientFactory = null)
    {
        $this->databaseUri = sprintf('https://%s.firebaseio.com', $serviceAccount->getProjectId());

        $this->googleClientFactory = $googleClientFactory ?? new Google\ClientFactory($serviceAccount);
    }

    public function createDatabaseClient(Auth $customAuth = null): Client
    {
        $googleClient = $this->googleClientFactory->createFirebaseDatabaseClient();

        $config = $googleClient->authorize()->getConfig();

        /** @var HandlerStack $stack */
        $stack = $config['handler'];
        $stack->push(Middleware::ensureJson(), 'ensure_json');

        if ($customAuth) {
            $stack->push(Middleware::auth($customAuth), 'custom_auth');
        }

        $config['base_uri'] = $this->databaseUri;

        return new Client($config);
    }
}
