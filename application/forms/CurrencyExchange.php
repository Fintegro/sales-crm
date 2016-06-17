<?php


class Application_Form_CurrencyExchange extends Zend_Form
{
    private $isEmptyMessage = 'This field is  required';
    private $currencies;



    function init()
    {

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $date = new Zend_Form_Element_Text('date');
        $date->setLabel('Date:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage))
            )->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');

        $first_currency = new Zend_Form_Element_Select('first_currency');
        $first_currency->setLabel('First Currency: ')
            ->setRequired(true)
            ->setDecorators([['ViewHelper'], ['Errors'],])
            ->addDecorator('Label',['class' => 'control-label label'])
            ->setAttrib('class','form-control');
        $options = $this->getCurrencies();
        $first_currency->setMultiOptions($options);

        $second_currency = new Zend_Form_Element_Select('second_currency');
        $second_currency->setLabel('Second Currency: ')
            ->setRequired(true)
            ->setDecorators([['ViewHelper'], ['Errors']])
            ->addDecorator('Label',['class' => 'control-label label'])
            ->setAttrib('class','form-control')
            ->addValidator(new Application_Form_Validate_NotIdentical('first_currency'),true);
        $options = $this->getCurrencies();
        $second_currency->setMultiOptions($options);


        $exchange_rate =  new Zend_Form_Element_Text('exchange_rate');
        $exchange_rate-> setLabel('Exchange rate:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $this->isEmptyMessage)))
            ->addValidator('Float',true,['messages'=>"Enter correct value"])
            ->setDecorators([['ViewHelper'], ['Errors'],['Label',['class' => 'control-label label']]])
            ->setAttrib('class','form-control');


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(['id'=>'submitbutton', 'class'=>'btn btn-default'] )
            ->setDecorators([['ViewHelper']]);

        $this->addElements(array($id,$date, $first_currency, $second_currency, $exchange_rate, $submit));
        $this->setAttrib('id','eventForm');

        $this->setAttribs((['id'=>'modalForm','class'=>'form-horizontal']));

    }

    function getCurrencies()
    {
        if(!$this->currencies)
        {
            $this->currencies = Zend_Db_Table::getDefaultAdapter()->fetchPairs('SELECT id,ISO_code FROM currency');
        }
        return $this->currencies;
    }


}