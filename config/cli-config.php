<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . "/../vendor/autoload.php";

// the connection configuration
$dbParams = ['url' => include __DIR__ . '/doctrine-url.php',];
$paths    = [__DIR__ . '/../tests/Entity/'];

// https://stackoverflow.com/a/21068085/4345061
$isDevMode = true;
$cache     = new ArrayCache();
$reader    = new AnnotationReader();
$driver    = new AnnotationDriver($reader, $paths);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$config->setMetadataDriverImpl($driver);

$entityManager = EntityManager::create($dbParams, $config);

//-- This I had to add to support the Mysql enum type.
$platform = $entityManager->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');

return ConsoleRunner::createHelperSet($entityManager);
