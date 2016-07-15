<?php

namespace Kreait\Firebase\Google;

use Kreait\Firebase\Exception\InvalidArgument;

class ServiceAccount
{
    private $projectId;
    private $clientId;
    private $clientEmail;
    private $privateKey;

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientEmail(): string
    {
        return $this->clientEmail;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @param $value
     *
     * @throws \Kreait\Firebase\Exception\InvalidArgument
     *
     * @return ServiceAccount
     */
    public static function createWith($value): ServiceAccount
    {
        if ($value instanceof ServiceAccount) {
            return $value;
        }

        if (is_string($value)) {
            return self::fromJsonFile($value);
        }

        if (is_array($value)) {
            return self::fromArray($value);
        }

        throw new InvalidArgument('Invalid value.');
    }

    private static function fromArray(array $config): ServiceAccount
    {
        if (!isset($config['project_id'], $config['client_id'], $config['client_email'], $config['private_key'])) {
            throw new \InvalidArgumentException('Missing/empty values in Service Account Config.');
        }

        $account = new self();
        $account->projectId = $config['project_id'];
        $account->clientId = $config['client_id'];
        $account->clientEmail = $config['client_email'];
        $account->privateKey = $config['private_key'];

        return $account;
    }

    private static function fromJsonFile(string $filePath): ServiceAccount
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist or is not readable.', $filePath));
        }

        $config = json_decode(file_get_contents($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(
                sprintf('Error while decoding JSON config: "%s".', json_last_error_msg())
            );
        }

        return self::fromArray($config);
    }
}
