<?php
/**
 * Description of Form_Profile
 *
 * @property string $name
 * @property string $email
 * @property string $login
 * @property string $image
 * @author marcelo.jacobus
 */
class Admin_Form_Profile extends Form_Abstract
{
    
    public function __construct($options = null)
    {
        parent::__construct($options);

        $element = $this->getTextElement('name', 'Nome');
        $this->addElement($element);
        
        $element = $this->getTextElement('email', 'Email');
        $element->addValidator(new Zend_Validate_EmailAddress());
        $this->addElement($element);
        
        $this->addSubmit('Salvar');

    }

}