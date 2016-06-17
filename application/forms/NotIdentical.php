<?php

class  Application_Form_NotIdentical extends Zend_Validate_Identical
{
    function __construct($token)
    {
        parent::__construct($token);
    }
    protected $_messageTemplates = array(
        self::NOT_SAME => "Values must be different",
    );
    public function isValid($value)
    {

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $first_currency = $request->getPost($this->_token);
        if((int)$first_currency == (int)$value)
        {
            $this->_error(self::NOT_SAME);
            return false;
        }

       return true;
    }
}