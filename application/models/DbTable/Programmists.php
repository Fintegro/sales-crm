<?php

class Application_Model_DbTable_Programmists extends Zend_Db_Table_Abstract
{

    protected $_name = 'programmists';

    public function fetchProgrammists()
    {
        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);
        $employees= [];
        foreach($result as $res)
        {
            $emp = [];
            $emp['name']=$res['firstName'];
            $emp['lastName'] = $res['lastName'];
            $emp['salary'] = $res['price'];
            $emp['salaryCur'] = (new Application_Model_DbTable_Currency())->getCurrencyNameById($res['price_curr']);
            $emp['hours'] = $res['workHrs'];
            $emp['rate'] = $res['effective_rate'];
            $emp['target_sale'] = $emp['hours']*$emp['rate'];
            $emp['targetSaleCurr'] = (new Application_Model_DbTable_Currency())->getCurrencyNameById($res['ts_curr']);
            $emp['id'] = $res['id'];
            $employees[] = $emp;
        }
        return $employees;
    }

    public function getProgrammistsList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'CONCAT(firstName,\' \', lastName)'));
        $result = $this->getAdapter()->fetchAll($select);
        return $result;

	}
	
	public function getProgrammistNameById($id_programmist)
	{
		$sql = 'SELECT CONCAT(firstName,\' \', lastName) FROM programmists WHERE id = ?';
		$result = $this->_db->fetchOne($sql, $id_programmist);
		return $result;
	}

    public function getPerson($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addPerson($firstName, $lastName, $price, $price_curr, $workHrs, $targetSale, $ts_curr)
    {
        $data = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
			'price' => $price,
			'price_curr' => $price_curr,
			'workHrs' => $workHrs,
			'effective_rate' => $targetSale,
			'ts_curr' => $ts_curr,
        );
        $this->insert($data);
    }

    public function editPerson($id, $firstName, $lastName, $price, $price_curr, $workHrs, $targetSale, $ts_curr)
    {
        $data = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
			'price' => $price,
			'price_curr' => $price_curr,
			'workHrs' => $workHrs,
			'effective_rate' => $targetSale,
			'ts_curr' => $ts_curr,
        );
        $this->update($data, 'id = '. (int)$id);
    }

    public function deletePerson($id)
    {
        $this->delete('id =' . (int)$id);
    }
    function getProgrammerIdByName($name)
    {
        $programmers = $this->getProgrammistsList();
        foreach($programmers as $programmer)
        {
            if($programmer['value'] == $name)
            {
                return $programmer['key'];
            }
        }
        return null;
    }

}

