<?php

/**
 * Admin_Controller_Abstract
 */
require_once 'Abstract.php';

class Admin_CarController extends Admin_Controller_Abstract
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
        $this->view->headTitle('Veículos');
        $this->model = new Admin_Model_Car();
    }
}