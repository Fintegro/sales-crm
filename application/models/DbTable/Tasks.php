<?php

class Application_Model_DbTable_Tasks extends Zend_Db_Table_Abstract
{

    protected $_name = 'tasks';

    function fetchTasks()
    {
        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);
        $tasks = [];
        foreach($result as $res)
        {
            $task = [];
            $task['name'] = $res['name'];
            $task['project'] = (new Application_Model_DbTable_Projects())->getProjectNameById($res['id_project']);
            $task['status'] = (new Application_Model_DbTable_Status())->getStatusNameById($res['id_status']);
            $task['complete_date'] = ($res['complete_date']?(new Application_Model_DbTable_CurrencyExchange())->convertDate($res['complete_date']):$res['complete_date']);
            $task['id'] = $res['id'];
            $tasks[] = $task;
        }
        return $tasks;
    }
	
	public function getDevelopers($id_task)
	{
		$sql = 'SELECT CONCAT(programmists.firstName,\' \', programmists.lastName) FROM programmists, inprogress WHERE inprogress.id_task = 2 AND programmists.id = inprogress.id_programmist';
		$result = $this->_db->fetchAll($sql, $id_task);
		return $result;
	}

	public function getTasksList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'name'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	
	public function getTaskNameById($id)
	{
		$sql = 'SELECT name FROM tasks WHERE id = ?';
		$result = $this->_db->fetchOne($sql, $id);
		return $result;
	}
    
    public function getTask($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addTask($name, $id_project, $id_status)
    {
        $data = array(
            'name' => $name,
            'id_project' => $id_project,
			'id_status' => $id_status,
        );
        $this->insert($data);
    }

    public function editTask($id,$data)
    {
        if((new Application_Model_DbTable_InProgress())->isTaskDone($id))
        {
            $data['complete_date'] = null;
        }
        else if((int)$data['id_status'] == 5)
        {
            $data['complete_date'] = date("Y-m-d");
        }
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteTask($id)
    {
        $this->delete('id =' . (int)$id);
    }
    
    function getNotDoneTasks()
    {
        $allSoldTasks = (new Application_Model_DbTable_SoldTasks())->getSoldTasksList();
        $notDoneTasks = [];
        foreach ($allSoldTasks as $task)
        {
            $t=$this->getTask($task['value']);
            if(strtolower((new Application_Model_DbTable_Status())->getStatusNameById($t['id_status']))!='done')
            {
                $notDoneTasks[]=['key'=>$t['id'],'value'=>$t['name']];
            }
        }
        return $notDoneTasks;
    }
    function getIdTaskByName($name)
    {
        $select = $this->_db->select()->from($this->_name,['id'])->where('name=?',$name);
        $result = $this->getAdapter()->fetchOne($select);
        return $result;
    }
    

}

