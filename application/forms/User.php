<?php

class Application_Form_User extends Zend_Form
{
    private $isEmptyMessage = 'This field is  required';
	public function init()
    {

        $this->setName('user');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $this->setAttrib('id','modalForm');

        
        $login = new Zend_Form_Element_Text('username');
        $login->setLabel('Name: ')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage))
            )->setDecorators([ ['ViewHelper'],['Errors'],['Label',['class' => 'label control-label']]])
            ->setAttribs(['class'=>'form-control']);
        

			
		$id_role = new Zend_Form_Element_Select('role_id');
        $id_role->setLabel('Role*: ')
            ->setRequired(true)
            ->setDecorators([['ViewHelper'], ['Errors'],])
            ->addDecorator('Label',['class' => 'control-label label'])
        ->setAttrib('class','form-control');
		$options = Zend_Db_Table::getDefaultAdapter()->fetchPairs('SELECT id,name FROM roles ORDER BY id ASC');
		$id_role->setMultiOptions($options);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);

        $this->addElements(array($id, $login, $id_role, $submit));

        $this->setAttrib('class','form-horizontal');
    }

    function addPasswordField()
    {
        $password = new Zend_Form_Element_Text('password');
        $password->setLabel('Password:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'label control-label']]])
            ->setAttrib('class','form-control')->setOrder(2);
        $this->addElement($password);
    }
}