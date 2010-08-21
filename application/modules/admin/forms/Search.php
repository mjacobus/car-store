<?php
/**
 * This is the ordinary search form
 *
 * @author marcelo.jacobus
 */
class Admin_Form_Search extends Admin_Form_Abstract
{
    /**
     *
     * @param string $value
     */
    public function __construct($value = null)
    {
        parent::__construct();

        $this->setAttrib('id', 'search');
        $this->setAttrib('class', 'search');
        
        $this->setMethod(Zend_Form::METHOD_GET);
        
        $search = new Zend_Form_Element_Text('search');
        $search->setDecorators(array( 'ViewHelper', 'Errors'))
                ->setValue($value)
                ->setAttrib('class','required');
        $this->addElement($search);

        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Pesquisar')
                ->setAttrib('class', 'button')
                ->setDecorators(array( 'ViewHelper', 'Errors'));
        $this->addElement($submit);
    }
}