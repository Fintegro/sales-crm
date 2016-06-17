<?php


class Application_Model_Filter
{
    private $_db;
    private $currencies;

    function __construct()
    {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
        $this->currencies = (new Application_Model_DbTable_Currency())->getCodes();
    }

    function filterByClients(array $data, $condition, $columnPosition)
    {

        $selectedData = [];
        for($i=0; $i<count($data); $i++)
        {
            if($data[$i][count($data[$i])-$columnPosition]==$condition)
            {
                $selectedData[]=$data[$i];
            }
        }
        return $selectedData;
    }
    function filterByBalance(array $data, $condition, $offset)
    {
        $condition = (int)$condition;
        $selectedData = [];
        if($condition==2)
        {
            for($i=0; $i<count($data); $i++)
            {
                if($data[$i][$offset]<0)
                {
                    $selectedData[]=$data[$i];
                }
            }
        }
        else
        {
            for($i=0; $i<count($data); $i++)
            {
                if($data[$i][$offset]>=0)
                {
                    $selectedData[]=$data[$i];
                }
            }
        }
        return $selectedData;
    }

    function filterByCurrency(array $data, $condition, array $change_columns)
    {
        for($i=0; $i<count($data); $i++)
        {
            foreach($change_columns as $key=>$value)
            {                
                if(!empty($currencyEx = $this->getRate($this->getCurrencyId($data[$i][$key]),$condition, $this->getTaskDate($data[$i]['name']))))
                {

                    if($currencyEx['multiply'])
                    {
                        foreach($value as $val)
                        {
                            $data[$i][$val] = round((int)$data[$i][$val]*$currencyEx['rate'],2);
                        }
                    }
                    else
                    {
                        foreach($value as $val)
                        {
                            $data[$i][$val] = round((int)$data[$i][$val]/$currencyEx['rate'],2);
                        }
                    }

                    $data[$i][$key] = $this->getCurrencyName($condition);
                }
            }
        }
        return $data;
    }

    function getRate($firstCurrency, $secondCurrency, $date)
    {
        $select = $this->_db->select()->from('currencyex')->where('id_currency_first=?',$secondCurrency)->where('id_currency_second=?',$firstCurrency)->where('date <= ?',$date)->order('date DESC');
        $secondCurrencyEx = $this->_db->fetchRow($select);
        $select = $this->_db->select()->from('currencyex')->where('id_currency_second=?',$secondCurrency)->where('id_currency_first=?',$firstCurrency)->where('date <= ?',$date)->order('date DESC');
        $firstCurrencyEx = $this->_db->fetchRow($select);//
        return (!empty($firstCurrencyEx)?(!empty($secondCurrencyEx)?($firstCurrencyEx['date']>$secondCurrencyEx['date']?['rate'=>floatval($firstCurrencyEx['current_rate']),'multiply'=>true]:['rate'=>floatval($secondCurrencyEx['current_rate']),'multiply'=>false]):['rate'=>floatval($firstCurrencyEx['current_rate']),'multiply'=>true]):
            (empty($secondCurrencyEx)?[]:['rate'=>floatval($secondCurrencyEx['current_rate']),'multiply'=>false]));

    }

    private function getCurrencyName($id)
    {
        $res='';
        foreach($this->currencies as $curr)
        {
            if((int)$curr['key']==$id)
            {
                $res = $curr['value'];
                break;
            }
        }
        return $res;
    }

    private function getCurrencyId($name)
    {
        $res='';
        foreach($this->currencies as $curr)
        {
            if($curr['value']==$name)
            {
                $res = $curr['key'];
                break;
            }
        }
        return $res;
    }

    function filter(array $data, $condition,$filter_column)
    {
        
        $newData = [];
        foreach ($data as $row)
        {            
            if($row[$filter_column] == $condition)
            {
                $newData[]=$row;
            }
        }
        return $newData;
    }

    function filterByDate(array $data, array $date, $filter_column)
    {
        $newData = [];
        $dateConverter = new Application_Model_DbTable_CurrencyExchange();
        //$date = strtotime($dateConverter->getCorrectFormat($date));
        foreach ($data as $row)
        {
            foreach($date as $d)
            {
                $d = strtotime($dateConverter->getCorrectFormat($d));
                if(strtotime($dateConverter->getCorrectFormat($row[$filter_column])) == $d)
                {
                    $newData[]=$row;
                }
            }

        }
        return $newData;
    }
    
    public function unsetOptions(array $filters)
    {
        foreach ($filters as $key=>$value)
        {

            if ((!is_array($value))&& $value === '0')
            {
                unset($filters[$key]);
            }
            else if(is_array($value))
            {

                if(in_array('0',$value))
                {
                    unset($filters[$key][array_search('0',$filters[$key])]);

                    if(empty($filter[$key]))
                    {
                        unset($filters[$key]);
                    }
                }
                
            }
        }
        return $filters;
    }

    private function getTaskDate($task)
    {
        $select = $this->_db->select()->from('soldtasks',['date'])->where('id_task = ?', (new Application_Model_DbTable_Tasks())->getIdTaskByName($task));
        $result = $this->_db->fetchRow($select);
        return $result['date'];
    }
    


}