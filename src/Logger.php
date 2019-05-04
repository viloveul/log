<?php

namespace Viloveul\Log;

use Throwable;
use Psr\Log\LogLevel;
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
     * @param $no
     * @param $str
     * @param $file
     * @param $line
     */
    public function handleError($no, $str, $file, $line): void
    {
        $this->log(LogLevel::ALERT, "{message}\n{file}:{line} (code: {code})", [
            'code' => $no,
            'file' => $file,
            'message' => $str,
            'line' => $line,
        ]);
    }

    /**
     * @param Throwable $e
     */
    public function handleException(Throwable $e): void
    {
        $this->log(LogLevel::ERROR, "{message}\n{file}:{line} (code: {code})\n{trace}", [
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
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
