<?php

namespace Kreait\Firebase\Exception;

use GuzzleHttp\Exception\RequestException;

class DatabaseException extends \RuntimeException implements FirebaseException
{
    public static function fromGuzzleException(RequestException $e): self
    {
        $message = sprintf('Database request to %s failed (%s)', $e->getRequest()->getUri(), $e->getMessage());

        if ($e->hasResponse()) {
            $data = json_decode((string) $e->getResponse()->getBody(), true);

            if (array_key_exists('error', $data)) {
                $message .= '. Reason: '.$data['error'];
            }
        }

        throw new self($message, $e->getCode(), $e);
    }
}
