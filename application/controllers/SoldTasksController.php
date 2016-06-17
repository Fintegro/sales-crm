<?php

class SoldTasksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    public function indexAction()
    {


        $data=['title'=>'Sold Tasks','addButton'=>'Sell task', 'addUrl'=>['controller'=>'sold-tasks', 'action'=>'add'],
            'columns'=>['Name','Date','Rate', 'Rate currency', 'Hours'],
            'rows'=>(new Application_Model_DbTable_SoldTasks())->fetchSoldTasks(),
            'edits'=>(Zend_Auth::getInstance()->getStorage()->read()['role']=='admin'?['edit'=>['controller'=>'sold-tasks','action'=>'edit'],
                'delete'=>['controller'=>'sold-tasks','action'=>'delete']]:'')];

        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    public function addAction()
    {
        $form = (new Application_Form_SoldTask())
        ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'sold-tasks','action' => 'add'),null,true));
         $viewData=['modalData'=>['form'=>$form,'urlTask'=>['controller'=>'tasks', 'action'=>'add']],'confirmButton'=>'Add','closeButton'=>'Close',
             'modalView'=>'sold-tasks/add',
             'modalTitle'=>'Sell task','form'=>'modalForm'];
        $this->view->edit = false;
        $this->_helper->renderHelper->setModalData($viewData);
        
        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{    
                $data = ['date'=> $form->getValue('date'), 'id_task'=>$form->getValue('id_task'),
                'rate'=>$form->getValue('rate'),'rate_curr'=>$form->getValue('rate_curr'),
                'hours'=>$form->getValue('hours')];
                $tasks = new Application_Model_DbTable_SoldTasks();
                $tasks->addSoldTask($data);
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
        $form = (new Application_Form_SoldTask())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'sold-tasks','action' => 'edit'),null,true));
        $form->getElement('id_task')->addMultioptions((new Application_Model_DbTable_Tasks())->getTasksList());
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id,'urlTask'=>['controller'=>'tasks', 'action'=>'add']],
            'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'sold-tasks/add',
            'modalTitle'=>'Edit task','form'=>'modalForm'];
        $this->view->edit = true;
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{                				
				$data = ['id'=>(int)$form->getValue('id'),
                    'date'=> $form->getValue('date'), 
                    'id_task'=>$form->getValue('id_task'),
                    'rate'=>$form->getValue('rate'),
                    'rate_curr'=>$form->getValue('rate_curr'),
                    'hours'=>$form->getValue('hours')];
                $soldtasks = new Application_Model_DbTable_SoldTasks();
                $soldtasks->editSoldTask($data);                
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{
                $soldtasks = new Application_Model_DbTable_SoldTasks();
                $form->populate($soldtasks->getSoldTask($id));            
        }
        $this->_helper->renderHelper->renderModalView();
        
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost())
        {
            $id = $this->getRequest()->getPost('id');
            $tasks = new Application_Model_DbTable_Tasks();
            $tasks->deleteTask($id);

            $this->_helper->redirector('index');
        }
        else
        {
            $id = $this->_getParam('id');
            $soldTasks =  new Application_Model_DbTable_SoldTasks();
            $soldTask = $soldTasks->getSoldTask($id);
            $tasks = new Application_Model_DbTable_Tasks();
            $task = $tasks->getTaskNameById($soldTask['id_task']);

            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete  task '.$task.'?','id'=>$id,
                'url'=>['controller'=>'tasks', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete task','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }


}







