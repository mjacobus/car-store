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
        $this->processForm($this->getRequest()->getParam('id', 0));
    }

    /**
     * @Add Action
     */
    public function addAction()
    {
        $this->processForm();
    }

    /**
     * Process Form
     * @param int $id
     *
     */
    public function processForm($id = null)
    {
        $form = $this->view->form = $this->model->getForm($id);

        $this->view->inlineScript(Zend_View_Helper_HeadScript::SCRIPT, $this->model->getForm()->getInlineScript());

        if ($this->getRequest()->isPost()) {

            $id = $this->getRequest()->getParam('id');
            $values = $this->getRequest()->getPost();
            if ($this->model->save($values,$id)) {
                $this->view->flash($this->model->getMessages());
                $this->_redirect($this->getRequest()->getModuleName()
                    . '/' . $this->getRequest()->getControllerName()
                );
            }
            $this->view->errors($this->model->getMessages());
        } else {
            if ($id !== null) {
                $this->model->populateForm($id);
            }
        }
    }

    /**
     * Del Action
     */
    public function delAction()
    {
        if ($this->getRequest()->isPost()) {

            if (strtolower($this->getRequest()->getPost('confirm')) == 'sim') {
                $id = $this->getRequest()->getPost('id');

                if (!$this->model->deleteRecord($id)) {
                    $this->view->flash()->setDivClass('error');
                }
                $this->view->flash($this->model->getMessages());
            }

            $this->_redirect($this->getRequest()->getModuleName()
                . '/' . $this->getRequest()->getControllerName()
            );
        }

        $id = $this->_getParam('id');
        $this->view->errors($this->model->getDelConfirmationMessage($id));
        $this->view->form = $this->model->getDelForm($id);
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
            ->appendFile($view->baseUrl('/js/jquery.maskedinput-1.2.2.min.js'))
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

            $pattern = '/' . preg_quote($crudUrl, '/') . '\/(edit|add|del)/';

            if (preg_match($pattern, $lastUrl)) {
                return true;
            }
        }
        return false;
    }

    public function preDispatch()
    {
        $this->view->tabs = array(
            'brand' => 'Marcas',
            'car' => 'Veículos',
        );
    }

}