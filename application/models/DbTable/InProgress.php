<?php

class Application_Model_DbTable_InProgress extends Zend_Db_Table_Abstract
{

    protected $_name = 'inprogress';
	
	public function getInProgressByTaskId($id_programmist)
	{		
		$sql = 'SELECT tasks.name FROM tasks, inprogress WHERE tasks.id = ?';
		$result = $this->_db->fetchOne($sql, $id_programmist);
		return $result;
	}
	public function getInProgressByProgrammistId($id_programmist)
	{
		$sql = 'SELECT CONCAT(programmists.firstName,\' \', programmists.lastName) FROM programmists, inprogress WHERE programmists.id = ?';
		$result = $this->_db->fetchOne($sql, $id_programmist);
		return $result;
	}	

    public function getInProgress($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        $inProgress = $row->toArray();
        $inProgress['date'] = (new Application_Model_DbTable_CurrencyExchange())->convertDate($inProgress['date']);
        return $inProgress;
    }
    function getWorksDone()
    {
        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);
        $works = [];
        foreach ($result as $res)
        {
            $work = [];
            $work[]=(new Application_Model_DbTable_CurrencyExchange())->convertDate($res['date']);
            $work[] = (new Application_Model_DbTable_Tasks())->getTaskNameById($res['id_task']);
            $work[] = $res['hours'];
            $work[] = (new Application_Model_DbTable_Programmists())->getProgrammistNameById($res['id_programmist']);
            $work[] = $res['note'];
            $work['id']=$res['id'];
            $works[] = $work;
        }
        return $works;
    }
    public function addInProgress($data)
    {
        $data['date'] = (new Application_Model_DbTable_CurrencyExchange())->getCorrectFormat($data['date']);
        return $this->insert($data);
    }

    public function editInProgress($id, $data)
    {
        $data['date'] = (new Application_Model_DbTable_CurrencyExchange())->getCorrectFormat($data['date']);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteInProgress($id)
    {
        $this->delete('id =' . (int)$id);
    }
    
    

    function editCell(array $formData)
    {
        $session = new Zend_Session_Namespace('table');
        $table = $session->table;
        foreach($table as &$row)
        {
            if($row['id']==$formData['id'])
            {

                $cell = (($formData['edit']=="task" || $formData['edit']=='programmer')?
                    $this->getForeignName(['id'=>$formData[$formData['edit']],'table'=>$formData['edit']]):$formData[$formData['edit']]);
                $row['tableData'][$formData['edit']]= $cell;
                if($formData['edit'] == 'date')
                {
                    $date = explode('.',$row['tableData']['date']);
                    $row['tableData']['date'] = "$date[2].$date[1].$date[0]";

                }              

                if(empty($row['saved']))
                {
                    $writingCells=0;
                    foreach($row['tableData'] as $key=>$value)
                    {
                        if($key!='note'&& !empty($value))
                        {
                            $writingCells++;
                        }
                    }
                    if($writingCells == 4)
                    {

                        $row['saved']=(new Application_Model_DbTable_InProgress())->addInProgress([
                            'id_task'=> (new Application_Model_DbTable_Tasks())->getIdTaskByName($row['tableData']['task']),
                            'id_programmist'=> (new Application_Model_DbTable_Programmists())->getProgrammerIdByName($row['tableData']['programmer']),
                            'date' => $row['tableData']['date'],
                            'hours' =>$row['tableData']['hours'],
                            'note' =>$row['tableData']['note']
                        ]);                       
                        if($this->isTaskDone((new Application_Model_DbTable_Tasks())->getIdTaskByName($row['tableData']['task'])))
                        {
                            (new Application_Model_DbTable_Tasks())->editTask((new Application_Model_DbTable_Tasks())->getIdTaskByName($row['tableData']['task']),['id_status'=>2,'complete_date'=>null]);
                            var_dump($formData['edit']);
                            exit;
                        }
                    }
                }
                else
                {
                    (new Application_Model_DbTable_InProgress())->editInProgress($row['saved'],[
                        'id_task'=> (new Application_Model_DbTable_Tasks())->getIdTaskByName($row['tableData']['task']),
                        'id_programmist'=> (new Application_Model_DbTable_Programmists())->getProgrammerIdByName($row['tableData']['programmer']),
                        'date' => $row['tableData']['date'],
                        'hours' =>$row['tableData']['hours'],
                        'note' =>$row['tableData']['note']
                    ]);                   
                    if($formData['edit']=='task'&& $this->isTaskDone((int)$formData[$formData['edit']]))
                    {
                        (new Application_Model_DbTable_Tasks())->editTask((new Application_Model_DbTable_Tasks())->getIdTaskByName($row['tableData']['task']),['id_status'=>2,'complete_date'=>null]);
                    }
                }
                $session->table = $table;
                break;
            }
        }
    }
    function isTaskDone($id_task)
    {
        $task = (new Application_Model_DbTable_Tasks())->getTask($id_task);        
        return (new Application_Model_DbTable_Status())->getStatusNameById($task['id_status']) == 'Done';
    }
    private function getForeignName(array $data)
    {
        switch($data['table'])
        {
            case 'task':
                return (new Application_Model_DbTable_Tasks())->getTaskNameById($data['id']);
            case 'programmer':
                return (new Application_Model_DbTable_Programmists())->getProgrammistNameById($data['id']);

        }
    }
    
    


}

