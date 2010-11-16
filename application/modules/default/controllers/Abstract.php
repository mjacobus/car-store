<?php

/**
 * Commum tasks for admin controller
 *
 * @author marcelo.jacobus
 */
abstract class Controller_Abstract extends Zend_Controller_Action
{

    /**
     * The absolute base url
     * @var string
     */
    protected $_absoluteBaseUrl;

    /**
     * Enforces Authentication
     */
    protected function requireAuthentication()
    {
        if (Model_Authentication::isLogged() == false) {
            $url = explode($this->getBaseUrl(), $this->view->url());
            if (isset($url[1])) {
                $url = $url[1];
            } else if (count($url)) {
                $url[0];
            } else {
                $url = '/';
            }
            $loginUrl = 'authentication';
            $this->_redirect("$loginUrl?redirect=$url"); // . '?redirect=' . $url);
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
     * Set a bit of things
     */
    public function postDispatch()
    {
        $this->setLayout();
    }

    /**
     * Set layout to admin
     */
    public function setLayout($layout = 'default')
    {
        $this->_helper->layout->setLayout($layout);

        $view = $this->view;
        $view->headLink()->appendStylesheet($view->baseUrl('/css/default.css'));
        $view->headScript()
            ->appendFile($view->baseUrl('/js/jquery-1.4.2.min.js'))
            ->appendFile($view->baseUrl('/js/jquery.validate.min.js'))
            ->appendFile($view->baseUrl('/js/jquery.validate.messages.js'))
            ->appendFile($view->baseUrl('/js/default.js'));
    }

    /**
     * Return url in format protocol://host:port
     * @return string
     */
    public function getAbsoluteBaseUrl($append = '/')
    {
        if ($this->_absoluteBaseUrl == null) {

            $protocol = explode('/', $_SERVER['SERVER_PROTOCOL']);
            $protocol = strtolower($protocol[0]) . '://';

            $port = ':' . $_SERVER['SERVER_PORT'];

            if (($protocol == 'http://' && $port == ':80') || ($protocol == 'https://' && $port == ':443')) {
                $port = '';
            }

            $host = $_SERVER['HTTP_HOST'];
            $this->_absoluteBaseUrl = $protocol . $host . $port;
        }

        return $this->_absoluteBaseUrl . $append;
    }

}