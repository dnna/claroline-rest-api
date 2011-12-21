<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Api_GroupsController extends Api_IndexController
{
    public function init() {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }

    public function indexAction() {
        $limit = $this->getRequest()->getParam('limit');
        if($limit == null) {
            $limit = $this->getRequest()->getParam('s');
        }

        $groups = Zend_Registry::get('user')->get_groups();
        //$groups = Zend_Registry::get('entityManager')->getRepository('Application_Model_Group')->findAll();
        $this->_helper->Index($this, $groups, 'groups', array('id' => 'get_id'));
    }

    public function getAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $object = Zend_Registry::get('entityManager')->getRepository('Application_Model_Group')->find($this->_request->getParam('id'));
        if(!isset($object)) {
            throw new Exception('GroupNotFound', 404);
        }
        $this->_helper->Get($this, $object, new Dnna_Form_AutoForm('Application_Model_Group', $this->view), 'group');
    }

    public function schemaAction() {
        echo $this->_helper->generateXsd($this, new Dnna_Form_AutoForm('Application_Model_Group', $this->view), 'group');
    }
}
?>