<?php

error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__ . '/vendor/autoload.php';

$log = Viloveul\Log\LoggerFactory::instance();
$log->getCollection()->add(
    new Viloveul\Log\Provider\FileProvider(__DIR__),
    'runtimeexception'
);

set_error_handler([$log, 'handleError']);
register_shutdown_function([$log, 'process']);

function abc()
{
    wow();
}

trigger_error("dor");
