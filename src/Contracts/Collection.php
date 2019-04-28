<?php

namespace Viloveul\Log\Contracts;

use Psr\Log\LoggerInterface;

interface Collection
{
    /**
     * @param LoggerInterface $log
     * @param array           $types
     */
    public function add(LoggerInterface $log, array $types): void;

    public function all(): array;
}
