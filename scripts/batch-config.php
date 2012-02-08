<?php
/*
 *  This file should be include in any batch processing scripts. It is used by ./scripts/insert-countries.php
 *  It instantiates Bisna\Doctrine\Container as $container.
 */

// Include this file when doing batch processing, then call: $container->getEntityManager()
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Creating application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// Bootstrapping resources
$bootstrap = $application->bootstrap()->getBootstrap();

// Retrieve Doctrine Container resource
$container = $application->getBootstrap()->getResource('doctrine');

?>
