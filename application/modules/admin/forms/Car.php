<?php

/**
 * Form for Car
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Car extends Admin_Form_Abstract
{

    protected $_availableYears = array();

    /**
     * Constructor
     */
    public function __construct($id = null, $options = null)
    {
        parent::__construct($options);

        $currentYear = ((int) date('Y') + 1);
        for ($i = $currentYear; $i >= 1920; $i--) {
            $this->_availableYears[$i] = $i;
        }

        $this->addModel();
        $this->addLicensePlate();
        $this->addBrand();
        $this->addColor();
        $this->addYear();
        $this->addModelYear();
        $this->addPrice();
        $this->addShowPrice();
        $this->addPriority();
        $this->addSubmit();
    }

    /**
     * Add Model wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Car
     */
    public function addModel()
    {
        $this->addElement($this->getTextElement('model', 'Modelo'));
        return $this;
    }

    /**
     * Add Color wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Car
     */
    public function addColor()
    {
        $this->addElement($this->getTextElement('color', 'Cor'));
        return $this;
    }

    /**
     * Add LicensePlate wich is a Zend_Form_Element_Text
     * @return Admin_Form_Car
     */
    public function addLicensePlate()
    {
        $this->addElement($this->getTextElement('licensePlate', 'Placa',
                array('min' => 7, 'max' => 8)));
        return $this;
    }

    /**
     * Add Brand wich is a Zend_Form_Element_Select
     * @return Admin_Form_Car
     */
    public function addBrand()
    {
        $element = new MyZend_Form_Element_DoctrineSelect('brand_id');
        $element->setLabel('Marca')
            ->addMultiOptionFromDql(
                Admin_Model_Brand::getSelectDql(),
                'id',
                'name',
                array(null => 'Selecione')
        );
        $this->setRequired($element);
        $this->addElement($element);
        return $this;
    }

    /**
     * Add Brand wich is a Zend_Form_Element_Select
     * @return Admin_Form_Car
     */
    public function addYear()
    {
        $element = new Zend_Form_Element_Select('year');
        $element->setLabel('Ano');
        $element->addMultiOption('', 'Selecione');
        $element->addMultiOptions($this->_availableYears);
        $this->setRequired($element);
        $this->addElement($element);
        return $this;
    }

    /**
     * Add Brand wich is a Zend_Form_Element_Select
     * @return Admin_Form_Car
     */
    public function addModelYear()
    {
        $element = new Zend_Form_Element_Select('modelYear');
        $element->setLabel('Ano Modelo');
        $element->addMultiOption('', 'Selecione');
        $element->addMultiOptions($this->_availableYears);
        $this->setRequired($element);
        $this->addElement($element);
        return $this;
    }

    /**
     * Add Brand wich is a Zend_Form_Element_Text
     * @return Admin_Form_Car
     */
    public function addPrice()
    {
        $element = $this->getTextElement('price', 'Valor');
        $this->addClass('money', $element);
        $this->addElement($element);
        return $this;
    }

    /**
     * Add option to show price or not
     * @return Admin_Form_Car 
     */
    public function addShowPrice()
    {
        $element = $this->getCheckElement('showPrice', 'Exibir Valor');
        $this->addElement($element);
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

