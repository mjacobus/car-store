<?php

/**
 * Form for Vehicle
 *
 * @author marcelo.jacobus
 */
class Admin_Form_VehicleFeature extends Admin_Form_Abstract
{

    protected $_availableYears = array();

    /**
     * Constructor
     */
    public function __construct(array $params = null, $options = null)
    {
        parent::__construct($options);
        $this->addVehicleId($params);
        $this->addDescription();
        $this->addPriority();
        $this->addSubmit();
    }

    /**
     * Add Description wich is a Zend_Form_Element_Text
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
        $this->addElement($this->getTextElement('description', 'Descricão'));
        return $this;
    }

    /**
     * Add priority
     * @return Admin_Form_Vehicle
     */
    public function addPriority()
    {
        $element = $this->getTextElement('priority', 'Prioridade');
        $element->addValidator(new Zend_Validate_Int());
        $this->addElement($element);
        return $this;
    }

}

