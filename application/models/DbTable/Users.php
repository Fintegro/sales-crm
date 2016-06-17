<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    function fetchUsers()
    {

        $sql = 'SELECT name FROM roles WHERE id = ?';
        // $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $select  = $this->_db->select()->from($this->_name);
        $rowSet = $this->getAdapter()->fetchAll($select);
        $users=[];
        foreach($rowSet as $row)
        {
            $user = [];
            $user['username'] = $row['username'];
            $user['role']=$this->getAdapter()->fetchOne($sql, $row['role_id']);
            $user['id'] = $row['id'];
            $users[] = $user;
        }
        return $users;
    }
    public function getUser($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
        {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addUser($login, $password, $id_role)
    {
        $data = array(
            'username' => $login,
            'password' => $password,
            'role_id' => $id_role,
        );
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $col=[];
        foreach(array_keys($data) as $key)
        {
            $col[]=$db->quoteIdentifier($key,true);
        }
        $placeholders=['?','SHA1(?)','?'];
        $sql = "INSERT INTO "
            . $db->quoteIdentifier($this->_name, true)
            . ' (' . implode(', ', $col ). ') '
            . 'VALUES (' . implode(', ', $placeholders) . ')';
        $db->query($sql,array_values($data));

    }

    public function editUser($id, $login, /*$password*/ $id_role)
    {
        $data = array(
            'username' => $login,
            /*'password' => $password,*/
            'role_id' => $id_role,
        );

        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteUser($id)
    {
        $this->delete('id =' . (int)$id);
    }

}

