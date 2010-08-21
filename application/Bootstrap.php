<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Inicia o Doctrine
     * @return Doctrine_Manager
     */
    protected function _initDoctrine()
    {

        $loader = $this->getApplication()->getAutoloader();
        $loader->pushAutoloader(array('Doctrine_Core', 'modelsAutoload'));
        $loader->registerNamespace('sfYaml');
        $loader->pushAutoloader(array('Doctrine_Core', 'autoload'), 'sfYaml');

        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(
            Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE
        );
        $manager->setAttribute(
            Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true
        );
        $manager->setAttribute(
            Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, false
        );
        $manager->setAttribute(
            Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL
        );

        $manager->setCollate('utf8_unicode_ci');
        $manager->setCharset('utf8');

        $option = $this->getOption('doctrine');

        $conn = Doctrine_Manager::connection($option['dsn']);
        $conn->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);

        //tests, becase database wasnt created yet.
        try {
            $conn->execute('SET names UTF8');
        } catch (Exception $e) {

        }

        $path = $option['models_path'];
        Doctrine_Core::loadModels($path);
        Doctrine_Core::setModelsDirectory($path);

        return $conn;
    }

    /**
     * Autoloader for Admin module
     */
    public function _initAdminModuleAutoloader()
    {
        //TODO: take this config to application.ini
        $resourceLoader = new Zend_Loader_Autoloader_Resource(
                array(
                    'basePath' => APPLICATION_PATH . '/modules/admin',
                    'namespace' => 'Admin',
                )
        );
        $resourceLoader->addResourceType('model', 'models/', 'Model');
        $resourceLoader->addResourceType('form', 'forms/', 'Form');
        return $resourceLoader;
    }

    /**
     * Autoloader for Admin module
     */
    public function _initDefaultModuleAutoloader()
    {
        //TODO: take this config to application.ini
        $resourceLoader = new Zend_Loader_Autoloader_Resource(
                array(
                    'basePath' => APPLICATION_PATH . '/modules/default',
                    'namespace' => '',
                )
        );
        $resourceLoader->addResourceType('model', 'models/', 'Model');
        $resourceLoader->addResourceType('form', 'forms/', 'Form');
        return $resourceLoader;
    }

    /**
     * Init Doctrine Query Profiler
     * @return Doctrine_Connection_Profiler
     */
    protected function _initDoctrineConnectionProfiler()
    {
        $profiler = new Doctrine_Connection_Profiler();
        $conn = Doctrine_Manager::connection();
        $conn->setListener($profiler);
        Zend_Registry::set('doctrineConnectionProfiler', $profiler);
        return $profiler;
    }

    /**
     * Init security salt
     */
    protected function _initSecuritySalt()
    {
        $option = $this->getOption('security');
        $salt = $option['password']['salt'];
        Zend_Registry::set('securitySalt', $salt);
    }

    public function __initCacheDir()
    {
        $cacheDir = APPLICATION_PATH . '/../tmp/cache';
        $backend = new Zend_Cache_Backend_File(array('cache_dir' => $cacheDir));
        $cache = Zend_Cache::factory('Core', $backend);
        Zend_Locale::setCache($cache);
    }

}

