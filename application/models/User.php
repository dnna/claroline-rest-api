<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 * @Entity (repositoryClass="Application_Model_Repositories_Users", readOnly=true) @Table(name="user")
 * @HasLifecycleCallbacks
 */
class Application_Model_User extends Dnna_Model_Object {
    /**
     * @Id
     * @Column (name="user_id", type="string")
     */
    protected $_userid;
    /**
     * @Column (name="username", type="string")
     * @FormFieldType Hidden
     * @FormFieldDisabled true
     */
    protected $_username;
    /**
     * @Column (name="prenom", type="string")
     * @FormFieldLabel Όνομα
     * @FormFieldDisabled true
     */
    protected $_firstname;
    /**
     * @Column (name="nom", type="string")
     * @FormFieldLabel Επώνυμο
     * @FormFieldDisabled true
     */
    protected $_lastname;
    /**
     * @Column (name="email", type="string")
     * @FormFieldLabel E-mail
     * @FormFieldDisabled true
     */
    protected $_email;
    /**
     * @ManyToOne (targetEntity="Application_Model_Department", inversedBy="_users")
     * @JoinColumn (name="department", referencedColumnName="id")
     * @FormFieldLabel Τμήμα
     * @FormFieldType Recursive
     * @var Application_Model_Department
     */
    protected $_department;
    /**
     * @ManyToMany (targetEntity="Application_Model_Course", mappedBy="_users")
     * @FormFieldLabel Μαθήματα
     * @FormFieldType Recursive
     */
    protected $_courses;
    /**
     * @ManyToMany (targetEntity="Application_Model_Group", mappedBy="_users")
     */
    protected $_groups;

    public function get_userid() {
        return $this->_userid;
    }

    public function set_userid($_userid) {
        $this->_userid = $_userid;
    }

    public function get_username() {
        return $this->_username;
    }

    public function set_username($_username) {
        $this->_username = $_username;
    }

    public function get_firstname() {
        return $this->_firstname;
    }

    public function set_firstname($_firstname) {
        $this->_firstname = $_firstname;
    }

    public function get_lastname() {
        return $this->_lastname;
    }

    public function set_lastname($_lastname) {
        $this->_lastname = $_lastname;
    }

    public function get_email() {
        return $this->_email;
    }

    public function set_email($_email) {
        $this->_email = $_email;
    }

    public function get_department() {
        return $this->_department;
    }

    public function set_department($_department) {
        $this->_department = $_department;
    }

    public function get_courses() {
        return $this->_courses;
    }

    public function set_courses($_courses) {
        $this->_courses = $_courses;
    }

    public function get_groups() {
        return $this->_groups;
    }

    public function set_groups($_groups) {
        $this->_groups = $_groups;
    }

    public function __toString() {
        return $this->get_firstname().' '.$this->get_lastname();
    }
}
?>