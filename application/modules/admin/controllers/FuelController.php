<?php

class Admin_FuelController extends Admin_Controller_Abstract
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
        $this->view->headTitle('Combustível');
        $this->model = new Admin_Model_Fuel();
    }
}