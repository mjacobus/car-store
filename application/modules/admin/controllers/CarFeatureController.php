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
    public function postSave($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        $url = $this->getRequest()->getModuleName()
            . '/' . $this->getRequest()->getControllerName();
        $url .= "/index/car/" . $this->_getParam('car');
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
        $url .= "/index/car/" . $this->_getParam('car');
        $this->_redirect($url);

    }

    /**
     * Set car on search form
     */
    public function  postDispatch()
    {
        parent::postDispatch();
        $form = $this->view->search;

        $element = new Zend_Form_Element_Hidden('car');
        $element->setValue($this->_getParam('car'));
        $form->addElement($element);

        $element->setDecorators(array('ViewHelper'));
    }

}