<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_VehicleImage extends App_Model_Crud
{

    protected $_tableName = 'VehicleImage';
    protected $_ukMapping = array(
        'vehicle_id_image_id_idx' => array(
            'field' => 'image_id',
            'label' => 'Imagem',
            'message' => 'Este veículo já possue esta imagem cadastrada.'
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
            $this->_form = new Admin_Form_VehicleImage($id);
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
            
            $where = "(alt like ? OR title like ? OR description like ? )";
            
            preg_match_all("/\?/", $where,$matches);

            $params = array();
            foreach($matches[0] as $tmp) {
                $params[] = $search;
            }
            
            $dql->addWhere($where, $params);
        }

        return $dql;
    }

}