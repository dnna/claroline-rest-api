<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

// Shutdown function to prevent APC bug #16745 (fatal error in sessions)
function shutdown() {
    session_write_close();
}
register_shutdown_function('shutdown');

// Define path to application directory
if(DIRECTORY_SEPARATOR !== '/') {
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__)) . '/../application'));
} else {
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
}

// Define application environment
define('APPLICATION_ENV', 'development');
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
try{
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );
    $application->bootstrap()
                ->run();
} catch(Exception $e) {
    echo '<html>
            <head>
            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
            <meta http-equiv="Content-Language" content="el">
            </head>
            <body>
                <center>
                    Παρουσιάστηκε σημαντικό σφάλμα κατά την εκκίνηση της εφαρμογής.<BR>Ο κωδικός σφάλματος ήταν:<BR>'.$e->getCode().' '.$e->getMessage().
                '</center>
            </body>
           </html>';
}