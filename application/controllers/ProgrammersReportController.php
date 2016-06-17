<?php

class ProgrammersReportController extends Zend_Controller_Action
{
    function summaryAction()
    {

    }
    
    function monthlyAction()
    {
        $data=['title'=>'Programmers Monthly Report',
            'tableData'=>['columns'=>['Programmer','Hours done this month','Effective rate','% of reaching the plan', '% of reaching the minimum plan'],
                'rows'=>(new Application_Model_ProgrammersReport())->getMonthlyReport()],
            'filterForm'=>''];
        $this->_helper->layout->disableLayout();
        $this->view->data = $data;
        $this->renderScript('partials/reportindex.phtml');
    }    
    
}