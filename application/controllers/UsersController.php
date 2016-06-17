<?php

class UsersController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $data=['title'=>'User list','addButton'=>'Add new user', 'addUrl'=>['controller'=>'users', 'action'=>'add'],
            'columns'=>['Login','Role'],
            'rows'=>(new Application_Model_DbTable_Users())->fetchUsers(),
            'edits'=>['edit'=>['controller'=>'users','action'=>'edit'],
                'delete'=>['controller'=>'users','action'=>'delete']],'filter'=>true];
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    public function addAction()
    {
        $form =( new Application_Form_User())
        ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'users','action' => 'add'),null,true));;
        $form->addPasswordField();
        $viewData=['modalData'=>['form'=>$form],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'users/add',
            'modalTitle'=>'Add new user','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);

        if ($this->getRequest()->isPost()) 
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{
                $login = $form->getValue('username');
                $password = $form->getValue('password');
				$id_role = $form->getValue('role_id');

                $users = new Application_Model_DbTable_Users();
                $users->addUser($login, $password, $id_role);
                
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
        $form = (new Application_Form_User())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'users','action' => 'edit'),null,true));
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'users/add',
            'modalTitle'=>'Edit user','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);

        if ($this->getRequest()->isPost())
		{
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
			{

                $id = (int)$form->getValue('id');
                $login = $form->getValue('username');
				$id_role = $form->getValue('role_id');
                $users = new Application_Model_DbTable_Users();
                $users->editUser($id, $login, $id_role);
                $this->_helper->redirector('index');
            } 
			else 
			{
                $form->populate($formData);
            }
        } 
		else
		{
            $users = new Application_Model_DbTable_Users();
            $user = $users->getUser($id);
            $form->populate($user);
        }
        $this->_helper->renderHelper->renderModalView();
    }
   public function deleteAction()
    {
        if ($this->getRequest()->isPost() )
		{
            
            $id = $this->getRequest()->getPost('id');
            $user = new Application_Model_DbTable_Users();
            $user->deleteUser($id);            
            $this->_helper->redirector('index');

            

        } 
		else
		{

            $id = $this->getRequest()->getParam('id');
            $users = new Application_Model_DbTable_Users();
            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete user '.$users->getUser($id)['username'].'?','id'=>$id,
                'url'=>['controller'=>'users', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete client','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();
        }
    }


}







