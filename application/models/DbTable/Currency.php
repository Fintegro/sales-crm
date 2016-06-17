<?php

class Application_Model_DbTable_Currency extends Zend_Db_Table_Abstract
{

    protected $_name = 'currency';

    public function fetchAll()
    {
       return $this->getCurrencyList();
    }

    public function getCurrencyList()
	{
		$select  = $this->_db->select()->from($this->_name);
		$result = $this->getAdapter()->fetchAll($select);
        $currency= [];
        foreach($result as $res)
        {
            $cur = [];
            $cur['id']=$res['id'];
            $cur['iso_code'] = $res['ISO_code'];
            $cur['last'] = '';
            $currency[] = $cur;
        }
        return $currency;
	}
	public function getCodes()
    {

        $select  = $this->_db->select()->from($this->_name,['key' => 'id','value' => 'ISO_code']);
		return $this->getAdapter()->fetchAll($select);
    }
	public function getCurrencyNameById($id_currency)
	{
		$sql = 'SELECT ISO_code FROM currency WHERE id = ?';
		$result = $this->_db->fetchOne($sql, $id_currency);
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

