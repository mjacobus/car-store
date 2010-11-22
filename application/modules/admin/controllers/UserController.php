<?php

class Admin_UserController extends Admin_Controller_Abstract
{

    /**
     * @var Admin_Model_Category
     */
    public $model = null;

    /**
     * Init
     */
    public function init()
    {
        $this->requireAuthentication();
        $this->setLayout();
        $this->view->headTitle('Usuários');
        $this->model = new Admin_Model_User();
    }
}