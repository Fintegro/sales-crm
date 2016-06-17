<?php

class Application_Model_DbTable_Countries extends Zend_Db_Table_Abstract
{
    protected $_name = 'countries';

	public function getCountriesList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'name'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	
	public function getCountryNameById($id_country)
	{
		$sql = 'SELECT name FROM countries WHERE id = ?';
		$result = $this->_db->fetchOne($sql, $id_country);
		return $result;
	}

    public function getCountry($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addCountry($name)
    {
        $data = array(
            'name' => $name,
        );
        $this->insert($data);
    }

    public function updateCountry($id, $name)
    {
        $data = array(
            'name' => $name,
        );
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteCountry($id)
    {
        $this->delete('id =' . (int)$id);
    }

}

