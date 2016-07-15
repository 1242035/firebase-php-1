<?php

namespace Kreait\Tests\Firebase\Google;

use Kreait\Firebase\Google\ServiceAccount;

/**
 * @coversDefaultClass \Kreait\Firebase\Google\ServiceAccount
 */
class ServiceAccountTest extends \PHPUnit_Framework_TestCase
{
    private static $fixtures = __DIR__ . '/_fixtures/ServiceAccount';

    /**
     * @var ServiceAccount
     */
    private $serviceAccount;

    protected function setUp()
    {
        $this->serviceAccount = ServiceAccount::createWith(self::$fixtures.'/valid.json');
    }

    /**
     * @covers ::getProjectId
     * @covers ::getClientId
     * @covers ::getClientEmail
     * @covers ::getPrivateKey
     */
    public function testGetProperties()
    {
        $this->assertNotNull($this->serviceAccount->getProjectId());
        $this->assertNotNull($this->serviceAccount->getClientId());
        $this->assertNotNull($this->serviceAccount->getClientEmail());
        $this->assertNotNull($this->serviceAccount->getPrivateKey());
    }

    /**
     * @param string $value
     *
     * @covers ::createWith
     * @covers ::fromArray
     * @covers ::fromJsonFile
     *
     * @dataProvider validValues
     */
    public function testCreate($value)
    {
        $this->assertInstanceOf(ServiceAccount::class, ServiceAccount::createWith($value));
    }

    /**
     * @param string $value
     *
     * @covers ::createWith
     * @covers ::fromArray
     * @covers ::fromJsonFile
     *
     * @dataProvider invalidValues
     */
    public function testCreateWithInvalidValue($value)
    {
        $this->expectException(\InvalidArgumentException::class);

        ServiceAccount::createWith($value);
    }

    public function validValues()
    {
        $filePath = self::$fixtures.'/valid.json';
        $json = file_get_contents($filePath);

        return [
            'json_file' => [$filePath],
            'array' => [json_decode($json, true)],
            'service_account' => [$this->createMock(ServiceAccount::class)],
        ];
    }

    public function invalidValues()
    {
        return [
            'missing_key' => [self::$fixtures.'/missing_key.json'],
            'invalid_json' => [self::$fixtures.'/invalid_json.json'],
            'non_existing' => [self::$fixtures.'/non_existing.json'],
            'invalid_bool' => [true],
            'invalid_int' => [666],
        ];
    }
}
