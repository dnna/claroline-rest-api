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

        $courseCode = $this->_request->getUserParam('course');
        if($courseCode == null) {
            throw new Exception('CourseParameterRequired', 404);
        }
        $course = Zend_Registry::get('entityManager')->getRepository('Application_Model_Course')->findOneBy(array('_code' => $courseCode));
        if($course == null) {
            throw new Exception('CourseNotFound', 404);
        }
        $groups = $course->get_groups();
        $this->_helper->Index($this, $groups, 'groups', array('id' => 'get_id'));
    }

    public function getAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $object = Zend_Registry::get('entityManager')->getRepository('Application_Model_Group')->find($this->_request->getParam('id'));
        if(!isset($object)) {
            throw new Exception('GroupNotFound', 404);
        }
        if(!$object->get_users()->contains(Zend_Registry::get('user'))) {
            throw new Exception('NotGroupMember', 401);
        }
        $this->_helper->Get($this, $object, new Dnna_Form_AutoForm('Application_Model_Group', $this->view), 'group');
    }

    public function schemaAction() {
        echo $this->_helper->generateXsd($this, new Dnna_Form_AutoForm('Application_Model_Group', $this->view), 'group');
    }
}
?>