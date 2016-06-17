<?php


class ProjectsReportController extends Zend_Controller_Action
{
    function indexAction()
    {

        $this->renderTable((new Application_Model_ProjectBalance())->getBalanceByProjects());

    }

    function filterAction()
    {
        $formData = $this->getRequest()->getPost();
        unset($formData['submit']);
        $this->renderTable((new Application_Model_ProjectBalance())->processFiltering($formData),$formData);
    }




    private function renderTable(array $rows, array $formData = [])
    {
        $data=['title'=>'Balance by project',
            'tableData'=>['columns'=>['Project','Total Sale', 'Total Payments Received', 'Commissions','Balance','Effective rate for the Project','Currency','Client'],
                'rows'=>$rows],
            'filterForm'=>(new Application_Form_ProjectFilter())->populate($formData)];
        $this->_helper->layout->disableLayout();
        $this->view->data = $data;
        $this->renderScript('partials/reportindex.phtml');
    }
}