<?php
/**
 * Autoloads a model inside a module
 *
 * @author marcelo
 */
class App_Autoloader_ModularModelLoader implements Zend_Loader_Autoloader_Interface
{

    /**
     * The class name should be given this way:
     * [module_]<ModelName>_Model, => Admin_User_Model
     * User_Model
     *
     * Return true if it loads a class or false if it doesn't
     *
     * @param string $class  class name
     * @return bool
     */
    public function autoload($class)
    {
        $class = str_replace('_Model','',$class);

        $parts = explode('_', $class);

        if (count($parts) > 1) {
            $module = strtolower($parts[0]);
            $model = $parts[1];
        } else {
            $module = 'default';
            $model = $class;
        }

        $modulePath = Zend_Controller_Front::getInstance()->getModuleDirectory($module) ;

        $file = $modulePath . '/models/'. $model . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
}