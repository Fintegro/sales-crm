<?php

class Application_Model_DbTable_Clients extends Zend_Db_Table_Abstract
{

    protected $_name = 'clients';

    function fetchClients()
    {
        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);

        $clients =[];
        foreach($result as $res)
        {
            $client=[];
            $client['name'] = $res['name'];
            $client['country'] = (new Application_Model_DbTable_Countries())->getCountryNameById($res['id_country']);
            $client['email'] = $res['e_mail'];
            $client['phone'] = $res['phone'];
            $client['skype'] = $res['skype'];
            $client['note'] = $res['note'];
            $client['id'] = $res['id'];
            $clients[] = $client;

        }
        return $clients;
    }

	public function getClientsList()
	{
		$select  = $this->_db->select()->from($this->_name,array('key' => 'id','value' => 'name'));
		$result = $this->getAdapter()->fetchAll($select);
        return $result;

	}
	
	public function getClientNameById($id_client)
	{
		$sql = 'SELECT name FROM clients WHERE id = ?';
		$result = $this->_db->fetchOne($sql, $id_client);
		return $result;
	}

    public function getClient($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
		{
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addClient($name, $id_country, $note, $e_mail, $phone, $skype)
    {
        $data = array(
            'name' => $name,
            'id_country' => $id_country,
			'note' => $note,
			'e_mail' => $e_mail,
			'phone' => $phone,
			'skype' => $skype,
        );
        $this->insert($data);
    }

    public function editClient($id, $name, $id_country, $note, $e_mail, $phone, $skype)
    {
        $data = array(
            'name' => $name,
            'id_country' => $id_country,
			'note' => $note,
			'e_mail' => $e_mail,
			'phone' => $phone,
			'skype' => $skype,
        );
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteClient($id)
    {
        $this->delete('id =' . (int)$id);
    }

}

