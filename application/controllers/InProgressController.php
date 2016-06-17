<?php

class InProgressController extends Zend_Controller_Action
{   

    public function indexAction()
    {
        $session = new Zend_Session_Namespace('table');
        if(empty($session->table))
        {
            $table = [];
            for($i=0;$i<10;$i++)
            {

                $table[]=$this->getTableRow($i);
            }
            $session->table = $table;
        }     
        
        $data=['title'=>'Work In Progress',
            'columns'=>['Date','Task','Hours','Programmer','Notes'], 
            'rows'=>$session->table, 'addButton'=>'Add row', 'addUrl'=>['controller'=>'in-progress', 'action'=>'add-row']
        ];
//        var_dump($data['rows']);
//        exit;
        $this->_helper->layout->disableLayout();
        $this->view->data = $data;
        //$this->_helper->viewRenderer->setNoRender(true);
    }

    function editCellAction()
    {
        $edit = $this->_getParam('edit');        
        $id = $this->_getParam('id');
        $formInput = $this->getInput($edit);
        $formInput->populate([$edit=>$this->getCellDataFromSession($id,$edit)]);
        $this->view->inputForm = $formInput;
        $this->view->partialForm = $this->getPartialForm($edit);
        $this->view->hiddens=[['name'=>'edit','value'=>$edit],['name'=>'id','value'=>$id]];
        if($this->getRequest()->isPost())
        {
            $formData = $this->getRequest()->getPost();
            
            (new Application_Model_DbTable_InProgress())->editCell($formData);
            
            //$this->_helper->layout->disableLayout();
            $this->_helper->_redirector('index');
        }             
        $this->renderScript('in-progress/editform.phtml');
        
    }
    
    private function getCellDataFromSession($id, $name)
    {
        $session = new Zend_Session_Namespace('table');
        $table = $session->table;
        
        foreach($table as $row)
        {
            if($row['id']==$id)
            {
                return $row['tableData'][$name];
            }
        }
        return null;
    }

    function addRowAction()
    {
        $session = new Zend_Session_Namespace('table');
        $table = $session->table;
        $table[] = $this->getTableRow((empty($session->table)?0:(int)($session->table[count($session->table)-1]['id'])+1));
        $session->table = $table;
        $this->_helper->_redirector('index');
    }
    private function getTableRow($id)
    {

        $tableData = ['date'=>date('d.m.Y'),
            'task'=>'',
            'hours'=>'',
            'programmer'=>'',
            'note'=>''];
        //$id = (empty($session->table)?0:(int)($session->table[count($session->table)-1]['id'])+1);
        return ['id'=>$id, 'tableData'=>$tableData, 'saved'=>0];
    }    

    private function getInput($input)
    {        
        $inProgress = new Application_Form_InProgress();
        $method = 'get'.ucfirst($input).'Input';
        $inProgress->$method();
        return $inProgress;
    }
    private function getPartialForm($edit)
    {
        return ['task'=>'select', 'programmer'=>'select', 'date'=>'date','hours'=>'text','note'=>'text'][$edit];
    }

}







