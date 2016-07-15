<?php

namespace Kreait\Firebase\Database;

class Query
{
    private $reference;

    public function __construct(Reference $reference)
    {
        $this->reference = $reference;
    }

    public function execute()
    {
    }
}
