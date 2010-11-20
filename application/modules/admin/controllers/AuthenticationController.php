<?php

class Admin_AuthenticationController extends Zend_Controller_Action
{

    /**
     * @var Admin_Model_Authentication
     */
    public  $model;

    public function init()
    {
        $this->view->headTitle('Login');
        $this->model = new Admin_Model_Authentication();
    }

    public function indexAction()
    {
        $url = $this->getRequest()->getParam('redirect','/');
        if (Admin_Model_Authentication::isLogged() == false) {
            $this->view->form = $this->model->getForm();
            $this->setScripts();

            if ($this->getRequest()->isPost()) {
                if ($this->model->performLogin($this->_getAllParams())) {
                    $this->_redirect($url);
                } else {
                    $this->view->errors()->addMessages($this->model->getMessages());
                }
            }
        } else {
            $this->model->logout();
            $this->_redirect($url);
        }
    }

    /**
     * log out
     */
    public function logoutAction()
    {
        $this->model->logout();
        $this->_redirect('/');
    }

    public function setScripts()
    {
        $view = $this->view;
        $view->headLink()
                ->appendStylesheet($view->baseUrl('/css/admin.css'));
        $view->headScript()
                ->appendFile($view->baseUrl('/js/sha1.js'))
                ->appendFile($view->baseUrl('/js/jquery-1.4.2.min.js'));

         $this->view->inlineScript(Zend_View_Helper_HeadScript::SCRIPT,
                $this->model->getForm()->getInlineScript());
    }

}

