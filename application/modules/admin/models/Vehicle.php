<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_Vehicle extends Admin_Model_Abstract
{

    protected $_tableName = 'Vehicle';
    protected $_ukMapping = array(
        'licensePlate' => array(
            'field' => 'licensePlate',
            'label' => 'Placa',
            'message' => 'Veículo de placa "{value}" já existe no sistema.'
        )
    );

    /**
     * Get the form
     * @return Admin_Form_Brand
     */
    public function getForm($id = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_Vehicle($id);
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
            $record->Brand->name, $record->model, $record->licensePlate);
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
                ->innerJoin('C.Type T')
                ->innerJoin('C.Fuel F')
                ->innerJoin('C.Status S')
                ->orderBy('C.model ASC,B.name ASC');

        if (array_key_exists('search', $params) && $params['search']) {
            $search = $params['search'];
            $dql->addWhere('C.model like ?', "%$search%")
                ->orWhere('B.name like ?', "%$search%")
                ->orWhere('T.name like ?', "%$search%")
                ->orWhere('C.licensePlate like ?', "%$search%")
                ->orWhere('C.year like ?', "%$search%")
                ->orWhere('C.color like ?', "%$search%")
                ->orWhere('C.modelYear like ?', "%$search%")
                ->orWhere('F.name like ?', "%$search%");

            $status = strtolower($search);
            if (($status == 'cancelado') || ($status == 'vendido') || ($status == 'disponível') || ($status == 'disponivel')) {
                $dql->where('S.name = ?', $status);
            }
        }
        return $dql;
    }

    /**
     * Get dql for listing statuses
     * @return Doctrine_Query
     */
    public static function getStatusDql()
    {
        $dql = Doctrine_Query::create()
                ->from('VehicleStatus')
                ->orderBy('id');
        return $dql;
    }
    
    /**
     * Get dql for listing types
     * @return Doctrine_Query
     */
    public static function getTypeDql()
    {
        $dql = Doctrine_Query::create()
                ->from('VehicleType')
                ->orderBy('name');
        return $dql;
    }

    /**
     * Get Vehicle by id
     * @param int $id
     * @return Vehicle
     */
    public function  getRegister($id)
    {
        $dql = Doctrine_Query::create()
            ->from($this->getTablelName() . ' C')
            ->leftJoin('C.Images CI')
            ->leftJoin('CI.Image I')
            ->leftJoin('C.Features F')
            ->where('C.id = ?', $id)
            ->orderBy('F.priority,CI.priority');
        return $dql->fetchOne();
    }
}