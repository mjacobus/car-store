<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_CarFeature extends Admin_Model_Abstract
{

    protected $_tableName = 'CarFeature';


    /**
     * Get the form
     * @param int $id
     * @return Admin_Form_Brand
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_CarFeature($id);
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
        return sprintf('Tem certeza que deseja excluir "%s"?',
            $record->description);
    }

    /**
     * Return a DQL for listing registers
     * @param array $params for querying
     * @return Doctrine_Query
     */
    public function getListingDql(array $params = array())
    {
        $dql = Doctrine_Core::getTable($this->getTablelName())
                ->createQuery('F')
                ->orderBy('priority ASC')
                ->where('car_id = ?', $params['car']);

        return $dql;
    }

}