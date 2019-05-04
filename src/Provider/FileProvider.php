<?php

namespace Viloveul\Log\Provider;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;
use Viloveul\Log\FormatMessageTrait;

class FileProvider implements LoggerInterface
{
    use LoggerTrait;
    use FormatMessageTrait;

    /**
     * @var mixed
     */
    private $target;

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $dir = rtrim($directory, '/') . '/logs';
        is_dir($dir) or mkdir($dir, 0775, true);
        $this->target = $dir . '/' . date('Y-m-d') . '.log';
    }

    /**
     * @param $level
     * @param $message
     * @param array      $context
     */
    public function log($level, $message, array $context = [])
    {
        $message = $this->format($message, $context);
        $handle = fopen($this->target, 'a');
        fwrite($handle, sprintf('[%s] %s', strtoupper($level), $message) . PHP_EOL);
        fclose($handle);
    }
}
