<?php

class WorkDoneReportController extends Zend_Controller_Action
{
    function indexAction()
    {

        $this->renderTable((new Application_Model_WorkDone())->getDoneTasks());

    }

    function filterAction()
    {
        $formData = $this->getRequest()->getPost();
        unset($formData['submit']);        
        $this->renderTable((new Application_Model_WorkDone())->processFilter($formData),$formData);
        //$this->_helper->viewRenderer->setNoRender(true);

    }
    private function renderTable(array $rows, $formData=[])
    {
        $form = (new Application_Form_InProgress())->getFullForm()
            ->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'work-done-report','action' => 'filter'),null,true));
        $data=['title'=>'Work Done',
            'tableData'=>['columns'=>['Date','Task',['Programmer'=>['Name', 'Rate','Hours', 'Total']],'Project','Note'],
                'rows'=>$rows],
            'filterForm'=>$form->populate($formData), 'formName'=>'work-done/filter'];
        $this->_helper->layout->disableLayout();
        $this->view->data = $data;
        $this->renderScript('tasks-report/index.phtml');

    }

}