<?php
/**
 * @author marcelo.jacobus
 */
class Admin_Form_Authentication extends Form_Abstract
{

    /**
     * Constructor
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->getTextElement('username', 'Login'));
        $this->addElement($this->getPasswordElement('password', 'Password', true, array()));
        $this->addSubmit('Login');
    }

    public function getInlineScript()
    {
        $salt = Zend_Registry::get('securitySalt');
        $script = "$(document).ready(function(){
            $('#password').change(function(){
                if($(this).val().length !== 40){
                    var encripted = SHA1($(this).val());
                    $(this).val(encripted);
                }
            });
        });";
        return $script;
    }

}