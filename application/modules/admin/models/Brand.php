<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_Brand extends Admin_Model_Abstract
{

    protected $_tableName = 'Brand';

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the form
     * @return Admin_Form_Brand
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_Brand($id);
        }
        return $this->_form;
    }

    /**
     * Get a confirmation messages for deleting a record
     * @var int $id the id of the Brand record
     * @return string
     */
    public function getDelConfirmationMessage($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        return sprintf('Tem certeza que deseja excluir a marca "%s"?', $record->name);
    }

}