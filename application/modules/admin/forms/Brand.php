<?php

/**
 * Form for Brand
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Brand extends App_Form
{

    /**
     * Constructor
     */
    public function __construct($id = null, $options = null)
    {
        parent::__construct($options);
        $this->addName();
        $this->addLogo();
        $this->addSubmit();
    }

    /**
     * Add Name wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Brand
     */
    public function addName()
    {
        $this->addElement($this->getTextElement('name', 'Nome'));
        return $this;
    }

    /**
     * Add Logotipo wich is a Zend_Form_Element_Select
     * length 255
     * @return Admin_Form_Brand
     */
    public function addLogo()
    {
        $element = new MyZend_Form_Element_DoctrineSelect('image_id');
        $element->setLabel('Logotipo')
            ->addMultiOptionFromDql(
                Admin_Model_ImageUpload::getSelectDql(),
                'id',
                'filename',
                array(null => 'Selecione')
        );
        $this->setRequired($element);
        $this->addElement($element);
        return $this;
    }

}

