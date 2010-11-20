<?php

/**
 * Plugin for ACL
 *
 * @author marcelo.jacobus
 */
class Plugin_ControllerAcl extends Zend_Controller_Plugin_Abstract
{
   
    /**
     *
     * @param Zend_Controller_Request_Abstract $request 
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (Admin_Model_Authentication::isLogged()) {
            $user = Admin_Model_Authentication::getIdentity();
            $role = $user->Role->name;
        } else {
            $role = 'guest';
        }
        
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $resources = array(
            "module-$module-controller-$controller-action-$action",
            "module-$module-controller-$controller",
            "module-$module",
        );


        $acl = App_Acl::getInstance();

        foreach ($resources as $resource) {
            if ($acl->has($resource)) {
                if (!$acl->isAllowed($role, $resource)) {
                    $this->handleDenied($request);
                }
                break;
            }
        }

    }

    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function handleDenied(Zend_Controller_Request_Abstract $request)
    {
        $request->setModuleName('default')
            ->setControllerKey('error')
            ->setActionName('permission-denied');
    }

}