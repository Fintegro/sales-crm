<?php

class ProjectsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {     
        $data=['title'=>'Projects list','addButton'=>'Add new project', 'addUrl'=>['controller'=>'projects', 'action'=>'add'],
            'columns'=>['Project name','Code','Client','Notes'],
            'rows'=>(new Application_Model_DbTable_Projects())->fetchAll(),
            'edits'=>['edit'=>['controller'=>'projects','action'=>'edit'],
                'delete'=>['controller'=>'projects','action'=>'delete']],'filter'=>true];
        $this->_helper->layout->disableLayout(); 
        $this->_helper->renderHelper->setDataForListTable($data);

    }

    public function addAction()
    {

        $form = (new Application_Form_Project())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'projects','action' => 'add'),null,true)) ;
        $viewData=['modalData'=>['form'=>$form,'urlClient'=>['controller'=>'clients', 'action'=>'add']],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'projects/add',
            'modalTitle'=>'Add new project','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        $this->view->edit = false;
        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $name = $form->getValue('name');
                $code = $form->getValue('code');
				$id_client = $form->getValue('id_client');
				$note = $form->getValue('note');
				
                $projects = new Application_Model_DbTable_Projects();
                $projects->addProject($name, $code, $id_client, $note);
                
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
        $form = (new Application_Form_Project())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'projects','action' => 'edit'),null,true));
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id,'urlClient'=>['controller'=>'clients', 'action'=>'add']],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'projects/add',
            'modalTitle'=>'Edit project','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        $this->view->edit = true;
        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
				$id = (int)$form->getValue('id');
                $name = $form->getValue('name');
                $code = $form->getValue('code');
				$id_client = $form->getValue('id_client');
				$note = $form->getValue('note');
                $projects = new Application_Model_DbTable_Projects();
                $projects->editProject($id, $name, $code, $id_client, $note);
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{
            $projects = new Application_Model_DbTable_Projects();
            $form->populate($projects->getProject($id));
        }
        $this->_helper->renderHelper->renderModalView();

    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) 
		{

                $id = $this->getRequest()->getPost('id');
                $projects = new Application_Model_DbTable_Projects();
                $projects->deleteProject($id);

            $this->_helper->redirector('index');
        } 
		else
		{
            $id = $this->_getParam('id');
            $projects = new Application_Model_DbTable_Projects();
            $project = $projects->getProject($id);            
            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete  '.$project['name'].'?','id'=>$id,
                'url'=>['controller'=>'projects', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete project','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }


}







