<?php

class Application_Model_DbTable_Projects extends Zend_Db_Table_Abstract
{

    protected $_name = 'projects';

    public function fetchAll()
    {
        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);
        $projects= [];
        foreach($result as $res)
        {
            $proj = [];
            $proj['name']=$res['name'];
            $proj['code'] = $res['code'];
            $proj['client'] = ((new Application_Model_DbTable_Clients())->getClientNameById($res['id_client']));
            $proj['note'] = $res['note'];
            $proj['id'] =$res['id'];
            $projects[] = $proj;
        }
        return $projects;
    }

	public function getProjectsList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'code'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}

    public function getProjectNameById($id_client)
    {
        $sql = 'SELECT code FROM projects WHERE id = ?';
        $result = $this->_db->fetchOne($sql, $id_client);
        return $result;
    }
	
    public function getProject($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addProject($name, $code, $id_client, $note)
    {
        $data = array(
            'name' => $name,
            'code' => $code,
			'id_client' => $id_client,
			'note' => $note,
        );
        $this->insert($data);
    }

    public function editProject($id, $name, $code, $id_client, $note)
    {
        $data = array(
            'name' => $name,
            'code' => $code,
			'id_client' => $id_client,
			'note' => $note,
        );
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteProject($id)
    {
        $this->delete('id =' . (int)$id);
    }
	
	

}

