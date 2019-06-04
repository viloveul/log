<?php

namespace Viloveul\Log;

use Throwable;
use Psr\Log\LogLevel;
use RuntimeException;
use Psr\Log\LoggerTrait;
use Viloveul\Log\Contracts\Logger as ILogger;
use Viloveul\Log\Contracts\Collection as ICollection;

class Logger implements ILogger
{
    use LoggerTrait;

    /**
     * @var mixed
     */
    protected $async = true;

    /**
     * @var mixed
     */
    protected $collection;

    /**
     * @var array
     */
    protected $queue = [];

    /**
     * @param bool $async
     */
    public function __construct(bool $async = true)
    {
        $this->async = $async;
    }

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
        $this->handleException(new RuntimeException($str, $no));
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
            'criteria' => get_class($e),
        ]);
    }

    /**
     * @param $level
     * @param $message
     * @param array      $context
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->getCollection()->all() as $provider) {
            if ($this->isAcceptedCriteria($context, $provider['criteria'])) {
                $q = function () use ($provider, $level, $message, $context) {
                    $provider['log']->log($level, $message, $context);
                };
                if ($this->async === true) {
                    $this->queue[] = $q;
                } else {
                    $q();
                }
            }
        }
    }

    public function process(): void
    {
        while ($q = array_shift($this->queue)) {
            if (is_callable($q)) {
                $q();
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

    /**
     * @param  array   $context
     * @param  string  $criteria
     * @return mixed
     */
    protected function isAcceptedCriteria(array $context, string $criteria): bool
    {
        if ($criteria === '*' || !array_key_exists('criteria', $context)) {
            return true;
        }
        $result = false;
        $search = preg_replace('/[^a-z0-9]+/', '.', strtolower($context['criteria']));
        $criteriaArrayTemp = explode(',', $criteria);
        $criteriaArray = array_map('trim', $criteriaArrayTemp);
        foreach ($criteriaArray as $c) {
            $cArr = explode('.', $c);
            $cLast = array_pop($cArr);
            if ($cLast !== '*') {
                array_unshift($cArr, $cLast);
                $cFinal = implode('.', $cArr);
                if ($cFinal === $search) {
                    $result = true;
                    break;
                }
            } else {
                $cFinal = implode('.', $cArr);
                if (stripos($search, $cFinal) === 0) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }
}
