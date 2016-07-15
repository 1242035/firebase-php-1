<?php

namespace Kreait;

use Kreait\Firebase\Database;
use Kreait\Firebase\Google;
use Kreait\Firebase\Http;
use Kreait\Firebase\Http\Auth;
use Kreait\Firebase\Storage;
use League\Flysystem\Filesystem;

final class Firebase
{
    private $serviceAccount;
    private $httpFactory;
    private $storageFactory;

    private $storage;
    private $database;


    public function __construct(
        Google\ServiceAccount $serviceAccount,
        Http\Factory $httpFactory = null,
        Storage\Factory $storageFactory = null
    ) {
        $this->serviceAccount = $serviceAccount;
        $this->httpFactory = $httpFactory ?? new Http\Factory($serviceAccount);
        $this->storageFactory = $storageFactory ?? new Storage\Factory($serviceAccount);
    }

    public static function create($serviceAccount): self
    {
        $serviceAccount = Google\ServiceAccount::createWith($serviceAccount);
        return new self($serviceAccount);
    }

    public function getStorage(): Filesystem
    {
        if (!$this->storage) {
            $this->storage = $this->storageFactory->createGoogleStorageFilesystem();
        }

        return $this->storage;
    }

    public function getDatabase(): Database
    {
        if (!$this->database) {
            $this->database = new Database($this->httpFactory->createDatabaseClient());
        }

        return $this->database;
    }

    public function withAuthOverride(Auth $auth): self
    {
        $new = new self($this->serviceAccount, $this->httpFactory, $this->storageFactory);

        $new->storage = $this->storageFactory->createGoogleStorageFilesystem($auth);
        $new->database = new Database($this->httpFactory->createDatabaseClient($auth));

        return $new;
    }
}
