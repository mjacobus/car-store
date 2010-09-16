<?php

/**
 * Form for User
 *
 * @author marcelo.jacobus
 */
class Admin_Form_User extends Admin_Form_Abstract
{

    /**
     * Constructor
     */
    public function __construct($id = null, $options = null)
    {
        parent::__construct($options);
        $this->addName();
        $this->addEmail();
        $this->addLogin();
        $this->addEnabled();
        $this->addRole();
        $this->addPasswordFields($id);
        $this->addSubmit();

        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset'
        ));
    }

    public function addPasswordFields($id = null)
    {
        $required = ($id == null);
        $this->addPassword($required);
        $this->addPasswordConfirmation($required);

        $fields = array(
            'password',
            'password_confirmation'
        );

        $this->addDisplayGroup($fields, 'f-passwords');
        $display = $this->getDisplayGroup('f-passwords')
                ->setLegend('Senhas');
    }

    /**
     * Add Name wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_User
     */
    public function addName()
    {
        $this->addElement($this->getTextElement('name', 'Nome'));
        return $this;
    }

    /**
     * Add Email wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_User
     */
    public function addEmail()
    {
        $element = $this->getEmailElement('email','Email');
        $this->addElement($element);
        return $this;
    }

    /**
     * Add Email wich is a Zend_Form_Element_Text
     * length 255
     * @return Admin_Form_User
     */
    public function addLogin()
    {
        $this->addElement($this->getTextElement('login', 'Login'));
        return $this;
    }

    /**
     * Add Enabled wich is a Zend_Form_Element_Checkbox
     * length 255
     * @return Admin_Form_User
     */
    public function addEnabled()
    {
        $this->addElement($this->getCheckElement('enabled', 'Ativo'));
        return $this;
    }

    /**
     * Add Enabled wich is a Zend_Form_Element_Password
     * length 255
     * @return Admin_Form_User
     */
    public function addPassword($required)
    {
        $element = $this->getPasswordElement('password', 'Senha');
        $this->setRequired($element, $required);
        $element->setRequired($required);
        $this->addElement($element);
        return $this;
    }

    /**
     * Add Enabled wich is a Zend_Form_Element_Password
     * length 255
     * @return Admin_Form_User
     */
    public function addPasswordConfirmation($required)
    {
        $element = $this->getPasswordElement('password_confirmation', 'Confirma Senha');
        $this->setRequired($element, $required);
        $this->addElement($element);
        return $this;
    }

    /**
     * Add Role wich is a Zend_Form_Element_Select
     * length 255
     * @return Admin_Form_User
     */
    public function addRole()
    {
        $element = new MyZend_Form_Element_DoctrineSelect('role_id');
        $element->setLabel('Logotipo')
            ->addMultiOptionFromCollection(
                Admin_Model_User::getRoles(),
                'id',
                'name',
                array(null => 'Selecione')
        );
        $this->setRequired($element);
        $this->addElement($element);
        return $this;
    }

    public function isValid($data)
    {
        parent::isValid($data);

        //TODO: solve password problem
        //password validation
        $password = $this->getValue('password');
        if ($password) {
            $validator = new MyZend_Validate_EqualsTo($password);
            $validator->setMessage("As senhas não conferem", MyZend_Validate_EqualsTo::NOT_EQUAL);
            $this->password_confirmation->addValidator($validator);
        }

        return parent::isValid($data);
    }

}

