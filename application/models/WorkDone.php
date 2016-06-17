<?php

class Application_Model_WorkDone
{
    private $select;

    function __construct()
    {
        $this->select = Zend_Db_Table::getDefaultAdapter()->select();
    }
    function getDoneTasks()
    {
        $select = Zend_Db_Table::getDefaultAdapter()->select()->from('inprogress');
        $result = Zend_Db_Table::getDefaultAdapter()->fetchAll($select);
        $works=[];
        foreach($result as $res)
        {
            $work=[];
            $programmer = $this->getProgrammer($res['id_programmist']);
            $work['date'] = (new Application_Model_DbTable_CurrencyExchange())->convertDate($res['date']);
            $work['task'] = (new Application_Model_DbTable_Tasks())->getTaskNameById($res['id_task']);
            $work['name'] = $programmer['name'];
            $work['rate'] = $programmer['rate'];
            $work['hours'] = $res['hours'];            
            $work['total'] =  $work['hours'] * $work['rate'];
            $work['project'] = $this->getProject($res['id_task']);
            $work['note'] = $res['note'];
            $works[]=$work;
        }
        return $works;
    }
    
    function processFilter(array $filters)
    {

        $filter = new Application_Model_Filter();
        $filters = $filter->unsetOptions($filters);
        $tableData = $this->getDoneTasks();

        if(!empty($filters))
        {

            foreach($filters as $key => $value)
            {
                switch($key)
                {
                    case 'tasks':
                        $tableData = $filter->filter($tableData,(new Application_Model_DbTable_Tasks())->getTaskNameById((int)$value),'task');
                        break;
                    case 'id_programmist':
                        $tableData = $filter->filter($tableData,(new Application_Model_DbTable_Programmists())->getProgrammistNameById((int)$value),'name');
                        break;
                    default:
                        if(!empty($value))
                        {
                            $tableData = $filter->filterByDate($tableData, $value, 'date');
                        }

                }
            }
            $totalHours = 0;
            foreach ($tableData as $row)
            {
                $totalHours+=floatval($row['hours']);
            }
            $tableData[] = ['total_hours' =>$totalHours];
        }
        return $tableData;
    }

    private function getProject($id_task)
    {
        $project_id = Zend_Db_Table::getDefaultAdapter()->fetchRow(Zend_Db_Table::getDefaultAdapter()->select()->from('tasks')->where('id=?',$id_task))['id_project'];
        $result = Zend_Db_Table::getDefaultAdapter()->select()->from('projects',['name'])->where('id=?', $project_id);
        return Zend_Db_Table::getDefaultAdapter()->fetchOne($result);
    }
    private function getProgrammer($id_programmist)
    {
        $select = Zend_Db_Table::getDefaultAdapter()->select()->from('programmists')->where('id=?', $id_programmist);
        $res = Zend_Db_Table::getDefaultAdapter()->fetchRow($select);
        return ['rate'=> $res['effective_rate'], 'name'=> $res['firstName']. ' '. $res['lastName']];
    }
    function getDoneTasksList()
    {
        $select = Zend_Db_Table::getDefaultAdapter()->select()->from('inprogress');
        $tasks = Zend_Db_Table::getDefaultAdapter()->fetchAll($select);
        $tasksName = [];
        $tasksTable = new Application_Model_DbTable_Tasks();
        foreach ($tasks as $task)
        {
            $tasksName[] = ['key'=>$task['id_task'], 'value'=> $tasksTable->getTaskNameById($task['id_task'])];
        }
        return $tasksName;
    }
    function getDatesDoneWorks()
    {
        $allTasks = $this->getDoneTasks();
        $dates = [];
        foreach( $allTasks as $task)
        {           
            $dates[] = $task['date'];
        }
        $dates = $this->sortDates($dates);
        $selectDates = [];
        foreach($dates as $date)
        {
            $selectDates[] = ['key'=>$date, 'value'=>$date];
        }
        return array_merge([['key'=>0, 'value'=>'All Dates']],$selectDates);
    }
    private function sortDates(array $dates)
    {
        $format = new Application_Model_DbTable_CurrencyExchange();
        for($i=0;$i<count($dates); $i++)
        {
            $dates[$i] = strtotime($format->getCorrectFormat($dates[$i]));
        }
       $swapped = true;
        $z=0;
        while ($swapped)
        {
            $swapped = false;
            for ($i = 0; $i < count($dates)- 1-$z; $i++) {
                if ($dates[$i] > $dates[$i + 1]) {
                    $a = $dates[$i];
                 $dates[$i] = $dates[$i + 1];
                 $dates[$i + 1] = $a;
                 $swapped = true;
              }
            }
           $z++;
        }

        for($i=0;$i<count($dates); $i++)
        {
            $dates[$i]= date('d.m.Y',$dates[$i]);
        }
        return $dates;
    }
    
}