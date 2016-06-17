<?php

class Application_Model_Auth
{
    private $dbAdapter;
    private $authAdapter;
    private $auth;

    function isSuccessAuthentification(array $data)
    {
        $adapter = $this->getAuthAdapter();
        $adapter->setIdentity($data['username'])->setCredential($data['password']);
        $this->auth = Zend_Auth::getInstance();
        $result = $this->auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = (array)$adapter->getResultRowObject(['username','role_id']);
            $this->setUserRole($user);
            $this->auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    private function getAuthAdapter()
    {
        if(!$this->authAdapter)
        {
            $this->dbAdapter=Zend_Db_Table::getDefaultAdapter();

            $authAdapter = new Zend_Auth_Adapter_DbTable($this->dbAdapter);
            $authAdapter->setTableName('users')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')->setCredentialTreatment('SHA1(?)');
            $this->authAdapter=$authAdapter;
        }
        return $this->authAdapter;
    }
    private function setUserRole(array &$user)
    {
        $statement = (new Zend_Db_Select($this->dbAdapter))->from('roles',['name'])->where('id=?',$user['role_id']);
        $result = $this->dbAdapter->query($statement);
        $user['role'] = strtolower($result->fetch()['name']);

    }
    function getUserRole()
    {

        return $this->auth->getStorage()->read()['role'];
    }
}