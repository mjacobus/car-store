<?php

/**
 * Admin_Controller_Abstract
 */
require_once 'Abstract.php';

class Admin_VehicleImageController extends Admin_Controller_Abstract
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
        $this->view->headTitle('Veículos - Imagens');
        $this->model = new Admin_Model_VehicleImage();
    }

    /**
     * After save succeed, redirects
     * Displays ok message and redirects
     */
    public function postSave($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        $url = $this->getRequest()->getModuleName()
            . '/' . $this->getRequest()->getControllerName();
        $url .= "/index/vehicle/" . $this->_getParam('vehicle_id');
        $this->_redirect($url);
    }

    /**
     *
     * @param int $savedRecordId
     */
    public function postDelete($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        $url = $this->getRequest()->getModuleName()
            . '/' . $this->getRequest()->getControllerName();
        $url .= "/index/vehicle/" . $this->_getParam('vehicle');
        $this->_redirect($url);
    }

    /**
     * Set vehicle on search form
     */
    public function  postDispatch()
    {
        parent::postDispatch();
        $form = $this->view->search;

        $element = new Zend_Form_Element_Hidden('vehicle');
        $element->setValue($this->_getParam('vehicle'));
        $form->addElement($element);

        $element->setDecorators(array('ViewHelper'));
    }
}