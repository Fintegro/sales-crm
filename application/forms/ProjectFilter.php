<?php

class Application_Form_ProjectFilter extends Zend_Form
{
    function init()
    {

        $currency = new Zend_Form_Element_Select('currency');
        $currency->setRequired(true)->setLabel('Currency: ')
            ->setDecorators([['ViewHelper']])
            ->setAttrib('class','form-control');
        $options = (array_merge([['key'=>0, 'value'=>'Original currency']],(new Application_Form_CurrencyExchange())->getCurrencies()));
        $currency->setMultiOptions($options);

        $clients = new Zend_Form_Element_Select('clients');
        $clients->setRequired(true)->setLabel('Clients:   ')
            ->setDecorators([['ViewHelper']]);
        $clientsOption = (array_merge([['key'=>0, 'value'=>'All']], (new Application_Model_DbTable_Clients())->getClientsList()));
        $clients->setMultiOptions($clientsOption);

        $balance = new Zend_Form_Element_Select('balance');
        $balance->setRequired(true)->setLabel('Balance: ')
            ->setDecorators([['ViewHelper']]);
        $balanceOptions = [['key'=>0, 'value'=>'All'], ['key'=>1, 'value'=>'Positive Balance'],['key'=> 2, 'value'=>'Negative Balance']];
        $balance->setMultiOptions($balanceOptions);

        $this->addElements([$currency, $clients, $balance]);
        $this->addElement((new Zend_Form_Element_Submit('submit'))->setLabel('Filter')->setAttrib('form', 'table_filter')->setAttrib('class', 'button success filterButton')->setDecorators([['ViewHelper']]));
        $this->setMethod('post')->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'projects-report','action' => 'filter'),null,true));
    }
}