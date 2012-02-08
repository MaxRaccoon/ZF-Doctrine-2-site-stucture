<?php
require 'library/Doctrine2/ORM/Tools/Setup.php';

// Setup Autoloader (1)
Doctrine\ORM\Tools\Setup::registerAutoloadPEAR();

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();

$driverImpl = new \Doctrine\ORM\Mapping\Driver\YamlDriver("/home/raccoon/web/default.zf/library/ZF/yml-mapping/");
//$driverImpl = $config->newDefaultAnnotationDriver(array("/home/raccoon/web/default.zf/library/ZF/Entity"));
//$driverImpl->setFileExtension(".yml");
$config->setMetadataDriverImpl($driverImpl);
//$config->getMetadataDriverImpl();

$config->setEntityNamespaces(array('ZF/Model/Entity'));

$config->setProxyDir('library/ZF/Entity/Proxy');
$config->setProxyNamespace('ZF/Entity/Proxy');

$config->setAutoGenerateProxyClasses(true);
//$config->getAutoGenerateProxyClasses();

$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
//$config->getMetadataCacheImpl();

$config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
//$config->getQueryCacheImpl();


$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'dbname' => 'default',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

/** @var $em \Doctrine\ORM\EntityManager */
$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
