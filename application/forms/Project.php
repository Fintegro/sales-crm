<?php

class Application_Form_Project extends Zend_Form
{
	public function init()
    {

        $this->setName('person');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

		$isEmptyMessage = 'This field is  required';
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Project name')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
        
        $code = new Zend_Form_Element_Text('code');
        $code->setLabel('Code')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');

		$id_client = new Zend_Form_Element_Select('id_client');
        $id_client->setLabel('Client')
            ->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
		$clients = new Application_Model_DbTable_Clients();
		$options = $clients->getClientsList();
		$id_client->addMultiOptions($options);
			
		$note = new Zend_Form_Element_Textarea('note');
        $note->setLabel('Note')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')->setDecorators([['ViewHelper'], ['Errors']    ,['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control')->setAttrib('rows','8');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);
        $this->addElements(array($id, $name, $code, $id_client, $note, $submit));
        $this->setAttrib('class','form-horizontal')->setAttrib('id','modalForm');
    }
}