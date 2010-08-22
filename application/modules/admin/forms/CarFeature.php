<?php

/**
 * Form for Car
 *
 * @author marcelo.jacobus
 */
class Admin_Form_CarFeature extends Admin_Form_Abstract
{

    protected $_availableYears = array();

    /**
     * Constructor
     */
    public function __construct(array $params = null, $options = null)
    {
        parent::__construct($options);
        $this->addCarId($params);
        $this->addDescription();
        $this->addPriority();
        $this->addSubmit();
    }

    /**
     * Add Description wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Car
     */
    public function addCarId(array $params = null)
    {
        $element = $this->getHiddenElement('car_id');
        if (array_key_exists('car', $params)) {
            $element->setValue($params['car']);
        }
        $this->addElement($element);
        return $this;
    }
    
    /**
     * Add Description wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Car
     */
    public function addDescription()
    {
        $this->addElement($this->getTextElement('description', 'Descricão'));
        return $this;
    }

    /**
     * Add priority
     * @return Admin_Form_Car
     */
    public function addPriority()
    {
        $element = $this->getTextElement('priority', 'Prioridade');
        $element->addValidator(new Zend_Validate_Int());
        $this->addElement($element);
        return $this;
    }

}

