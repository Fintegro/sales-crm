<?php

class Application_Model_ProjectBalance
{
    private $_db;
    
    
    function __construct()
    {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
        $this->currencies = $currencies = (new Application_Model_DbTable_Currency())->getCodes();
    }




    public function getSoldTasksInCurrency($id_project, $second_curr, $id_project, $second_curr)
    {
        $summInCurr = 0;
        $sql = "SELECT soldtasks.rate, currencyex.current_rate
				FROM currencyex, projects, soldtasks, tasks
				WHERE currencyex.id_currency_first = soldtasks.rate_curr
					AND soldtasks.id_task = tasks.id
					AND tasks.id_project = projects.id
					AND projects.id = ?
					AND currencyex.id_currency_second = ?
					AND currencyex.date BETWEEN soldtasks.date AND (SELECT currencyex.date WHERE currencyex.id_currency_first = soldtasks.rate_curr AND currencyex.id_currency_second = ? AND currencyex.date>soldtasks.date LIMIT  1)
				GROUP BY soldtasks.id";
        $result = $this->_db->fetchAll($sql,$id_project, $second_curr, $id_project, $second_curr);
        foreach($result as $res)
        {
            $summInCurr += $res['rate']*$res['current_rate'];
        }
        return $summInCurr;
    }

    public function getPaymentsInCurrency($id_project, $second_curr, $id_project, $second_curr)
    {
        $summInCurr = 0;
        $sql = "SELECT  payments.summ, currencyex.current_rate
				FROM currencyex, projects, payments, tasks
				WHERE currencyex.id_currency_first = payments.summ_curr
					AND payments.id_task = tasks.id
					AND tasks.id_project = projects.id
					AND projects.id = ?
					AND currencyex.id_currency_second = ?
					AND currencyex.date BETWEEN payments.date AND (SELECT currencyex.date WHERE currencyex.id_currency_first = payments.rate_curr AND currencyex.id_currency_second = ? AND currencyex.date>payments.date LIMIT  1)
				GROUP BY payments.id";
        $result = $this->_db->fetchAll($sql,$id_project, $second_curr, $id_project, $second_curr);
        foreach($result as $res)
        {
            $summInCurr += $res['rate']*$res['current_rate'];
        }
        return $summInCurr;
    }

    /* Usefull functions*/
    public function getSoldTasksSumm($id)
    {
        $sql = 'SELECT SUM(soldtasks.rate*soldtasks.hours), soldtasks.rate_curr
				FROM tasks, projects, soldtasks
				WHERE tasks.id_project = projects.id
					AND soldtasks.id_task = tasks.id
					AND projects.id = ?';
        $result = $this->_db->fetchOne($sql, $id);
        return $result;
    }

    public function getPaymentsSumm($id)
    {
        $sql = 'SELECT SUM(payments.summ), payments.summ_curr, SUM(payments.commisions), payments.comm_curr
				FROM payments, tasks, projects
				WHERE tasks.id_project = projects.id
					AND payments.id_task = tasks.id
					AND projects.id = ?';
        $result = $this->_db->fetchRow($sql, $id);
        return $result;
    }
    function getBalanceByProjects()
    {
        $select = $this->_db->select()->from('projects',['id','id_client','code']);
        $result = $this->_db->fetchAll($select);
        $resultData=[];
        foreach($result as $res)
        {
            $data = [];
            $data[] = $res['code'];
            $data[] = floatval($this->getSoldTasksSumm($res['id']));
            $payments = array_values($this->getPaymentsSumm($res['id']));
            $data[] = floatval($payments[0]);
            $data[] = floatval($payments[2]);
            $data[] = $data[2] - $data[3];
            $data[] = (new Application_Model_TasksReport())->getEffectiveProjectRate($res['id']);
            $data[] = (new Application_Model_DbTable_Currency())->getCurrencyNameById($payments[1]);
            $data[] = (new Application_Model_DbTable_Clients())->getClientNameById($res['id_client']);
            $resultData[]=$data;
        }
        return $resultData;
    }

    function processFiltering(array $filters)
    {

        foreach ($filters as $key=>$value)
        {
            if ($value == 0)
            {
                unset($filters[$key]);
            }
        }

        $tableData = $this->getBalanceByProjects();        
        if(!empty($filters))
        {
            $filter = new Application_Model_Filter();
            foreach($filters as $key => $value)
            {
                switch($key)
                {
                    case 'currency':
                        $tableData = $filter->filterByCurrency($tableData,(int)$value,[6=>[1,2,3,4,5]]);
                        break;
                    case 'balance':
                        $tableData = $filter->filterByBalance($tableData,$value,4);
                        break;
                    case 'clients':
                        $tableData = $filter->filterByClients($tableData,(new Application_Model_DbTable_Clients())->getClientNameById($value),1);
                        break;
                }
            }
        }
        return $tableData;
    }


    

}