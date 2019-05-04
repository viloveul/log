<?php

namespace Viloveul\Log;

use Viloveul\Log\Logger;
use Viloveul\Log\Collection;
use Viloveul\Log\Contracts\Logger as ILogger;

class LoggerFactory
{
    /**
     * @var mixed
     */
    private static $logger;

    public static function instance(): ILogger
    {
        if (!(static::$logger instanceof ILogger)) {
            static::$logger = new Logger();
            static::$logger->setCollection(new Collection());
        }
        return static::$logger;
    }
}
