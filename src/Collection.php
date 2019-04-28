<?php

namespace Viloveul\Log;

use Psr\Log\LogLevel;
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
     * @param array           $types
     */
    public function add(LoggerInterface $log, array $types = []): void
    {
        $defs = [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG,
        ];
        $this->collections[] = [
            'object' => $log,
            'levels' => empty($types) ? $defs : $types,
        ];
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->collections;
    }
}
