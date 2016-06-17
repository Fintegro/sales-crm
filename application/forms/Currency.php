<?php

class Application_Form_Currency extends Zend_Form
{
    public function init()
    {
        $this->setName('currency');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

$isEmptyMessage = 'This field is required';
        
        $ISO_code = new Zend_Form_Element_Text('ISO_code');
        $ISO_code->setLabel('ISO code')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'label control-label']]])
            ->setAttrib('class','form-control')->setAttrib('class','form-control');
        
        $submit = new Zend_Form_Element_Submit('submit');

        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);
        $this->addElements(array($id, $ISO_code, $submit));

        $this->setAttribs((['id'=>'modalForm','class'=>'form-horizontal']));
    }
}