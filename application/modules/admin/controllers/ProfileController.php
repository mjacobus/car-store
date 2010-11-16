<?php
/**
 * ControllerAbstract
 */
require_once 'Abstract.php';

class Admin_ProfileController extends Admin_Controller_Abstract
{

    /**
     * @var Admin_Admin_Model_Profile
     */
    public  $model;

    /**
     * Just to disable search form
     */
    public function setSearchForm()
    {
        
    }

    public function init()
    {
        $this->requireAuthentication();
        $this->view->headTitle('Profile');
        $this->model = new Admin_Model_Profile();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
    }

    /**
     * Attempt to change password
     */
    public function changePasswordAction()
    {
        $this->view->headTitle('Profile - Alteração de Senha');
        $this->view->form = $this->model->getChangePasswordForm();
        
        if ($this->getRequest()->isPost()) {
            if ($this->model->changePassword($this->_getAllParams())) {
                $this->view->flash($this->model->getMessages());
                $this->_redirect($this->getRequest()->getModuleName() . '/' . $this->getRequest()->getControllerName());
            } else {
                $this->view->errors()->addMessages($this->model->getMessages());
            }
        }
    }

    public function indexAction()
    {
        $this->view->form = $this->model->getForm();

        if ($this->getRequest()->isPost()) {
            if ($this->model->saveProfile($this->_getAllParams())) {
                $this->view->flash($this->model->getMessages());
                $this->_redirect($this->getRequest()->getModuleName() . '/' . $this->getRequest()->getControllerName());
            } else {
                $this->view->errors($this->model->getMessages());
            }
        }
    }
 
}

