<?php

namespace Viloveul\Log\Contracts;

use Throwable;
use Psr\Log\LoggerInterface;
use Viloveul\Log\Contracts\Collection;

interface Logger extends LoggerInterface
{
    public function getCollection(): Collection;

    public function handleError($no, $str, $file, $line): void;

    public function handleException(Throwable $e): void;

    public function process(): void;

    public function setCollection(Collection $collection): void;
}
