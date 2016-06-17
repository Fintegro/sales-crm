<?php

/**
 * Created by PhpStorm.
 * User: fintegro
 * Date: 14.03.16
 * Time: 12:00
 */
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()){
            $request->setControllerName('login');
            $request->setActionName('index');
        }


    }
}