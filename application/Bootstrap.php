<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoloader() {
        $options = $this->getOptions();
        $loader = new Zend_Application_Module_Autoloader(array(
                    'basePath' => APPLICATION_PATH,
                    'namespace' => $options['appnamespace'],
                ));
    }

    /**
     * http://code.google.com/p/dnna-zend-lib/
     */
    protected function _initDnnaLib() {
        $loader = new Zend_Application_Module_Autoloader(array(
                    'basePath' => APPLICATION_PATH.'/../library/Dnna',
                    'namespace' => 'Dnna',
                ));
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/../library/Dnna/controllers/helpers',
                                                      'Dnna_Action_Helper');
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/../library/Dnna/controllers/helpers/Rest',
                                                      'Dnna_Action_Helper_Rest');
        $this->bootstrap('view');
        $this->getResource('view')->addHelperPath(APPLICATION_PATH.'/../library/Dnna/views/helpers', 'Dnna_View_Helper');
    }

    protected function _initCache() {
        $frontendOptions = array(
            'lifetime' => 7200, // cache lifetime of 2 hours
            'automatic_serialization' => true
        );

        // Μετατρέπουμε το directory separator σε Unix based (παίζει και στα Windows έτσι)
        if (DIRECTORY_SEPARATOR !== '/') {
            $cachedir = str_replace(DIRECTORY_SEPARATOR, '/', realpath(sys_get_temp_dir()));
        } else {
            $cachedir = realpath(sys_get_temp_dir());
        }
        Zend_Registry::set('cachePath', $cachedir);
        $backendOptions = array(
            'cache_dir' => $cachedir
        );

        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
    }

    protected function _initTimezone() {
        $options = $this->getOptions();
        date_default_timezone_set($options['phpSettings']['date']['timezone']);
    }

    protected function _initZendLocale() {
        Zend_Registry::set('Zend_Locale', new Zend_Locale('el_GR'));
    }

    protected function _initRssRoute() {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $route = new Application_Plugin_Route(array(), $front->getDispatcher(), $front->getRequest());
        $router->addRoute('feed', $route);
    }

    protected function _initDoctrine() {
        if ($this->hasPluginResource('doctrine2')) {
            //doctrine autoloader
            include_once(APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php'); // Για να μη βγάζει error στο Γερμανικό server
            $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', APPLICATION_PATH . '/../library/');
            $classLoader->register();
            $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', APPLICATION_PATH . '/../library/');
            $classLoader->register();
            $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', APPLICATION_PATH . '/../library/');
            $classLoader->register();
            include_once('Doctrine/DBAL/Event/Listeners/MysqlSessionInit.php'); // Για να μη βγάζει error σε κάποια servers

            include_once(APPLICATION_PATH . '/plugins/GreekFloatType.php'); // Για να φορτώσει το GreekFloatType
            include_once(APPLICATION_PATH . '/plugins/GreekPercentageType.php'); // Για να φορτώσει το GreekPercentageType
            include_once(APPLICATION_PATH . '/plugins/EDateTimeType.php'); // Για να φορτώσει το EDateTimeType
            $doctrine2Resource = $this->getPluginResource('doctrine2');
            $doctrine2Resource->init();
            $em = $doctrine2Resource->getEntityManager();
            Zend_Registry::set("entityManager", $em);
            include_once(APPLICATION_PATH . '/plugins/BlobType.php'); // Override BlobType

            // Init extensions
            $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', APPLICATION_PATH . '/../library/');
            $classLoader->register();
        }
    }

    protected function _initPaginator() {
        $config = $this->getOptions();
        $resultsPerPage = (int) $config['resources']['view']['resultsPerPage'];
        Zend_Paginator::setDefaultItemCountPerPage($resultsPerPage);
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                'pagination_control.phtml'
        );
        $this->bootstrap('view');
        $this->getResource('view')->addScriptPath(APPLICATION_PATH . '/layouts/scripts');
    }
}

?>