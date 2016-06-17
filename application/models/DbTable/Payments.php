<?php

class Application_Model_DbTable_Payments extends Zend_Db_Table_Abstract
{
    protected $_name = 'payments';

    function getPayments()
    {
        $select  = (new Zend_Db_Select(self::getDefaultAdapter()))->from($this->_name)->order('date DESC');
        $result = self::getDefaultAdapter()->query($select);
        $payments  = [];
        foreach($result as $res)
        {
            $payment = [];
            $payment['date'] = (new Application_Model_DbTable_CurrencyExchange())->convertDate($res['date']);
            $payment['task'] = (new Application_Model_DbTable_Tasks())->getTaskNameById($res['id_task']);
            $payment['amount'] = $res['summ'];
            $payment['amount_currency'] = (new Application_Model_DbTable_Currency())->getCurrencyNameById($res['summ_curr']);
            $payment['commision'] = $res['commisions'];
            $payment['com_currency'] = (new Application_Model_DbTable_Currency())->getCurrencyNameById($res['comm_curr']);
            $payment['id'] = $res['id'];
            $payments[] = $payment;
        }
        return $payments;
    }
    function getPayment($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row)
        {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }
    
    function editPayment($data,$id)
    {
        $this->update($data, 'id = '. (int)$id);
    }
    function addPayment($data)
    {
        $this->insert($data);
    }

    function deletePayment($id)
    {
        $this->delete('id =' . (int)$id);
    }
}