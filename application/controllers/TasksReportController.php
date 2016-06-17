<?php

class TasksReportController extends Zend_Controller_Action
{

    function indexAction()
    {

        $this->renderTable((new Application_Model_TasksReport())->getTasksReport());
    }

    function filterAction()
    {
        $formData = $this->getRequest()->getPost();
        unset($formData['submit']);

        $this->renderTable((new Application_Model_TasksReport())->processFiltering($formData),$formData);
    }

    private function renderTable(array $rows, $formData=[])
    {
        $form = (new Application_Form_ProjectFilter())->setAction((new Zend_View_Helper_Url())->url(array('controller'=>'tasks-report','action' => 'filter'),null,true));
        $form->removeElement('clients');
        $data=['title'=>'Tasks Report',
            'tableData'=>['columns'=>['Task','Project',['Total Sale'=>['Rate', 'Hours', 'Sum', 'Currency']],['Total Expenses'=>['Hours', 'Total', 'Currency']],
                'Effective rate for the task', 'Balance'],
                'rows'=>$rows],
            'filterForm'=>$form->populate($formData), 'formName'=>'tasks-report/filter'];
        $this->_helper->layout->disableLayout();
        $this->view->data = $data;
        $this->renderScript('tasks-report/index.phtml');
    }
}