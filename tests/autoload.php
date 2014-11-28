<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Apps\\', __DIR__ . '/src');
$loader->addPsr4('Framework\\TestExtensions\\', __DIR__ . '/extensions');
$loader->addPsr4('Framework\\', __DIR__ . '/libs/framework');

return $loader;
