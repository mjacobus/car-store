<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_Brand extends App_Model_Crud
{

    protected $_tableName = 'Brand';

    protected $_ukMapping = array(
        'name' => array(
            'field' => 'name',
            'label' => 'Nome',
            'message' => 'Marca de nome "{value}" já existe no sistema.'
        )
    );

    
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

    /**
     * @return Doctrine_Query
     */
    public static function getSelectDql()
    {
        return Doctrine_Query::create()
            ->from('Brand')->orderBy('name ASC');
    }

}