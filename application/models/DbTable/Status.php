<?php

class Application_Model_DbTable_Status extends Zend_Db_Table_Abstract
{

    protected $_name = 'status';
	
	public function getStatusList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'name'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	
	public function getStatusNameById($id_status)
	{
		$sql = 'SELECT name FROM status WHERE id = ?';
		$result = $this->_db->fetchOne($sql, $id_status);
		return $result;
	}

    public function getCurrency($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addCurrency($ISO_code)
    {
        $data = array(
            'ISO_code' => $ISO_code,
        );
        $this->insert($data);
    }

    public function updateCurrency($id, $ISO_code)
    {
        $data = array(
            'ISO_code' => $ISO_code,
        );
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteCurrency($id)
    {
        $this->delete('id =' . (int)$id);
    }

}

