<?php

class Application_Model_DbTable_SoldTasks extends Zend_Db_Table_Abstract
{

    protected $_name = 'soldtasks';
	
	public function getSoldTasksList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'id_task'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;

	}

    function fetchSoldTasks()
    {
        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);
        $tasks = [];
        foreach($result as $res)
        {
            $task = [];
            $task['name'] = (new Application_Model_DbTable_Tasks())->getTaskNameById($res['id_task']);
            $task['date'] = (new Application_Model_DbTable_CurrencyExchange())->convertDate($res['date']);
            $task['rate'] = $res['rate'];
            $task['rate_curr'] =(new Application_Model_DbTable_Currency())->getCurrencyNameById($res['rate_curr']);
            $task['hours'] = $res['hours'];         
            $task['id'] = $res['id'];
            $tasks[] = $task;
        }
        return $tasks;
    }
    /*public function getSoldTaskNameById($id_client)
    {
        $sql = 'SELECT name FROM clients WHERE id = ?';
        $result = $this->_db->fetchOne($sql, $id_client);
        return $result;
    }*/

    public function getSoldTask($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        $soldTask = $row->toArray();
        $soldTask['date'] = (new Application_Model_DbTable_CurrencyExchange())->convertDate($soldTask['date']);
        return $soldTask;
    }

    public function addSoldTask($data)
    {
        $data['date'] = (new Application_Model_DbTable_CurrencyExchange())->getCorrectFormat($data['date']);
        $this->insert($data);
    }

    public function editSoldTask($data)
    {
        $data['date'] = (new Application_Model_DbTable_CurrencyExchange())->getCorrectFormat($data['date']);
        $this->update($data, 'id = '. (int)$data['id']);
    }

    public function deleteSoldTask($id)
    {
        $this->delete('id =' . (int)$id);
    }

    function getNotSoldTaskList()
    {
        $allTasks = (new Application_Model_DbTable_Tasks())->getTasksList();
        $soldTasks= $this->getSoldTasksList();
        $notSoldTasks = [];
        $allSoldTasksValues =[];
        foreach($soldTasks as  $task)
        {
            $allSoldTasksValues[]=$task['value'];
        }
        foreach($allTasks as $task)
        {
            if(!in_array($task['key'],$allSoldTasksValues))
            {
                $notSoldTasks[]=$task;
            }
        }
        return $notSoldTasks;
    }

}

