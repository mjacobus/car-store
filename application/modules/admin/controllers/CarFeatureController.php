<?php

/**
 * Admin_Controller_Abstract
 */
require_once 'Abstract.php';

class Admin_CarFeatureController extends Admin_Controller_Abstract
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
        $this->model = new Admin_Model_CarFeature();
    }

    /**
     * After save succeed, redirects
     * Displays ok message and redirects
     */
    public function onSaveOk($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        $url = $this->getRequest()->getModuleName()
            . '/' . $this->getRequest()->getControllerName();
        $url .= "/index/car/" . $this->_getParam('car_id');
        $this->_redirect($url);
    }

    /**
     *
     * @param int $savedRecordId
     */
    public function onDeleteOk($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}