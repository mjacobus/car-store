<?php

class Admin_IndexController extends Admin_Controller_Abstract
{

    public function init()
    {
        $this->requireAuthentication();
    }

    public function indexAction()
    {
        // action body
    }

    public function postDispatch()
    {
        $this->setLayout();
        $this->view->headTitle('Página de Administrção');
    }

}

