<?php

class ClientsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    public function indexAction()
    {
        $data=['title'=>'Clients list','addButton'=>'Add new client', 'addUrl'=>['controller'=>'clients', 'action'=>'add'],
            'columns'=>['Name','Country','Email','Phone', 'Skype', 'Note'],
            'rows'=>(new Application_Model_DbTable_Clients())->fetchClients(),
            'edits'=>['edit'=>['controller'=>'clients','action'=>'edit'],
                'delete'=>['controller'=>'clients','action'=>'delete']],'filter'=>true];
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    public function addAction()
    {
        $form =( new Application_Form_Client())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'clients','action' => 'add'),null,true));
        $viewData=['modalData'=>['form'=>$form],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'clients/add',
            'modalTitle'=>'Add new client','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $name = $form->getValue('name');
                $id_country = $form->getValue('id_country');
				$note = $form->getValue('note');
				$e_mail = $form->getValue('e_mail');
                $phone = $form->getValue('phone');
                $skype = $form->getValue('skype');
                $clients = new Application_Model_DbTable_Clients();
                $clients->addClient($name, $id_country, $note, $e_mail, $phone, $skype);
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
        $form =( new Application_Form_Client())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'clients','action' => 'edit'),null,true));
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'clients/add',
            'modalTitle'=>'Edit client','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $id = (int)$form->getValue('id');
                $name = $form->getValue('name');
                $id_country = $form->getValue('id_country');
				$note = $form->getValue('note');
				$e_mail = $form->getValue('e_mail');
                $phone = $form->getValue('phone');
                $skype = $form->getValue('skype');
                $clients = new Application_Model_DbTable_Clients();
                $clients->editClient($id, $name, $id_country, $note, $e_mail, $phone, $skype);
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{

            $clients = new Application_Model_DbTable_Clients();
            $form->populate($clients->getClient($id));           

        }
        $this->_helper->renderHelper->renderModalView();
        
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) 
		{
            $id = $this->getRequest()->getPost('id');
            $clients = new Application_Model_DbTable_Clients();
            $clients->deleteClient($id);
            $this->_helper->redirector('index');
        } 
		else
		{
            $id = $this->_getParam('id');
            $clients = new Application_Model_DbTable_Clients();
            $client = $clients->getClientNameById($id);            

            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete  '.$client.'?','id'=>$id,
                'url'=>['controller'=>'clients', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete client','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }


}







