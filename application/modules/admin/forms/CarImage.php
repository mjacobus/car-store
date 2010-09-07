<?php

/**
 * Form for Car
 *
 * @author marcelo.jacobus
 */
class Admin_Form_CarImage extends Admin_Form_Abstract
{

    protected $_availableYears = array();

    /**
     * Constructor
     */
    public function __construct(array $params = null, $options = null)
    {
        parent::__construct($options);
        $this->addCarId($params);
        $this->addImage();
        $this->addAlt();
        $this->addTitle();
        $this->addDescription();
        $this->addPriority();
        $this->addSubmit();
    }



    /**
     * Add Image wich is a Zend_Form_Element_Select
     * length 255
     * @return Admin_Form_CarImage
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
     * Add CarId wich is a Zend_Form_Element_Text
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
        $this->addElement($this->getTextElement('description', 'Descrição'));
        return $this;
    }

    /**
     * Add Alternative text wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Car
     */
    public function addAlt()
    {
        $this->addElement($this->getTextElement('alt', 'Texto alternativo'));
        return $this;
    }

    /**
     * Add Title text wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_Car
     */
    public function addTitle()
    {
        $this->addElement($this->getTextElement('title', 'Título'));
        return $this;
    }

    /**
     * Add priority
     * @return Admin_Form_Car
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

