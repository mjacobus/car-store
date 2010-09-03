<?php
/**
 * Zend_Form_Element_Select for doctrine
 *
 * @author marcelo
 */
class MyZend_Form_Element_DoctrineSelect extends Zend_Form_Element_Select
{

    /**
     * Constructor
     *
     * $spec may be:
     * - string: name of element
     * - array: options with which to configure element
     * - Zend_Config: Zend_Config with options for configuring element
     *
     * @param  string|array|Zend_Config $spec
     * @return void
     * @throws Zend_Form_Exception if no element name after initialization
     */
    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);
    }


    /**
     * Add options to a select box from a DQL
     *
     * @param Doctrine_Query $resultset
     * @param string $propertyValue property for value
     * @param string $propertyLabel property for label
     * @param array $prependOptions Options for prepending. IE: array('' => 'Select an option')
     * @return MyZend_Form_Element_Select
     */
    public function addMultiOptionFromDql(Doctrine_Query $dql,$propertyValue,$propertyLabel, $prependOptions = array())
    {
        $this->addMultiOptionFromCollection($dql->execute(), $propertyValue, $propertyLabel,$prependOptions);
        return $this;
    }

    /**
     * Add options to a select box from a Doctrine_Collection
     * 
     * @param Doctrine_Collection $resultset
     * @param string $propertyValue property for value
     * @param string $propertyLabel property for label
     * @param array $prependOptions Options for prepending. IE: array('' => 'Select an option')
     * @return MyZend_Form_Element_Select
     */
    public function addMultiOptionFromCollection(Doctrine_Collection $resultset,$propertyValue,$propertyLabel, $prependOptions = array())
    {
        $this->addMultiOptions($prependOptions);
        try {
            foreach($resultset as $row) {
                $this->addMultiOption($row->$propertyValue, $row->$propertyLabel);
            }
        } catch(Exception $e) {
            require 'MyZend/Form/Element/DoctrineException.php';
            throw new MyZend_Form_Element_DoctrineException($e->getMessage());
        }
        return $this;
    }


    
}