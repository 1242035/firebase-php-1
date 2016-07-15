<?php

namespace Kreait\Firebase\Http;

use GuzzleHttp\HandlerStack;

class DatabaseClient extends Client
{
    public function enablePrettifiedJsonOutput()
    {
        /** @var HandlerStack $stack */
        $stack = $this->getConfig('handler');
        $stack->push(Middleware::prettifyResponseJson(), 'prettify_response_json');
    }

    public function disablePrettifiedJsonOutput()
    {
        /** @var HandlerStack $stack */
        $stack = $this->getConfig('handler');
        $stack->remove('prettify_response_json');
    }

    public function enableSilentMode()
    {
        /** @var HandlerStack $stack */
        $stack = $this->getConfig('handler');
        $stack->push(Middleware::suppressResponseOutput(), 'suppress_response_output');
    }

    public function disableSilentMode()
    {
        /** @var HandlerStack $stack */
        $stack = $this->getConfig('handler');
        $stack->remove('suppress_response_output');
    }
}
