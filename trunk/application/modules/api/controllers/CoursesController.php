<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Api_CoursesController extends Api_IndexController
{
    const name = 'Μαθήματα';

    public function init() {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }

    public function indexAction() {
        $limit = $this->getRequest()->getParam('limit');
        if($limit == null) {
            $limit = $this->getRequest()->getParam('s');
        }

        $courses = Zend_Registry::get('entityManager')->getRepository('Application_Model_Course')->findAll();
        $this->_helper->Index($this, $courses, 'courses', array('code' => 'get_code'));
    }

    public function getAction() {
        $object = Zend_Registry::get('entityManager')->getRepository('Application_Model_Course')->findOneBy(array('_code' => $this->_request->getParam('id')));
        if(!isset($object)) {
            throw new Exception('CourseNotFound', 404);
        }
        if(!$object->get_users()->contains(Zend_Registry::get('user'))) {
            throw new Exception('NotEnrolledInCourse', 401);
        }
        $this->_helper->Get($this, $object, new Dnna_Form_AutoForm('Application_Model_Course', $this->view), 'course');
    }

    public function schemaAction() {
        echo $this->_helper->generateXsd($this, new Dnna_Form_AutoForm('Application_Model_Course', $this->view), 'course');
    }
}
?>