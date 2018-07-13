<?php
define('WWW_DIR', __DIR__);
$container = require __DIR__ . '/../Examples/bootstrap.php';

$container->getByType('Nette\Application\Application')->run();