<?php
/**
 * @author marcelo.jacobus
 */
class Form_Authentication extends Form_Abstract
{

    /**
     * Constructor
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElement($this->getTextElement('username', 'Login'));
        $this->addElement($this->getPasswordElement('password', 'Password'));
        $this->addSubmit('Login');
    }

    public function getInlineScript()
    {
        $salt = Zend_Registry::get('securitySalt');
        $script = "$(document).ready(function(){
            $('#password').change(function(){
                if($(this).val().length !== 40){
                    var encripted = SHA1('$salt' + $(this).val() + '$salt');
                    $(this).val(encripted);
                    $(this).val();
                }
            });
        });";

        return $script;
    }

}