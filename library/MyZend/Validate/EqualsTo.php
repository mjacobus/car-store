<?php

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * Description of ${name}
 *
 * @author ${user}
 */
class MyZend_Validate_EqualsTo extends Zend_Validate_Abstract
{

    const NOT_EQUAL = 'notEqual';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EQUAL => "The value is not equal to %value%"
    );

    /**
     * 
     * @param string $equalsTo
     */
    public function __construct($equalsTo)
    {
        $this->_equalTo = $equalsTo;
    }

    /**
     *
     * @var String
     */
    protected $_equalTo;

    public function isValid($value)
    {
        if ($value !== $this->_equalTo) {
            $this->_error(self::NOT_EQUAL,$this->_equalTo);
            return false;
        }
        return true;
    }

}
