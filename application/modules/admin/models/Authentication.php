<?php

/**
 *
 *
 * @author marcelo.jacobus
 */
class Admin_Model_Authentication extends Model_Abstract implements Zend_Auth_Adapter_Interface
{

    /**
     * @var string
     */
    protected $_login;
    /**
     * @var string
     */
    protected $_password;
    /**
     * @var string
     */
    protected $_salt;
    /**
     * @var Admin_Form_Authentication
     */
    protected $_form;
    /**
     * @var bool
     */
    protected $_encriptPassword = true;

    /**
     * @return Admin_Form_Authentication
     */
    public function getForm()
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_Authentication();
        }
        return $this->_form;
    }

    /**
     * Set login
     * @param string $login
     * @return Admin_Model_Authentication
     */
    public function setLogin($login)
    {
        $this->_login = $login;
        return $this;
    }

    /**
     * Username
     * @param string $name
     * @return Admin_Model_Authentication
     */
    public function setPassword($name)
    {
        $this->_password = $name;
        return $this;
    }

    /**
     * Hash
     * @param string $salt
     * @return Admin_Model_Authentication
     */
    public function setSecuritySalt($salt)
    {
        $this->_salt = $salt;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $user = Doctrine_Core::getTable('User')
                ->findOneByLogin($this->_login);

        if ($user !== false) {
            $password = Model_Security::stringToPasswordHash($this->_password);

            if ($password === $user->password && $user->enabled == 1) {
                return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
            }
        }

        throw new Zend_Auth_Adapter_Exception('Could not nog in.');
    }

    public function performLogin(array $values = array())
    {
        if (self::isLogged()) {
            $this->logout();
        }

        try {
            if ($this->getForm()->isValid($values)) {
                $this->setLogin($this->_form->getValue('username'));
                $this->setPassword($this->_form->getValue('password'));

                $result = Zend_Auth::getInstance()->authenticate($this);

                if ($result->isValid()) {
                    return true;
                }
            }
        } catch (Zend_Auth_Adapter_Exception $e) {
            
        }

        $this->_messages[] = 'Usuário e ou senha não conferem.';
        return false;
    }

    public function logout()
    {
        Zend_Auth::getInstance()->clearIdentity();
    }

    public static function isLogged()
    {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    /**
     * Set option to encript password before comparing against the database
     * @param boolean flag
     */
    public function setEncriptPassword($flag)
    {
        $this->_encriptPassword = (bool) $flag;
    }

    /**
     * Get option to encript password before comparing against the database
     * @return  boolean
     */
    public function getEncriptPassword()
    {
        return $this->_encriptPassword;
    }

    /**
     * Get authenticated user
     * @return User
     */
    public static function getIdentity()
    {
        return Zend_Auth::getInstance()->getIdentity();
    }

}