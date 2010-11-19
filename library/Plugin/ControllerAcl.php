<?php
/**
 * Plugin for ACL
 *
 * @author marcelo.jacobus
 */
class Plugin_ControllerAcl extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getControllerName() == 'user') {
            die('oops');
        }


        
    }
}