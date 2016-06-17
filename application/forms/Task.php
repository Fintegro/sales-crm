<?php

class Application_Form_Task extends Zend_Form
{
	public function init()
    {
        $this->setName('task');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

		$isEmptyMessage = 'This field is  required';
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
		
		$id_project = new Zend_Form_Element_Select('id_project');
        $id_project->setLabel('Project')
            ->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
		$projects = new Application_Model_DbTable_Projects();
		$options = $projects->getProjectsList();
		$id_project->addMultiOptions($options);
		
		$id_status = new Zend_Form_Element_Select('id_status');
        $id_status->setLabel('Status')
            ->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
		$status = new Application_Model_DbTable_Status();
		$options = $status->getStatusList();
		$id_status->addMultiOptions($options);

        $date = new Zend_Form_Element_Text('complete_date');
        $date->setLabel('Date*:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['HtmlTag',['tag'=>'div','class'=>'col-md-8',
            ]],['Label',['class' => 'control-label col-md-4']]])
            ->setAttrib('class','form-control')->setAttrib('id','datepicker');
			        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);

        $this->addElements(array($id, $name, $id_project, $id_status, $submit));
        $this->setAttrib('class','form-horizontal');
    }
}