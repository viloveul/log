<?php

error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__ . '/vendor/autoload.php';

$man = Viloveul\Log\LoggerFactory::instance();
$man->getCollection()->add(
    new Viloveul\Log\Provider\FileProvider(__DIR__)
);

set_exception_handler([$man, 'handleException']);
set_error_handler([$man, 'handleError']);

function abc()
{
	wow();
}

abc();
