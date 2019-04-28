<?php

namespace Viloveul\Log;

use Psr\Log\LoggerTrait;
use Viloveul\Log\Contracts\Logger as ILogger;
use Viloveul\Log\Contracts\Collection as ICollection;

class Logger implements ILogger
{
    use LoggerTrait;

    /**
     * @var mixed
     */
    protected $collection;

    /**
     * @return mixed
     */
    public function getCollection(): ICollection
    {
        return $this->collection;
    }

    /**
     * @param $level
     * @param $message
     * @param array      $context
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->getCollection()->all() as $log) {
            if (in_array($level, $log['levels'])) {
                $log['object']->log($level, $message, $context);
            }
        }
    }

    /**
     * @param ICollection $collection
     */
    public function setCollection(ICollection $collection): void
    {
        $this->collection = $collection;
    }
}
