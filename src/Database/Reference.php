<?php

namespace Kreait\Firebase\Database;

use Kreait\Firebase\Database;

class Reference
{
    private $location;
    private $database;
    private $value;

    public function __construct(string $location, Database $database)
    {
        $this->location = $location;
        $this->database = $database;
    }

    public function setValue($value): self
    {
        $this->value = $this->database->set($this->location, $value);

        return $this;
    }

    public function query(): Query
    {
        return new Query($this);
    }
}
