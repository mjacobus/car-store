<?php

/**
 * Form for ImageUpload
 *
 * @author marcelo.jacobus
 */
class Admin_Form_ImageUpload extends App_Form
{

    /**
     * Constructor
     */
    public function __construct(array $params = null, $options = null)
    {
        parent::__construct($options);

        $this->addImage($params);
        $this->addFile($params);
        $this->addFilename();
        $this->addDescription();
        $this->addSubmit();
    }

    /**
     * Add Description wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_ImageUpload
     */
    public function addDescription()
    {
        $this->addElement($this->getTextElement('description', 'Descrição'));
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
    public function addFile(array $params = array())
    {
        $element = new Zend_Form_Element_File('file');
        $this->setRequired($element);
        $element->setLabel('Arquivo')
            ->setDestination(APPLICATION_PATH . '/../tmp/uploads')
            ->addValidator('Extension', false, 'jpg,png,gif') //2MB
            ->addValidator('Size', false, 2097152000) //2MB
            ->setMaxFileSize(2097152);

        if (array_key_exists('id', $params)) {
            $element->setDescription("Para alterar imagem prencha este campo.");
            $this->setRequired($element, false);
            $element->setLabel('');
        }

        $this->addElement($element);
        return $this;
    }

    /**
     * Add image wich is a Zend_Form_Element_File
     * @return Admin_Form_ImageUpload
     */
    public function addImage(array $params = array())
    {
        if (array_key_exists('id', $params)) {
            $element = new Zend_Form_Element_Image('image', array());
            $element->setLabel('Imagem')
                ->setAttrib('style', 'width:400px;');
            $element->getDecorator('HtmlTag')->setOption('style', 'height:310px;');
            $this->addElement($element);
        }
        return $this;
    }

}

