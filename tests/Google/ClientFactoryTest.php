<?php

namespace Kreait\Firebase\tests\Google;

use Google_Client;
use Kreait\Firebase\Google\ClientFactory;
use Kreait\Firebase\Google\ServiceAccount;

class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new ClientFactory($this->createMock(ServiceAccount::class));
    }

    public function testCreateFirebaseDatabaseClient()
    {
        $this->assertInstanceOf(Google_Client::class, $this->factory->createFirebaseDatabaseClient());
    }

    public function testCreateStorageClient()
    {
        $this->assertInstanceOf(Google_Client::class, $this->factory->createStorageClient());
    }
}
