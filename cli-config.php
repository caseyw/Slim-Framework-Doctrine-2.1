<?php

require 'Doctrine/ORM/Tools/Setup.php';

Doctrine\ORM\Tools\Setup::registerAutoloadDirectory(array(__DIR__ . '/libs/'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


 $classLoader = new \Doctrine\Common\ClassLoader(__DIR__ . '/app/Entities', __DIR__);
 $classLoader->register();
 $classLoader = new \Doctrine\Common\ClassLoader(__DIR__ . '/app/Proxies', __DIR__);
 $classLoader->register();


 $config = new \Doctrine\ORM\Configuration();


 $config->setProxyDir(__DIR__ . '/app/Proxies');
 $config->setProxyNamespace('Proxies');

 $config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));


 $driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__ . "/app/Entities"));
 $config->setMetadataDriverImpl($driverImpl);


 if (APPLICATION_ENV == "development") {

     $cache = new \Doctrine\Common\Cache\ArrayCache();

 } else {

     $cache = new \Doctrine\Common\Cache\ApcCache();
 }

 $config->setMetadataCacheImpl($cache);
 $config->setQueryCacheImpl($cache);

require __DIR__ . '/config.php';

 $connectionOptions = array(
    'driver' => 'pdo_mysql',
    'dbname' => $dbname,
    'user' => $dbuser,
    'password' => $dbpass,
    'host' => $dbhost
);

 $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

 $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
     'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
     'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
 ));


