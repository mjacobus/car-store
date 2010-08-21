<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_redirect('admin');
    }

    public function indexAction()
    {
        // action body
    }


}

