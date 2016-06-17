<?php
class Application_Model_DbTable_CurrencyExchange extends Application_Model_DbTable_Currency
{

    protected $_name = 'currencyex';

    public function fetchAll()
    {
         return $this->getCurrencyRateList();

    }

    function getCurrencyRateList()
    {
        $select  = (new Zend_Db_Select(self::getDefaultAdapter()))->from($this->_name,['id','date','id_currency_first','id_currency_second','current_rate'])
        ->order('date DESC');
        $result = self::getDefaultAdapter()->query($select);
        $currencyRate = [];
        foreach($result as $res)
        {
            $cur = [];
            $cur['date']=$this->convertDate($res['date']);
            $cur['first_currency'] = $this->getCurrencyNameById($res['id_currency_first']);
            $cur['second_currency'] = $this->getCurrencyNameById($res['id_currency_second']);
            $cur['current_rate']= $res['current_rate'];
            $cur['id']=$res['id'];
            $currencyRate[] = $cur;
        }
        return $currencyRate;
    }


     public function getCurrencyRate($id)
     {
         $id = (int)$id;
         $row = $this->fetchRow('id = ' . $id);
         if (!$row)
         {
             throw new Exception("Could not find row $id");
         }
         $rate = $row->toArray();
         $formattedRate = [];
         $formattedRate['date'] = $this->convertDate($rate['date']);
         $formattedRate ['first_currency'] = $rate['id_currency_first'];
         $formattedRate ['second_currency'] = $rate['id_currency_second'];
         $formattedRate['exchange_rate'] = $rate['current_rate'];

         return $formattedRate;
     }

    public function addCurrencyRate(array $data)
    {
        $data['date']=$this->getCorrectFormat($data['date']);
        $this->insert($data);
    }

    public function updateCurrencyRate($id, array $data)
    {

        $data['date'] = $this->getCorrectFormat($data['date']);
        $this->update($data, 'id = '. (int)$id);
    }

    public function deleteCurrencyRate($id)
    {
        $this->delete('id =' . (int)$id);
    }

    function getCorrectFormat($date)
    {
        $data = explode('.',$date);
        $convertDate = strlen($data[0])==4?"$data[0]-$data[1]-$data[2]":"$data[2]-$data[1]-$data[0]";
        return $convertDate;
    }

    function convertDate($date)
    {
        $parts = explode('-',$date);
        return "$parts[2].$parts[1].$parts[0]";
    }

}