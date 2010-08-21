<?php
class MyZend_View_Helper_Pager extends Zend_View_Helper_Abstract
{

    /**
     * Pager
     * @var Doctrine_Pager
     */
    protected $_pager;

    /**
     * Get/Set Doctrine pager.
     * @param Doctrine_Pager $pager Doctrine_Pager to set
     * @return Doctrine_Pager
     */
    function pager(Doctrine_Pager $pager = null)
    {
        if ($pager !== null) {
            $this->_pager = $pager;
        }
        return $this->_pager;
    }
}