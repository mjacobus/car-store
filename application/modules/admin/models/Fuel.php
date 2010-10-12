<?php

/**
 * Manages Fuels
 *
 * @author marcelo.jacobus
 */
class Admin_Model_Fuel extends Admin_Model_Abstract
{

    protected $_tableName = 'Fuel';

    protected $_ukMapping = array(
        'name' => array(
            'field' => 'name',
            'label' => 'Nome',
            'message' => 'Combustível de nome "{value}" já existe no sistema.'
        ),
        'short' => array(
            'field' => 'short',
            'label' => 'Abreviado',
            'message' => 'A abreviação de combustível "{value}" já existe no sistema.'
        ),
    );

    
    /**
     * Get the form
     * @return Admin_Form_Fuel
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_Fuel($id);
        }
        return $this->_form;
    }

    /**
     * Get a confirmation messages for deleting a record
     * @var int $id the id of the Fuel record
     * @return string
     */
    public function getDelConfirmationMessage($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        return sprintf('Tem certeza que deseja excluir o combustível "%s"?', $record->name);
    }

    /**
     * @return Doctrine_Query
     */
    public static function getSelectDql()
    {
        return Doctrine_Query::create()
            ->from('Fuel')->orderBy('name ASC');
    }

}