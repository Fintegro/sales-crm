<?php

class Application_Form_Client extends Zend_Form
{
	public function init()
    {
		$this->setName('client');

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
        
        $id_country = new Zend_Form_Element_Select('id_country');
        $id_country->setLabel('Country')
            ->setRequired(true)->setDecorators([['ViewHelper'], ['Errors'],
                ['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
		$countries = new Application_Model_DbTable_Countries();
		$options = $countries->getCountriesList();
		$id_country->addMultiOptions($options);
			
		$note = new Zend_Form_Element_Textarea('note');
        $note->setLabel('Note')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control')/*->setDecorators([['ViewHelper'], ['Errors'],['HtmlTag',['tag'=>'div','class'=>'col-md-10',
            ]],['Label',['class' => 'control-label col-md-2']]])
            ->setAttrib('class','form-control')*/->setAttrib('rows','8');
			
		$e_mail = new Zend_Form_Element_Text('e_mail');
        $e_mail->setLabel('E-mail')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            ) ->addValidator(new Zend_Validate_EmailAddress(),true)->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
			
		$phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Phone')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')->addValidator(new Application_Form_Validate_Phone())->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
			
			
		$skype = new Zend_Form_Element_Text('skype');
        $skype->setLabel('Skype')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);;
        $this->addElements(array($id, $name, $id_country, $e_mail, $phone, $skype, $note, $submit));

        $this->setAttrib('class','form-horizontal')->setAttrib('id','modalForm');

    }
}