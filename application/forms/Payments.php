<?php


class Application_Form_Payments extends Zend_Form
{
    private $isEmptyMessage = 'This field is  required';


    function init()
    {

        $this->setName("login");
        $this->setMethod('post');

        $this->setAttrib('class','form-horizontal');
        $this->setAttrib('id','modalForm');
        $this->addElems();
    }
    function addElems()
    {
        $this->addElement('text', 'date', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('validator'=>'NotEmpty', 'breakChainOnFailure' => true, 'options'=>['messages' => ['isEmpty' => $this->isEmptyMessage]]),
            ),
            'required' => true,
            'label' => 'Date*:',
            'decorators' => $this->getStandartDecorators(),
            'attribs' => ['class'=>'form-control', 'id'=>'datepicker']

        ));
        $this->addElement('select','id_task',[
            'required'=> true,
            'label' => 'Project:',
            'decorators' => $this->getStandartDecorators(),
            'attribs' => ['class'=>'form-control']
        ]);
        $projectOptions = (new Application_Model_DbTable_Tasks())->getTasksList();
        $this->getElement('id_task')->setMultiOptions($projectOptions);

        $this->addElement('text','summ',[
            'required' => true,
            'label' =>'Amount*: ',
            'filters' => array('StringTrim'),
            'validators'=>[['validator'=>'Float','breakChainOnFailure' => true]],
            'decorators'=>$this->getStandartDecorators(5),
            'attribs' => ['class'=>'form-control']
        ]);
        $this->addElement('select','summ_curr',[
            'required'=> true,
            'decorators' =>['ViewHelper', 'Errors'],
            'attribs' => ['class'=>'form-control']
        ]);
        $currencyOptions = (new Application_Form_CurrencyExchange())->getCurrencies();
        $this->getElement('summ_curr')->setMultiOptions($currencyOptions);

        $this->addElement('text','commisions',[

            'label' =>'Comission: ',
            'filters' => array('StringTrim'),
           'validators'=>[['validator'=>'Float','breakChainOnFailure' => true]],
            'decorators'=>$this->getStandartDecorators(5),
            'attribs' => ['class'=>'form-control']
        ]);
        $obj = clone $this->getElement('summ_curr');
        $this->addElement($obj->setName('comm_curr'));
        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Save',
            'decorators' => array('ViewHelper'),
            'attribs' =>['class'=>'btn btn-default', 'id'=>'submitbutton']
        ));
        $this->addElement(new Zend_Form_Element_Hidden('id'));
    }



    private function getStandartDecorators()
    {
        return array('ViewHelper', 'Errors', array('Label',array('class' => 'control-label label')));
    }

}