<?php

namespace Darcher\PineconePhp\Response;

use stdClass;

class Database
{
    public $name;
    public $dimension;
    public $metric;
    public $pods;
    public $replicas;
    public $shards;
    public $pod_type;
    public IndexConfig $index_config;
    public stdClass $metadata_config;
    public Status $status;
}