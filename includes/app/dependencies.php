<?php

use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Register component on container
/**
 * @param $container
 * @return \Slim\Views\Twig
 * @throws \Twig\Error\LoaderError
 */
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
    );

    // Instantiate and add Slim\Twig specific extension
    $basePath = rtrim(str_ireplace('bootstrap.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

/**
 * @return Logger
 */
$container['logger'] = function (){
    $logs_file_path = '/p3t/phpappfolder/logs/';
    $logs_file_name = 'EE_SOAP_CLIENT_LOG.log';
    $logs_file = $logs_file_path . $logs_file_name;
    $log = new Logger('Logger');
    $log->pushHandler(new StreamHandler($logs_file, Logger::INFO));
    return $log;
};

/**
 * @param $container
 * @return \EESoapClient\SQLHelper
 */
$container['sqlHelper'] = function ($container) {
    return new \EESoapClient\SQLHelper($container['logger'], $container[EntityManager::class]);
};

/**
 * @param $container
 * @return \EESoapClient\SoapClientEE
 */
$container['soapClientEE'] = function ($container) {
    return new \EESoapClient\SoapClientEE($container['logger'], $container['sqlHelper']);
};


