<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload () {

        spl_autoload_register(function($class)
        {
            $parts = explode('_',$class);
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
            require_once $path;
        });       
    }
    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers' );
    }	
}
