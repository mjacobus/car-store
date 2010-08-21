<?php
/**
 * Description of ChangePassword
 *
 * @author marcelo.jacobus
 */
class Form_ChangePassword extends Form_Abstract
{
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->getPasswordElement('oldPassword', 'Senha atual'));
        $this->addElement($this->getPasswordElement('newPassword', 'Senha nova'));
        $this->addElement($this->getPasswordElement('passwordConfirmation', 'Confirmação da senha nova'));
        $this->addSubmit('Trocar senha');
    }

}