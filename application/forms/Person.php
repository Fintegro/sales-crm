<?php

class Application_Form_Person extends Zend_Form
{
	public function init()
    {
        $this->setName('person');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

		$isEmptyMessage = 'This field is  required';
        
        $firstName = new Zend_Form_Element_Text('firstName');
        $firstName->setLabel('Name')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
        
        $lastName = new Zend_Form_Element_Text('lastName');
        $lastName->setLabel('Surname')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
			
		$price = new Zend_Form_Element_Text('price');
        $price->setLabel('Salary per month')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->addValidator('Float',true,['messages'=>"Enter correct value"])->setDecorators([['ViewHelper'], 
                ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
			
		
		
		$price_curr = new Zend_Form_Element_Select('price_curr');
        $price_curr->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],])
                        ->setAttrib('class','form-control');
		$currencies = new Application_Model_DbTable_Currency();
		$options = $currencies->getCodes();
		$price_curr->addMultiOptions($options);
			
		$workHrs = new Zend_Form_Element_Text('workHrs');
        $workHrs->setLabel('Work hours')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->addValidator('Float',true,['messages'=>"Enter correct value"])->setDecorators([['ViewHelper'], 
                ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
			
		$targetSale = new Zend_Form_Element_Text('effective_rate');
        $targetSale->setLabel('Target sale')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->addValidator('Float',true,['messages'=>"Enter correct value"])->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
			
			
		$ts_curr = new Zend_Form_Element_Select('ts_curr');
        $ts_curr->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],])
            ->setAttrib('class','form-control');
		$ts_curr->setMultiOptions($options);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);

        $this->addElements(array($id, $firstName, $lastName, $price, $price_curr, $workHrs, $targetSale, $ts_curr, $submit));
        $this->setAttrib('class','form-horizontal')->setAttrib('id','modalForm');
    }
}