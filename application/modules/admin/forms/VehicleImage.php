<?php

/**
 * Form for Vehicle
 *
 * @author marcelo.jacobus
 */
class Admin_Form_VehicleImage extends App_Form
{

    protected $_availableYears = array();

    /**
     * Constructor
     */
    public function __construct(array $params = null, $options = null)
    {
        parent::__construct($options);
        $this->addVehicleId($params);
        $this->addImage();
        $this->addIlustrative();
        $this->addAlt();
        $this->addTitle();
        $this->addDescription();
        $this->addPriority();
        $this->addSubmit();
    }

    /**
     * Add Image wich is a Zend_Form_Element_Select
     * length 255
     * @return Admin_Form_VehicleImage
     */
    public function addImage()
    {
        $element = new MyZend_Form_Element_DoctrineSelect('image_id');
        $element->setLabel('Image')
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

    /**
     * Add Illustrative checkbox wich is a Zend_Form_Element_Checkbox
     * length 255
     * @return Admin_Form_VehicleImage
     */
    public function addIlustrative()
    {
        $element = $this->getCheckElement('illustrative', 'Foto Ilustrativa');
        $this->addElement($element);
        return $this;
    }

    /**
     * Add VehicleId wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Vehicle
     */
    public function addVehicleId(array $params = null)
    {
        $element = $this->getHiddenElement('vehicle_id');
        if (array_key_exists('vehicle', $params)) {
            $element->setValue($params['vehicle']);
        }
        $this->addElement($element);
        return $this;
    }
    
    /**
     * Add Description wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Vehicle
     */
    public function addDescription()
    {
        $this->addElement($this->getTextElement('description', 'Descrição'));
        return $this;
    }

    /**
     * Add Alternative text wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Vehicle
     */
    public function addAlt()
    {
        $this->addElement($this->getTextElement('alt', 'Texto alternativo'));
        return $this;
    }

    /**
     * Add Title text wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Vehicle
     */
    public function addTitle()
    {
        $this->addElement($this->getTextElement('title', 'Título'));
        return $this;
    }

    /**
     * Add priority
     * @return Admin_Form_Vehicle
     */
    public function addPriority()
    {
        $element = $this->getTextElement('priority', 'Ordem');
        $element->addValidator(new Zend_Validate_Int());
        $element->addValidator(new Zend_Validate_Between(0,500));
        $this->addElement($element);
        return $this;
    }
    

}

