<?php

class Zend_Form_Decorator_Date extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element_Date) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $day   = $element->getDay();
        $month = $element->getMonth();
        $year  = $element->getYear();
        $name  = $element->getFullyQualifiedName();
 
        $params = array(
            'size'      => 2,
            'maxlength' => 2,
        );
        $yearParams = array(
            'size'      => 4,
            'maxlength' => 4,
        );
 
        $markup = $view->formText($name . '[day]', $day, $params)
                . ' / ' . $view->formText($name . '[month]', $month, $params)
                . ' / ' . $view->formText($name . '[year]', $year, $yearParams);
 
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}


?>