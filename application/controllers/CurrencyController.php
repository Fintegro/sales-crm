<?php

class CurrencyController extends Zend_Controller_Action
{


    public function indexAction()
    {
        $currencies = new Application_Model_DbTable_Currency();
        $this->view->currency = $currencies->fetchAll();

        $data=['title'=>'Currency List','addButton'=>'Add new currency', 'addUrl'=>['controller'=>'currency', 'action'=>'add'],
            'columns'=>['ID','ISO Code'],'rows'=>(new Application_Model_DbTable_Currency())->fetchAll(),
            'edits'=>['edit'=>['controller'=>'currency','action'=>'edit'],
                'delete'=>['controller'=>'currency','action'=>'delete']],'filter'=>true];
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    public function addAction()
    {
        $form = (new Application_Form_Currency())->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'currency','action' => 'add'),null,true));
        $viewData=['modalData'=>['form'=>$form],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'currency/add',
            'modalTitle'=>'Add new currency','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);       
        
        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $ISO_code = $form->getValue('ISO_code');
                $currencies = new Application_Model_DbTable_Currency();
                $currencies->addCurrency($ISO_code);
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        }
        $this->_helper->renderHelper->renderModalView();
            
    }

    public function editAction()
    {


       
        $form = ((new Application_Form_Currency())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'currency','action' => 'edit'),null,true)) );        
        $id = $this->_getParam('id');        
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'currency/add',
            'modalTitle'=>'Edit currency','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $id = (int)$form->getValue('id');
                $ISO_code = $form->getValue('ISO_code');
                $currencies = new Application_Model_DbTable_Currency();
                $currencies->updateCurrency($id, $ISO_code);
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{
            $exchange = new Application_Model_DbTable_Currency();
            $currency = $exchange->getCurrency($id);
            $form->populate($currency);           
        }
        $this->_helper->renderHelper->renderModalView();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) 
		{

            $id = $this->getRequest()->getPost('id');
            $currencies = new Application_Model_DbTable_Currency();
            $currencies->deleteCurrency($id);
            $this->_helper->redirector('index');
        } 
		else
		{
            $id = $this->_getParam('id', 0);
            $currencies = new Application_Model_DbTable_Currency();            
            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete "'.$currencies->getCurrency($id)['ISO_code'] . '"?','id'=>$id,
                'url'=>['controller'=>'currency', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete currency','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }

    function currencyRateAction()
    {

        $data=['title'=>'Currency Exchange','addButton'=>'Add new currency rate', 'addUrl'=>['controller'=>'currency', 'action'=>'add-currency-rate'],
        'columns'=>['Date','First currency','Second currency','Exchange Rate'],'rows'=>(new Application_Model_DbTable_CurrencyExchange())->fetchAll(),
        'edits'=>['edit'=>['controller'=>'currency','action'=>'edit-currency-rate'],
            'delete'=>['controller'=>'currency','action'=>'delete-currency-rate']],'filter'=>true];
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    function addCurrencyRateAction()
    {
        $this->view->title='Add Exchange Rate';
        $form = ((new Application_Form_CurrencyExchange(null))
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'currency','action' => 'add-currency-rate'),null,true)) );
        $viewData=['modalData'=>['form'=>$form],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'currency/add-currency-rate',
            'modalTitle'=>'Add new currency rate','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
        {

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
            {
                $data = ['date'=>$form->getValue('date'),'id_currency_first'=>$form->getValue('first_currency'),
                        'id_currency_second'=>$form->getValue('second_currency'), 'current_rate'=>$form->getValue('exchange_rate')];
                $exchangeModel = new Application_Model_DbTable_CurrencyExchange();
                $exchangeModel->addCurrencyRate($data);

                $this->_helper->redirector('currency-rate');
            }
            else
            {
                $form->populate($formData);
            }
        }
        $this->_helper->renderHelper->renderModalView();

    }

    function editCurrencyRateAction()
    {
        
        $form = ((new Application_Form_CurrencyExchange())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'currency','action' => 'edit-currency-rate'),null,true)) );

        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'currency/add-currency-rate',
            'modalTitle'=>'Edit currency rate','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);


        if ($this->getRequest()->isPost())
        {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
            {
                $data = ['date'=>$form->getValue('date'), 'id_currency_first'=>$form->getValue('first_currency'),
                'id_currency_second'=>$form->getValue('second_currency'), 'current_rate'=>$form->getValue('exchange_rate')];
                $rate = new Application_Model_DbTable_CurrencyExchange();
                $rate->updateCurrencyRate($form->getValue('id'), $data);
                $this->_helper->redirector('currency-rate');
            }
            else
            {

                $form->populate($formData);
            }
        }
        else
        {
            $exchange = new Application_Model_DbTable_CurrencyExchange();
            $rate = $exchange->getCurrencyRate($id);
            $form->populate($rate);

        }
        $this->_helper->renderHelper->renderModalView();
    }

    function deleteCurrencyRateAction()
    {

        if($this->getRequest()->isPost())
        {

                $id = $this->getRequest()->getPost('id');
                $rate = new Application_Model_DbTable_CurrencyExchange();
                $rate->deleteCurrencyRate($id);
                $this->_redirect('currency/currency-rate');

        }
        else
        {           
            $viewData=['modalData'=>['confirmMessage'=>'Are you sure you want to delete currency rate?','id'=>$this->getRequest()->getParam('id'),
                'url'=>['controller'=>'currency', 'action'=>'delete-currency-rate']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete currency rate','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }


}







