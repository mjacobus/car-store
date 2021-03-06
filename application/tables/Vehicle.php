<?php

/**
 * Vehicle
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Vehicle extends Base_Vehicle
{
    const VEHICLE_STATUS_AVAILABLE = 1;
    const VEHICLE_STATUS_CANCELED = 2;
    const VEHICLE_STATUS_SOLD = 3;

    /**
     * Get the main image
     * @return Doctrine_Record|stdClass
     */
    public function getImage()
    {
        $dql = Doctrine_Query::create()
                ->from('VehicleImage CI')
                ->leftJoin('CI.Image')
                ->where('vehicle_id = ?', $this->id)
                ->orderBy('priority ASC');
        if ($dql->count()) {
            $image = $dql->fetchOne();
            return $image;
        }

        return false;
    }

    /**
     * pre save rotines.
     */
    public function preSave()
    {
        $this->calculateUrl();
        $this->changeStatusDate();
    }

    /**
     * calculates and sets url
     */
    private function calculateUrl()
    {
        $urlParts = array(
            $this->Brand->name,
            $this->model,
            $this->licensePlate,
        );

        $url = Util_String::arrayToUrl($urlParts);

        $this->_set('url', $url);
    }

    /**
     * Get formated price
     * @return string
     */
    public function getPrice()
    {
        $value = $this->_get('price');
        $options = array(
            'format' => 'pt_BR',
            'display' => Zend_Currency::NO_SYMBOL,
        );
        $cur = new Zend_Currency();
        return $cur->toCurrency($value, $options);
    }

    /**
     *
     * @param string $value
     */
    public function setPrice($value)
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        $this->_set('price', $value);
    }

    /**
     * change the date the vehicle had the satus changed
     */
    public function changeStatusDate()
    {
        $modified = $this->getModified();

        if (array_key_exists('status_id', $modified)) {
            if ($this->status_id !== self::VEHICLE_STATUS_AVAILABLE) {
                $date = new Zend_Date();
                $this->status_modified_at = $date->toString('YYYY-MM-dd HH:mm:ss');
            }
        }
    }

}