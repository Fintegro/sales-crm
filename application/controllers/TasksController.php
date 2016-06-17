<?php

class TasksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $data=['title'=>'Tasks list','addButton'=>'Add new task', 'addUrl'=>['controller'=>'tasks', 'action'=>'add'],
            'columns'=>['Name','Project','Status', 'Complete date'],
            'rows'=>(new Application_Model_DbTable_Tasks())->fetchTasks(),
            'edits'=>(Zend_Auth::getInstance()->getStorage()->read()['role']=='admin'?['edit'=>['controller'=>'tasks','action'=>'edit'],
                'delete'=>['controller'=>'tasks','action'=>'delete']]:'')];
        if(Zend_Auth::getInstance()->getStorage()->read()['role']=='pm')
        {
            $statuses = [];
            foreach($data['rows'] as $row)
            {
                if(strtolower($row['status'])!='done')
                {
                    $statuses[]= $row['status'];
                }

            }
            $this->view->change = ['controller'=>'tasks', 'action'=>'change-status','statuses'=> $statuses];
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    public function addAction()
    {
        $form = (new Application_Form_Task())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'tasks','action' => 'add'),null,true));
        $viewData=['modalData'=>['form'=>$form,'urlProject'=>['controller'=>'projects', 'action'=>'add']],
            'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'tasks/add',
            'modalTitle'=>'Add new task','form'=>'modalForm'];
        $this->view->edit = false;
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{               
                $name = $form->getValue('name');
                $id_project = $form->getValue('id_project');
				$id_status = $form->getValue('id_status');
			    $tasks = new Application_Model_DbTable_Tasks();
                $tasks->addTask($name, $id_project, $id_status);
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
        $form = (new Application_Form_Task())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'tasks','action' => 'edit'),null,true));
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id,'urlProject'=>['controller'=>'clients', 'action'=>'add']],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'tasks/add',
            'modalTitle'=>'Edit task','form'=>'modalForm'];
        $this->view->edit = true;
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $id = (int)$form->getValue('id');
				$data = ['name'=> $form->getValue('name'), 'id_project' =>$form->getValue('id_project'),
                    'id_status'=> $form->getValue('id_status')/*, 'complete_date' => date("Y-m-d")*/];
                $tasks = new Application_Model_DbTable_Tasks();
                $tasks->editTask($id, $data);
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{

            $tasks = new Application_Model_DbTable_Tasks();
            $form->populate($tasks->getTask($id));            
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
            $tasks = new Application_Model_DbTable_Tasks();
            $task = $tasks->getTask($id);           
            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete  task '.$task['name'].'?','id'=>$id,
                'url'=>['controller'=>'tasks', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete task','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }

    function changeStatusAction()
    {
        $form = (new Application_Form_Task())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'tasks','action' => 'change-status'),null,true));
        $id = $this->_getParam('id');
        $taskName = (new Application_Model_DbTable_Tasks())->getTaskNameById($id);
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id, 'changeStatus'=>true],'confirmButton'=>'Change','closeButton'=>'Close',
            'modalView'=>'tasks/add',
            'modalTitle'=>'Change status of ' .$taskName . ' task','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);

        if ($this->getRequest()->isPost())
        {
            $formData = $this->getRequest()->getPost();
            $form->populate($formData);
            $task_id = $form->getValue('id');
            $id_status = $form->getValue('id_status');
            $status = (new Application_Model_DbTable_Status())->getStatusNameById($id_status);
            $data = ['id_status'=>$id_status];
            if(strtolower($status)=='done')
            {
                $data['complete_date'] =date("Y-m-d");
            }
            $tasks = new Application_Model_DbTable_Tasks();
            $tasks->editTask($task_id, $data);
            $this->_helper->redirector('index');
        }
        else
        {
            $tasks = new Application_Model_DbTable_Tasks();
            $form->populate($tasks->getTask($id));
        }
        $this->_helper->renderHelper->renderModalView();

    }   


}







