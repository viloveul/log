<?php

namespace Viloveul\Log\Contracts;

use Psr\Log\LoggerInterface;

interface Collection
{
    /**
     * @param LoggerInterface $log
     * @param string          $criteria
     */
    public function add(LoggerInterface $log, string $criteria): void;

    public function all(): array;
}
