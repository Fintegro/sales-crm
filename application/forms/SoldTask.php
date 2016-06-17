<?php

class Application_Form_SoldTask extends Zend_Form
{
	public function init()
    {
		$this->setName('tasks');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

		$isEmptyMessage = 'This field is  required';      
        
        $id_task = new Zend_Form_Element_Select('id_task');
        $id_task->setLabel('Task: ')
            ->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label']]])
            ->setAttrib('class','form-control');
		$tasks = new Application_Model_DbTable_SoldTasks();
		$options = $tasks->getNotSoldTaskList();
		$id_task->addMultiOptions($options);


        $date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date*:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],
            ['Label',['class' => 'control-label col-md-4']]])
            ->setAttrib('class','form-control')->setAttrib('id','datepicker');
        
        $rate =  new Zend_Form_Element_Text('rate');
        $rate-> setLabel('Rate*:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage)))
            ->addValidator('Float',true,['messages'=>"Enter correct value"])
            ->setDecorators([['ViewHelper'], ['Errors']])
            ->setAttrib('class','form-control');			
		
        $rate_curr = new Zend_Form_Element_Select('rate_curr');
        $rate_curr->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label col-md-4']]])
            ->setAttrib('class','form-control');         
		$currency = new Application_Model_DbTable_Currency();
		$options = $currency->getCodes();
		$rate_curr->addMultiOptions($options);


        $hours =  new Zend_Form_Element_Text('hours');
        $hours-> setLabel('Hours*:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage)))
            ->addValidator('Float',true,['messages'=>"Enter correct value"])
            ->setDecorators([['ViewHelper'], ['Errors']])
            ->setAttrib('class','form-control');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id, $date, $id_task, $rate, $rate_curr, $hours, $submit));
    }
}