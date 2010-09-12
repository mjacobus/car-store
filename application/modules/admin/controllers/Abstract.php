<?php

/**
 * Commum tasks for admin controller
 *
 * @author marcelo.jacobus
 */
abstract class Admin_Controller_Abstract extends Zend_Controller_Action
{

    /**
     * Enforces Authentication
     */
    protected function requireAuthentication()
    {
        if (Model_Authentication::isLogged() == false) {

            $base = $this->getBaseUrl();
            $url = $this->view->url();

            if (strlen($base)) {
                $url = explode($base, $url);
                if (isset($url[1])) {
                    $url = $url[1];
                } else if (count($url)) {
                    $url[0];
                } else {
                    $url = '/';
                }
            }

            $loginUrl = 'authentication';
            $this->_redirect("$loginUrl?redirect=$url");
        }
    }

    /**
     * Get base url
     * @return string
     */
    public function getBaseUrl()
    {
        return Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    /**
     * Enforces authentication
     */
    public function init()
    {
        $this->requireAuthentication();
    }

    /**
     * Index Action
     */
    public function indexAction()
    {
        $namespace = implode('_', array(
                $this->getRequest()->getModuleName(),
                $this->getRequest()->getControllerName(),
                'search'
            ));

        $session = new Zend_Session_Namespace($namespace);

        $search = $this->_getAllParams();

        if ($this->lastActionWasCrud()) {
            $search = $session->params;
            if (is_array($search)) {
                foreach ($search as $key => $value) {
                    $this->getRequest()->setParam($key, $value);
                }
            }
        } else {
            $session->params = $search;
        }

        if (!is_array($search)) {
            $search = $this->_getAllParams();
        }

        $pager = new Doctrine_Pager(
                $this->model->getListingDql($search),
                $this->_getParam('page', 1),
                $this->_getParam('per-page', 30)
        );

        $this->view->registers = $pager->execute();
        $this->view->pagination()->setPager($pager);
    }

    /**
     * Edit Action
     */
    public function editAction()
    {
        $this->processForm($this->getRequest()->getParams());
    }

    /**
     * @Add Action
     */
    public function addAction()
    {
        $this->processForm($this->getRequest()->getParams());
    }

    /**
     * Process Form
     * @param int $params
     *
     */
    public function processForm(array $params = null)
    {
        $form = $this->view->form = $this->model->getForm($params);

        $this->view->inlineScript(Zend_View_Helper_HeadScript::SCRIPT, $this->model->getForm()->getInlineScript());

        if ($this->getRequest()->isPost()) {

            $id = $this->getRequest()->getParam('id');
            $values = $this->getRequest()->getPost();
            $id = $this->model->save($values, $id);
            if ($id) {
                $this->postSave($id);
            } else {
                $this->view->errors($this->model->getMessages());
            }
        } else {
            if (array_key_exists('id', $params)) {
                $this->model->populateForm($params['id']);
            }
        }
    }

    /**
     * After delete, shows message
     */
    public function postDelete($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        $this->_redirect($this->getRequest()->getModuleName()
            . '/' . $this->getRequest()->getControllerName()
        );
    }

    /**
     * After save succeed, redirects
     * Displays ok message and redirects
     */
    public function postSave($savedRecordId = null)
    {
        $this->view->flash($this->model->getMessages());
        $this->_redirect($this->getRequest()->getModuleName()
            . '/' . $this->getRequest()->getControllerName()
        );
    }

    /**
     * Del Action
     */
    public function delAction()
    {
        if ($this->getRequest()->isPost()) {

            if (strtolower($this->getRequest()->getPost('confirm')) == 'sim') {
                $id = $this->getRequest()->getPost('id');

                $id = $this->model->deleteRecord($id);
                if (!$id) {
                    $this->view->flash()->setDivClass('error');
                }
            }

            $this->postDelete($id);
        }

        $params = $this->getRequest()->getParams();
        $this->view->errors($this->model->getDelConfirmationMessage($params['id']));
        $this->view->form = $this->model->getDelForm($params);
    }

    /**
     * Set a bit of things
     */
    public function postDispatch()
    {
        $this->setSearchForm();
        $this->setLayout();
    }

    /**
     * Set the search form
     */
    public function setSearchForm()
    {
        $form = $this->model->getSearchForm();
        $form->populate($this->_getAllParams());
        $form->setAction($this->view->url(array(
                'module' => $this->getRequest()->getModuleName(),
                'controller' => $this->getRequest()->getControllerName(),
                'action' => null),
                null,
                true
        ));
        $this->view->search = $form;
    }

    /**
     * Set layout to admin
     */
    public function setLayout($layout = 'admin')
    {
        $this->_helper->layout->setLayout($layout);

        $view = $this->view;
        $view->headLink()
            ->appendStylesheet($view->baseUrl('/css/redmond/jquery-ui-1.8.2.custom.css'))
            ->appendStylesheet($view->baseUrl('/css/admin.css'))
            ->appendStylesheet($view->baseUrl('/css/jquery.wysiwyg.css'));
        $view->headScript()
            ->appendFile($view->baseUrl('/js/jquery-1.4.2.min.js'))
            ->appendFile($view->baseUrl('/js/jquery.wysiwyg.js'))
            ->appendFile($view->baseUrl('/js/jquery.validate.min.js'))
            ->appendFile($view->baseUrl('/js/jquery.validate.messages.js'))
            ->appendFile($view->baseUrl('/js/jquery-ui-1.8.2.datepicker.min.js'))
            ->appendFile($view->baseUrl('/js/jquery.maskMoney.js'))
            ->appendFile($view->baseUrl('/js/admin.js'));
    }

    /**
     * Get profiler
     * @return Doctine_Connection_Profiler
     */
    public function getDoctrineConnectionProfiler()
    {
        return Zend_Registry::get('doctrineConnectionProfiler');
    }

    /**
     * Checks whether the last action (HTTP_REFERER) was for editing,
     * adding or editing
     * @return bool
     */
    public function lastActionWasCrud()
    {
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {

            $lastUrl = $_SERVER['HTTP_REFERER'];

            $crudUrl = implode('/', array(
                    $this->getBaseUrl(),
                    $this->getRequest()->getModuleName(),
                    $this->getRequest()->getControllerName(),
                ));

            $pattern = '/' . preg_quote($crudUrl, '/') . '\/(view|edit|add|del)/';

            if (preg_match($pattern, $lastUrl)) {
                return true;
            }
        }
        return false;
    }

    public function preDispatch()
    {
        $this->view->tabs = array(
            'Veículos' => array('car', 'car-feature', 'car-image'),
            'Marcas' => array('brand'),
            'Imagens' => array('image-upload'),
        );
        $this->view->params = $this->_getAllParams();
    }

    /**
     * view Action
     */
    public function viewAction()
    {
        $register = $this->model->getRegister($this->_getParam('id'));
        $this->view->register = $register;
    }

}