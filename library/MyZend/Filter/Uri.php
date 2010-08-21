<?php

/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

class MyZend_Filter_Uri implements Zend_Filter_Interface
{

    /**
     * Whether or not to prepend slash
     * @var boolean
     */
    protected $_prependSlash = false;

    /**
     * Whether or not to append slash
     * @var boolean
     */
    protected $_appendSlash = false;

    public function  __construct($prependSlash = false, $appendSlash = false)
    {
        $this->_prependSlash = (boolean) $prependSlash;

        $this->_appendSlash = (boolean) $appendSlash;
    }

    /**
     * Apply filter
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $filtered = trim(trim($value),'/');

        if ($this->_prependSlash) {
            $filtered = '/' . $filtered;
        }
        if ($this->_appendSlash) {
            $filtered .= '/';
        }

        return $filtered;
    }
}