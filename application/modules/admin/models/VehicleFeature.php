<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_VehicleFeature extends App_Model_Crud
{

    protected $_tableName = 'VehicleFeature';
    protected $_ukMapping = array(
        'vehicle_id_description_uk_idx' => array(
            'field' => 'description',
            'label' => 'Descricão',
            'message' => 'Este veículo já possue descricão "{value}" cadastrada.'
        )
    );

    /**
     * Get the form
     * @param int $id
     * @return Admin_Form_Brand
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_VehicleFeature($id);
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
                ->createQuery('I')
                ->orderBy('priority ASC')
                ->where('vehicle_id = ?', $params['vehicle']);

        if (array_key_exists('search', $params) && $params['search']) {
            $search = "%" . $params['search'] . "%";
            $where = "(description like ?)";
            $dql->addWhere($where, $search);
        }

        return $dql;
    }

}