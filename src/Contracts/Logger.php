<?php

namespace Viloveul\Log\Contracts;

use Psr\Log\LoggerInterface;
use Viloveul\Log\Contracts\Collection;

interface Logger extends LoggerInterface
{
    public function getCollection(): Collection;

    public function setCollection(Collection $collection): void;
}
