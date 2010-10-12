<?php

/**
 * Manages Users
 *
 * @author marcelo.jacobus
 */
class Admin_Model_User extends Admin_Model_Abstract
{

    protected $_tableName = 'User';

    protected $_ukMapping = array(
        'name' => array(
            'field' => 'name',
            'label' => 'Nome',
            'message' => 'Marca de nome "{value}" já existe no sistema.'
        )
    );

    
    /**
     * Get the form
     * @return Admin_Form_User
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_User($id);
        }
        return $this->_form;
    }

    /**
     * Get a confirmation messages for deleting a record
     * @var int $id the id of the User record
     * @return string
     */
    public function getDelConfirmationMessage($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        return sprintf('Tem certeza que deseja excluir a usuario "%s"?', $record->name);
    }

    public static function getRoles()
    {
        $dql = Doctrine_Query::create()
            ->from('UserRole')
            ->orderBy('name');

        return $dql->execute();
    }
}