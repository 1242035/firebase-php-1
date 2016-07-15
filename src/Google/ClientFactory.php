<?php

namespace Kreait\Firebase\Google;

use Google_Client;
use Google_Service_Storage;

class ClientFactory
{
    /**
     * @var ServiceAccount
     */
    private $serviceAccount;

    public function __construct(ServiceAccount $serviceAccount)
    {
        $this->serviceAccount = $serviceAccount;
    }

    public function createFirebaseDatabaseClient(): Google_Client
    {
        return $this->createGoogleClient([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/firebase.database'
        ]);
    }

    public function createStorageClient(): Google_Client
    {
        return $this->createGoogleClient([
            Google_Service_Storage::DEVSTORAGE_FULL_CONTROL,
        ]);
    }

    private function createGoogleClient(array $scopes = []): Google_Client
    {
        $client = new Google_Client();

        $client->setAuthConfig([
            'type' => 'service_account',
            'client_id' => $this->serviceAccount->getClientId(),
            'client_email' => $this->serviceAccount->getClientEmail(),
            'private_key' => $this->serviceAccount->getPrivateKey()
        ]);

        $client->setScopes($scopes);

        return $client;
    }
}
