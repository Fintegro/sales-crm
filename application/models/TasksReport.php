<?php


class Application_Model_TasksReport
{
    function getTasksReport()
    {

        $select = Zend_Db_Table::getDefaultAdapter()->select()->from('soldtasks',['id_task','rate','rate_curr', 'hours']);
        $result = Zend_Db_Table::getDefaultAdapter()->fetchAll($select);
        $tasks=[];
        foreach($result as $res)
        {
            $tasksTable = new Application_Model_DbTable_Tasks();
            $task=[];
            $task['name']= $tasksTable->getTaskNameById($res['id_task']);
            $task['project'] = (new Application_Model_DbTable_Projects())->getProjectNameById($tasksTable->getTask($res['id_task'])['id_project']);
            $task['rate'] = $res['rate'];
            $task['hours'] = $res['hours'];
            $task['sum'] = $task['rate'] * $task['hours'];
            $currency = new Application_Model_DbTable_Currency();
            $task['sale_curr'] = $currency->getCurrencyNameById($res['rate_curr']);
            $expense = $this->getExpense($res['id_task']);
            $task['expence_hours'] = $expense['hours'];
            $task['total'] = $expense['total'];
            $task['expence_curr'] = $expense['expence_curr'];
            $task['task_rate'] = /*$this->getEffectiveTaskRate()*/($task['expence_hours'] == 0?0:(round($task['sum']/$task['expence_hours'],2)));
            /*$task['project_rate'] = $this->getEffectiveProjectRate();*/
            $task['balance'] = 0;
            $tasks[]=$task;
        }        
        return $tasks;
    }

    private function getExpense($task_id)
    {
        $select = Zend_Db_Table::getDefaultAdapter()->select()->from('inprogress',['id_programmist','hours'])->where('id_task=?', $task_id);
        $in_prog = Zend_Db_Table::getDefaultAdapter()->fetchAll($select);
        $select = Zend_Db_Table::getDefaultAdapter()->select()->from('programmists',['id','effective_rate', 'ts_curr']);
        $programmers =Zend_Db_Table::getDefaultAdapter()->fetchAll($select);
        $hours=0;
        $total=0;
        foreach($in_prog as $val)
        {
            $hours += floatval($val['hours']);
            $total += floatval($val['hours'])* floatval($this->findProgrammerRate($val['id_programmist'],$programmers));
        }
       return ['hours'=>$hours, 'total'=>$total, 'expence_curr'=> (new Application_Model_DbTable_Currency())->getCurrencyNameById($programmers[0]['ts_curr'])];
    }

    private function findProgrammerRate($id_programmer, array $programmers)
    {
        $rate = 0;
        foreach($programmers as $programmer)
        {
            if($programmer['id'] == $id_programmer)
            {
                $rate = $programmer['effective_rate'];
                break;
            }
        }
        return $rate;
    }
    
    function processFiltering(array $filters)
    {
        
        $filter = new Application_Model_Filter();
        $filters = $filter->unsetOptions($filters);
        $tableData = $this->getTasksReport();
        if(!empty($filters))
        {
            
            foreach($filters as $key => $value)
            {
                switch($key)
                {
                    case 'currency':
                        $tableData = $filter->filterByCurrency($tableData,(int)$value,
                            ['sale_curr'=>['rate','sum'],'expence_curr'=>['total']]);
                        break;
                    case 'balance':
                        $tableData = $filter->filterByBalance($tableData,$value,'balance');
                        break;
                }
            }
        }
        return $tableData;
    }



    function getEffectiveProjectRate($id_project)
    {
        /*$select = $select = Zend_Db_Table::getDefaultAdapter()->select()->from('soldtasks',['id_task','rate','rate_curr', 'hours']);
        $result = Zend_Db_Table::getDefaultAdapter()->fetchAll($select); */
        $tasks = $this->getTasksReport();
        $taskName = (new Application_Model_DbTable_Projects())->getProjectNameById($id_project);
        $total = 0;
        $tasksCount = 0;
        foreach ($tasks as $task)
        {
            if($task['project'] == $taskName)
            {
                $tasksCount++;
                $total += floatval($task['task_rate']);
            }
        }
        if($total!=0)
        {
           $total = round($total/$tasksCount,2);
        }
        return $total;
    }

    
}