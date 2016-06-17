<?php

class Application_Form_InProgress extends Zend_Form
{
    private $EmptyMessage;

    function getFullForm()
    {
        $this->setName('inprogress');
        $this->EmptyMessage = 'This field is  required';

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $tasks = new Zend_Form_Element_Select('tasks');
        $tasks->setLabel('Task:')
            ->setRequired(true)
            ->setDecorators([['ViewHelper'], ['Errors'],]);

        $doneTasks = (array_merge([['key'=>0, 'value'=>'All']],(new Application_Model_WorkDone())->getDoneTasksList()));
        $tasks->addMultiOptions($doneTasks);

        /*$date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->EmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors']])
            ->setAttrib('class','form-control');*/
        $date = new Zend_Form_Element_Multiselect('date');
        $date->setLabel('Date:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->EmptyMessage)))
            ->addValidator('Float',true,['messages'=>"Enter correct value"])
            ->setDecorators([['ViewHelper'], ['Errors']])
            ->setAttrib('id','mu');
        $date->addMultiOptions( (new Application_Model_WorkDone())->getDatesDoneWorks());

        $id_programmist = new Zend_Form_Element_Select('id_programmist');
        $id_programmist->setLabel('Programmist:')
            ->setRequired(true)
            ->setDecorators([['ViewHelper'], ['Errors']]);

        $programmists = new Application_Model_DbTable_Programmists();
        $options = $programmists->getProgrammistsList();
        $id_programmist->addMultiOptions(array_merge([['key'=>0, 'value'=>'All']],$options));


        $this->addElements(array($id, $tasks, $date, $id_programmist));
        $this->addElement((new Zend_Form_Element_Submit('submit'))
            ->setLabel('Filter')->setAttrib('form', 'table_filter')
            ->setAttrib('class', 'button success filterButton')
            ->setDecorators([['ViewHelper']]));
        return $this;
    }
    function getDateInput()
    {
        $date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->EmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors']])
            ->setAttrib('class','form-control');
        $this->addElement($date);
    }

    function getTaskInput()
    {
        $this->getSelect('task','Task ');
    }

    function getProgrammerInput()
    {
        $this->getSelect('programmer','Programmer: ');
    }

    function getNoteInput()
    {
        $this->getTextField('note','Note ');
    }
    function getHoursInput()
    {
        $this->getTextField('hours', 'Hours ');
    }

    private function getTextField($name,$label)
    {
        $textField =  new Zend_Form_Element_Text($name);
        $textField -> setLabel($label)
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage)))
            ->addValidator('Float',true,['messages'=>"Enter correct value"])
            ->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]]);
        $this->addElement($textField);
    }
    
    private function getSelect($name,$label)
    {
        $select = new Zend_Form_Element_Select($name);
        $select->setLabel($label)
            ->setRequired(true)
            ->setDecorators([['ViewHelper'], ['Errors']]);
        $options = null;
        if($name == 'tasks' || $name=='task')
        {
            $options = (new Application_Model_DbTable_Tasks())->getTasksList();
        }
        else
        {
            $programmists = new Application_Model_DbTable_Programmists();
            $options = $programmists->getProgrammistsList();
        }

        $select->addMultiOptions($options);
        $this->addElement($select);
    }


    

}