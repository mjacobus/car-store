<?php
/**
 *
 *
 * @author marcelo.jacobus
 */
class Model_Profile extends Model_Abstract
{

    /**
     * @var Form_ChangePasswordForm
     */
    protected $_changePassowrdForm;
    
    /**
     * @var Form_Profile
     */
    protected $_form;

    /**
     * Password Security Salt
     * @var string
     */
    protected $_salt;

    /**
     * @return Form_Authentication
     */
    public function getChangePasswordForm()
    {
        if ($this->_changePassowrdForm == null) {
            $this->_changePassowrdForm = new Form_ChangePassword();
        }
        return $this->_changePassowrdForm;
    }
    
    /**
     * @return Form_Authentication
     */
    public function getForm()
    {
        if ($this->_form == null) {
            $this->_form = new Form_Profile();
            $this->_form->populate(Zend_Auth::getInstance()->getIdentity()->toArray());
        }
        return $this->_form;
    }

    /**
     *
     * @param array $values
     * @return bool
     */
    public function changePassword(array $values = array())
    {
        if (Model_Authentication::isLogged()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            $form = $this->getChangePasswordForm();

            //confere validacoes basicas
            if (!$form->isValid($values)) {
                $this->_messages[] = "O formulário contém valores inválidos.";
                return false;
            }

            //confere se a confirmacao combina com o novo password
            $newPassword =  $form->getValue('newPassword');
            $validator = new Zend_Validate_InArray(array($newPassword));
            $validator->setMessage('Senha não confere.', Zend_Validate_InArray::NOT_IN_ARRAY);
            $form->passwordConfirmation->addValidator($validator);
            
            if (!$form->isValid($values)) {
                $this->_messages[] = "O formulário contém valores inválidos.";
                return false;
            }

            //confere se a senha antiga confere
            $password =  $form->getValue('oldPassword');
            $password = sha1($this->addSalt($password));
            
            if ($user->password !== $password) {
                $this->_messages[] = "Senha incorreta.";
                return false;
            }

            try {
                $user->password = sha1($this->addSalt($newPassword));
                $user->save();
                $this->_messages = 'Senha alterada com sucesso.';
                return true;
            } catch (Exception $e) {
                $this->_messages[] = $e->getMessage();
            }
        }
        return false;
    }

    /**
     * Save profile
     * @param array $values
     * @return Model_Profile
     */
    public function saveProfile(array $values = array())
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        $form = $this->getForm();

        if ($form->isValid($values)) {
            try {
                $user->merge($values);
                $user->save();
                $this->_messages[] = 'Profile salvo com sucesso.';
                return true;
            }catch(Exception $e) {
            }
        }

        $this->_messages[] = 'Erro ao salvar profile.';
        return false;
    }

    /**
     * Adiciona o Security Salt
     * @param addSalt to a string $password
     * @return string
     */
    public function addSalt($password)
    {
        $salt = $this->_salt;
        return $salt . $password . $salt;
    }

    /**
     * Set security salt for password verification
     * @param string $salt
     * @return Model_User
     */
    public function setSecuritySalt($salt)
    {
        $this->_salt = $salt;
        return $this;
    }
}