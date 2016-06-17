<?php

class Application_Form_Login extends Zend_Form
{
    function init()
    {
        $this->setName("login");
        $this->setMethod('post');
        $urlHelper = new Zend_View_Helper_Url();
        $this->setAction($urlHelper->url(['controller'=>'login', 'action'=>'index']));
        $this->addElems();
    }

    private function addElems()
    {
        $this->addElement('text', 'username', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required' => true,
            'label' => 'Username :',
            'decorators' => array('ViewHelper', 'Errors',array('Label'=>array('tag' => 'label')))

        ));

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required' => true,
            'label' => 'Password :',
            'decorators' => array('ViewHelper', 'Errors',array('Label'=>array('tag' => 'label')))
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Login to...',
            'decorators' => array('ViewHelper')
        ));
        $this->getElement('username')->setAttrib('class','form-control');
        $this->getElement('password')->setAttrib('class','form-control');
        $this->getElement('login')->setAttrib('class','button primary');

    }
}