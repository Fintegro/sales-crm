<?php


class PaymentsController extends Zend_Controller_Action
{
    function indexAction()
    {
        $data=['title'=>'Received Payments','addButton'=>'Add new payment', 'addUrl'=>['controller'=>'payments', 'action'=>'add'],
            'columns'=>['Date','Task','Amount','Amount currency', 'Comissions', 'Comission currency'],
            'rows'=>(new Application_Model_DbTable_Payments())->getPayments(),
            'edits'=>['edit'=>['controller'=>'payments','action'=>'edit'],
                'delete'=>['controller'=>'payments','action'=>'delete']],'filter'=>true];
        $this->_helper->layout->disableLayout();
        $this->_helper->renderHelper->setDataForListTable($data);
    }

    function addAction()
    {

        $form= (new Application_Form_Payments())->setAction((new Zend_View_Helper_Url())->url(['controller'=>'payments', 'action'=>'add'],null,true));
        $viewData=['modalData'=>['form'=>$form],'confirmButton'=>'Add','closeButton'=>'Close','modalView'=>'payments/add',
        'modalTitle'=>'Add new payment','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
        {
            $formData = $this->getRequest()->getPost();
            var_dump($form->isValid($formData));
            if ($form->isValid($formData))
            {

                $data['date'] = (new Application_Model_DbTable_CurrencyExchange())->getCorrectFormat($form->getValue('date'));
                $data['id_task'] = $form->getValue('id_task');
                $data['summ'] = $form->getValue('summ');
                $data['summ_curr'] = $form->getValue('summ_curr');
                $data['commisions'] = $form->getValue('commisions');
                $data['comm_curr'] =$form->getValue('comm_curr');
                $payments = new Application_Model_DbTable_Payments();
                $payments->addPayment($data);
                $this->_helper->redirector('index');
            }
            else
            {
                $form->populate($formData);
            }
        }
        $this->_helper->renderHelper->renderModalView();
    }

    function editAction()
    {

        $form = ((new Application_Form_Payments())
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'payments','action' => 'edit'),null,true)) );
        $id = $this->_getParam('id');
        $viewData=['modalData'=>['form'=>$form, 'id'=>$id],'confirmButton'=>'Edit','closeButton'=>'Close','modalView'=>'payments/add',
            'modalTitle'=>'Edit payment','form'=>'modalForm'];
        $this->_helper->renderHelper->setModalData($viewData);
        if ($this->getRequest()->isPost())
        {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData))
            {

                $data = [];
                $data['date'] = (new Application_Model_DbTable_CurrencyExchange())->getCorrectFormat($form->getValue('date'));
                $data['id_task'] = $form->getValue('id_task');
                $data['summ'] = $form->getValue('summ');
                $data['summ_curr'] = $form->getValue('summ_curr');
                $data['commisions'] = $form->getValue('commisions');
                $data['comm_curr'] = $form->getValue('comm_curr');
                $data['id'] = $form->getValue('id');

                $payments = new Application_Model_DbTable_Payments();
                $payments->editPayment($data,$form->getValue('id'));
                $this->_helper->redirector('index');
            }
            else
            {
                $form->populate($formData);
            }
        }
        else
        {

            $payments = new Application_Model_DbTable_Payments();
            $payment = $payments->getPayment($id);
            $payment['date'] = (new Application_Model_DbTable_CurrencyExchange())->convertDate($payment['date']);
            $form->populate($payment);
        }
        $this->_helper->renderHelper->renderModalView();
    }

    function deleteAction()
    {

        if ($this->getRequest()->isPost())
        {
            $id = $this->getRequest()->getPost('id');
            $payments = new Application_Model_DbTable_Payments();
            $payments->deletePayment($id);
            $this->_helper->redirector('index');
        }
        else
        {
            $id = $this->_getParam('id');
            $tasks = new Application_Model_DbTable_Tasks();
            $payments = new Application_Model_DbTable_Payments();
            $payment = $payments->getPayment($id);
            $task = $tasks->getTask($payment['id_task']);
            /*$payment = (new Application_Model_DbTable_Payments())->getPayment($id);
            $projects = new Application_Model_DbTable_Tasks();
            $project = $projects->getTaskNameById($payment['id_task']);*/
            $viewData=['modalData'=>['confirmMessage'=>'Are you sure that you want to delete  payment for the task '.$task['name'] .'?','id'=>$id,
                'url'=>['controller'=>'payments', 'action'=>'delete']],'confirmButton'=>'Yes','closeButton'=>'No','modalView'=>'partials/confirmdelete',
                'modalTitle'=>'Delete payment','form'=>'delete'];
            $this->_helper->renderHelper->setModalData($viewData);
            $this->_helper->renderHelper->renderModalView();

           /* $data = ['title'=>'Delete payment','confirmMessage'=>'Are you sure that you want to delete  payment for the task '.$task .'?',
                'url'=>['controller'=>'payments', 'action'=>'delete'],'id'=>$id];
            $this->_helper->renderHelper->setDataForDelete($data);
            $this->renderScript('partials/confirmdelete.phtml');*/
        }
    }
}