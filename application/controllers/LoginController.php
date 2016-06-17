<?php


class LoginController extends Zend_Controller_Action
{


    function indexAction()
    {
        $form = new Application_Form_Login();
        $this->view->form = $form;
        if($this->getRequest()->isPost())
        {
            $this->processAuth($form);
        }
    }

    function processAuth($form)
    {

        $data = $this->getRequest()->getPost();
        if(!$form->isValid($data))
        {
            $form->populate($data);
            $this->renderScript('login/index.phtml');

        }else
        {
            $auth = new Application_Service_Auth();
            if(!$auth->isSuccessAuthentification($data))
            {
                $this->view->errorMessage='Invalid login or password';
            }
			else
			{
               $this->_redirect('/');

            }

        }
    }


    function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        \Zend_Session::destroy( true );
        $this->_redirect('login');
    }
}