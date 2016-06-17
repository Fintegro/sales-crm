<?php


class Zend_Controller_Action_Helper_RenderHelper extends Zend_Controller_Action_Helper_Abstract
{
    private $view;

    public function init()
    {
        $this->view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
    }

    function setDataForListTable(array $data)
    {

        $this->view->title=$data['title'];
        $this->view->addButton = $data['addButton'];
        $this->view->addUrl= $data['addUrl'];
        $this->view->columns = $data['columns'];
        $this->view->rows=$data['rows'];
        $this->view->edits= $data['edits'];
    }
    function setDataForDelete(array $data)
    {
        $this->view->title=$data['title'];
        $this->view->confirmMessage = $data['confirmMessage'];
        $this->view->id = $data['id'];
        $this->view->url = $data['url'];
    }

    function setModalData($data)
    {
        $this->view->modalData =$data['modalData'];
        $this->view->confirmButton = $data['confirmButton'];
        $this->view->closeButton = $data['closeButton'];
        $this->view->modalView = $data['modalView'];
        $this->view->modalTitle = $data['modalTitle'];
        $this->view->form = $data['form'];
    }

    function renderModalView()
    {
        Zend_Controller_Action_HelperBroker::getExistingHelper('Layout')->disableLayout();
        Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer')->renderScript('partials/modalview.phtml');
    }
}