<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Application_Plugin_AclPlugin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if ('api' != $request->getModuleName() || strtolower($request->getControllerName()) === 'index') {
            // If not in this module, return early
            return;
        }
        $user = false;
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Teiath"');
            Zend_Registry::set('user', false);
            $this->redirectToError(new Exception('AuthorizationRequired', 401));
            return;
        } else {
            $user = Zend_Registry::get('entityManager')->getRepository('Application_Model_User')->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            //$user = Zend_Registry::get('entityManager')->getRepository('Application_Model_User')->findOneBy(array('_username' => $_SERVER['PHP_AUTH_USER']));
            Zend_Registry::set('user', $user);
        }
        if(Zend_Registry::get('user') == false) {
            $this->redirectToError(new Exception('InvalidCredentials', 401));
            return;
        }
    }

    protected function redirectToError($exception) {
        // Repoint the request to the default error handler
        $request = $this->getRequest();
        $request->setControllerName('error');
        $request->setActionName('error');

        // Set up the error handler
        $error = new Zend_Controller_Plugin_ErrorHandler();
        $error->type = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER;
        $error->request = clone($request);
        $error->exception = $exception;
        $request->setParam('error_handler', $error);
    }
}
?>