<?php

/**
 * Admin_Controller_Abstract
 */
require_once 'Abstract.php';

class Admin_ImageUploadController extends Admin_Controller_Abstract
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
        set_time_limit(120);
        $this->requireAuthentication();
        $this->setLayout();
        $this->view->headTitle('Imagens');
        $this->model = new Admin_Model_ImageUpload();
    }
}