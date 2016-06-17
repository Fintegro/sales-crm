<?php

class EmployeesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $data=['title'=>'Employees list','addButton'=>'Add new employee', 'addUrl'=>['controller'=>'employees', 'action'=>'add'],
        'columns'=>['Name','Surname','Salary','Salary currency', 'Work hours','Minimal effective rate','Target sale','Target Sale currency'],
            'rows'=>(new Application_Model_DbTable_Programmists())->fetchProgrammists(),
        'edits'=>['edit'=>['controller'=>'employees','action'=>'edit'],
            'delete'=>['controller'=>'employees','action'=>'delete']],'filter'=>true];
        $this->_helper->layout->disableLayout();        
        $this->_helper->renderHelper->setDataForListTable($data);
        
    }

    public function addAction()
    {

        $this->view->title='Add new employee';
        $form = ((new Application_Form_Person())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'employees','action' => 'add'),null,true)) );
        $viewData=['modalData'=>['form'=>$form],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'employees/add',
            'modalTitle'=>'Add new employee','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        
        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $firstName = $form->getValue('firstName');
                $lastName = $form->getValue('lastName');
				$price = $form->getValue('price');
				$price_curr = $form->getValue('price_curr');
                $workHrs = $form->getValue('workHrs');
                $targetSale = $form->getValue('effective_rate');
				$ts_curr = $form->getValue('ts_curr');				
                $employees = new Application_Model_DbTable_Programmists();
                $employees->addPerson($firstName, $lastName, $price, $price_curr, $workHrs, $targetSale, $ts_curr);                
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->renderModalView();
            
    }

    public function editAction()
    {

       
        $form = ((new Application_Form_Person())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'employees','action' => 'edit'),null,true)) );
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id],'confirmButton'=>'Edit','closeButton'=>'Close','modalView'=>'employees/add',
            'modalTitle'=>'Edit employee','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);

        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $id = (int)$form->getValue('id');
                $firstName = $form->getValue('firstName');
                $lastName = $form->getValue('lastName');
				$price = $form->getValue('price');
				$price_curr = $form->getValue('price_curr');
                $workHrs = $form->getValue('workHrs');
                $targetSale = $form->getValue('effective_rate');
				$ts_curr = $form->getValue('ts_curr');	
				
                $employees = new Application_Model_DbTable_Programmists();
                $employees->editPerson($id, $firstName, $lastName, $price, $price_curr, $workHrs, $targetSale, $ts_curr);
                
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{
            $id = $this->_getParam('id');
            $employees = new Application_Model_DbTable_Programmists();
            $form->populate($employees->getPerson($id));
        }
        $this->_helper->renderHelper->renderModalView();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) 
		{
            $id = $this->getRequest()->getPost('id');
            $employees = new Application_Model_DbTable_Programmists();
            $employees->deletePerson($id);
            $this->_helper->redirector('index');
        } 
		else
		{
            $id = $this->_getParam('id', 0);
            $employees = new Application_Model_DbTable_Programmists();
            $employee = $employees->getPerson($id);          

            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete  '.$employee['firstName'].' '.$employee['lastName'].'?','id'=>$id,
                'url'=>['controller'=>'employees', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete employee','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }


}







