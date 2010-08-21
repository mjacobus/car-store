<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_Car extends Admin_Model_Abstract
{

    protected $_tableName = 'Car';


    /**
     * Get the form
     * @return Admin_Form_Brand
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_Car($id);
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
        return sprintf('Tem certeza que deseja excluir o veiculo "%s/%s" (%s)?',
            $record->Brand->name,$record->model,$record->licensePlate);
    }

    /**
     * Return a DQL for listing registers
     * @param array $params for querying
     * @return Doctrine_Query
     */
    public function getListingDql(array $params = array())
    {
        $dql = Doctrine_Core::getTable($this->getTablelName())
                ->createQuery('C')
                ->innerJoin('C.Brand B')
                ->orderBy('C.model ASC,B.name ASC');

        if (array_key_exists('search', $params) && $params['search']) {
            $search = $params['search'];
            $dql->addWhere('C.model like ?', "%$search%")
                ->orWhere('B.name like ?', "%$search%")
                ->orWhere('C.licensePlate like ?', "%$search%")
                ->orWhere('C.year like ?', "%$search%")
                ->orWhere('C.color like ?', "%$search%")
                ->orWhere('C.modelYear like ?', "%$search%");
        }
        return $dql;
    }

}