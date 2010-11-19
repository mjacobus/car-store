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
     * @var Zend_Acl
     */
    protected $_acl = null;
    public function __construct()
    {
        $acl = new Zend_Acl();
        $acl->addRole(new Zend_Acl_Role('guest'));
        $acl->addRole(new Zend_Acl_Role('member'),'guest');
        $acl->addRole(new Zend_Acl_Role('admin'),'member');

        $acl->addResource(new Zend_Acl_Resource('module-default'));
        $acl->addResource(new Zend_Acl_Resource('module-admin'),'module-default');
        $acl->addResource(new Zend_Acl_Resource('model-admin-controller-user'),'module-admin');
        $acl->addResource(new Zend_Acl_Resource('model-admin-controller-user-action-add'),'model-admin-controller-user');

        $acl->allow('admin', 'model-admin-controller-user');
        
        $this->_acl = $acl;



        
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $user = Admin_Model_Authentication::getIdentity();
        $role = $user->Role->name;
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
    }
}