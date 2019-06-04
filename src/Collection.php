<?php

namespace Viloveul\Log;

use Psr\Log\LoggerInterface;
use Viloveul\Log\Contracts\Collection as ICollection;

class Collection implements ICollection
{
    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @param LoggerInterface $log
     * @param string          $criteria
     */
    public function add(LoggerInterface $log, string $criteria = '*'): void
    {
        $this->collections[] = compact('log', 'criteria');
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->collections;
    }
}
