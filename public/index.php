<?php

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

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$frontController = Zend_Controller_Front::getInstance();
$frontController->registerPlugin(new Application_Plugin_Acl());
/*$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->pushAutoloader(function($class){
    var_dump($class);
    $parts = explode('_',$class);
    var_dump($parts);
    $path=[];
    for($i=1;$i<count($parts)-1;$i++)
    {
        if(strtolower($parts[$i])=='form')
        {
            $path[]='forms';
            continue;
        }
        $path[]=strtolower($parts[$i]);

    }
    $path[]=$parts[count($parts)-1];
    $path = APPLICATION_PATH.'/'.implode(DIRECTORY_SEPARATOR,$path).'.php';
    var_dump($path);
    require_once $path;
});*/

$application->bootstrap()
            ->run();
			