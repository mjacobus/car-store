<?php

/**
 * Form for Category
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Brand extends Admin_Form_Abstract
{

    /**
     * Constructor
     */
    public function __construct($id = null, $options = null)
    {
        parent::__construct($options);
        $this->addName();
        $this->addSubmit();
    }

    /**
     * Add Name wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Category
     */
    public function addName()
    {
        $this->addElement($this->getTextElement('name', 'Nome'));
        return $this;
    }

}

