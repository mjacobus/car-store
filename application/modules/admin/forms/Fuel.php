<?php

/**
 * Form for Fuel
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Fuel extends Admin_Form_Abstract
{

    /**
     * Constructor
     */
    public function __construct($id = null, $options = null)
    {
        parent::__construct($options);
        $this->addName();
        $this->addShort();
        $this->addSubmit();
    }

    /**
     * Add Name wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Fuel
     */
    public function addName()
    {
        $this->addElement($this->getTextElement('name', 'Nome'));
        return $this;
    }
    /**
     * Add Name wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Fuel
     */
    public function addShort()
    {
        $element = $this->getTextElement('short', 'Abreviado');
        $element->addFilter(new Zend_Filter_StringToUpper('utf-8'));
        $this->addElement($element);
        return $this;
    }

}

