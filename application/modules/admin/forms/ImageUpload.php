<?php

/**
 * Form for Car
 *
 * @author marcelo.jacobus
 */
class Admin_Form_ImageUpload extends Admin_Form_Abstract
{

    /**
     * Constructor
     */
    public function __construct(array $params = null, $options = null)
    {
        parent::__construct($options);
        $this->addFile();
        $this->addFilename();
        $this->addDescription();
        $this->addMd5();
        $this->addSubmit();
    }

    /**
     * Add Description wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_ImageUpload
     */
    public function addDescription()
    {
        $this->addElement($this->getTextElement('description', 'Descricão'));
        return $this;
    }
    
    /**
     * Add filename wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_ImageUpload
     */
    public function addFilename()
    {
        $this->addElement($this->getTextElement('filename', 'Nome do Arquivo'));
        return $this;
    }

    /**
     * Add file wich is a Zend_Form_Element_File
     * @return Admin_Form_ImageUpload
     */
    public function addFile()
    {
        $element = new Zend_Form_Element_File('file');
        $element->setLabel('Imagem')
            ->setDestination(APPLICATION_PATH . '/../tmp/uploads')
            ->addValidator('Extension', false, 'jpg,png,gif') //2MB
            ->addValidator('Size', false, 2097152000) //2MB
            ->setMaxFileSize(2097152);

        $this->addElement($element);
        return $this;
    }

    /**
     * Add filename wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_ImageUpload
     */
    public function addMd5()
    {
        $this->addElement($this->getTextElement('md5', 'Hash'));
        return $this;
    }
    
}

