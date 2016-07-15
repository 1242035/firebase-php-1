<?php

namespace Kreait\Firebase\Filesystem;

use GuzzleHttp\Psr7\Request;

class GoogleStorageAdapter extends \Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter
{
    public function read($path)
    {
        // TODO: can this be optimised to not perform 2 x api calls here?
        $object = $this->getObject($path);

        try {
            $response = $this->service->getClient()->execute(new Request('GET', $object->getMediaLink()));
        } catch (\Exception $e) {
            return false;
        }

        $result = $this->normaliseObject($object);
        $result['contents'] = (string) $response->getBody();

        return $result;
    }
}
