<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 * @Entity(readOnly=true) @Table(name="faculte")
 */
class Application_Model_Department extends Dnna_Model_Object {
    /**
     * @Id
     * @Column (name="id", type="string")
     * @FormFieldType Hidden
     * @FormFieldDisabled true
    */
    protected $_id;
    /**
     * @Column (name="code", type="string")
     * @FormFieldLabel Κωδικός Τμήματος
     * @FormFieldDisabled true
    */
    protected $_code;
    /**
     * @Column (name="name", type="string")
     * @FormFieldLabel Όνομα Τμήματος
     * @FormFieldDisabled true
    */
    protected $_name;
    /**
     * @OneToMany (targetEntity="Application_Model_Course", mappedBy="_department")
     * @var Application_Model_Course
     */
    protected $_courses;
    /**
     * @OneToMany (targetEntity="Application_Model_User", mappedBy="_department")
     * @var Application_Model_User
     */
    protected $_users;

    public function get_id() {
        return $this->_id;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function get_code() {
        return $this->_code;
    }

    public function set_code($_code) {
        $this->_code = $_code;
    }

    public function get_name() {
        return $this->_name;
    }

    public function set_name($_name) {
        $this->_name = $_name;
    }

    public function get_courses() {
        return $this->_courses;
    }

    public function set_courses($_courses) {
        $this->_courses = $_courses;
    }

    public function get_users() {
        return $this->_users;
    }

    public function set_users($_users) {
        $this->_users = $_users;
    }

    public function __toString() {
        return $this->get_name();
    }
}
?>