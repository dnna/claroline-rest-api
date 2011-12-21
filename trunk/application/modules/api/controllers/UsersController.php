<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Api_UsersController extends Api_IndexController
{
    public function init() {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }

    public function indexAction() {
        throw new Exception('Not allowed', '401');
    }

    public function getAction() {
        $username = $this->_request->getParam('id');
        if($username === 'me') {
            $object = Zend_Registry::get('user');
        } else {
            throw new Exception('CanOnlyViewSelf', '401');
            $object = Zend_Registry::get('entityManager')->getRepository('Application_Model_User')->findOneBy(array('_username' => $username));
        }
        Zend_Registry::set('personalized', true);
        $this->_helper->Get($this, $object, new Dnna_Form_AutoForm('Application_Model_User', $this->view), 'user');
    }

    public function schemaAction() {
        echo $this->_helper->generateXsd($this, new Dnna_Form_AutoForm('Application_Model_User', $this->view), 'user');
    }
}
?>