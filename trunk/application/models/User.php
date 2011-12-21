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
     * @FormFieldLabel Ονοματεπώνυμο
     * @FormFieldDisabled true
     */
    protected $_realname;
    /**
     * @ManyToMany (targetEntity="Application_Model_Course", mappedBy="_users")
     */
    protected $_courses;
    /**
     * @ManyToMany (targetEntity="Application_Model_Group", mappedBy="_users")
     */
    protected $_groups;

    public function hasRole($rolename) {
        foreach($this->_roles as $curRole) {
            if($curRole->get_rolename() === $rolename) {
                return true;
            }
        }
        return false;
    }
    
    public function getDominantRole() {
        if($this->hasRole('elke')) {
            return 'elke';
        } else if($this->hasRole('professor')) {
            return 'professor';
        } else {
            throw new Exception('Σφάλμα στην ανάκτηση του κυρίαρχου ρόλου του χρήστη');
        }
    }

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

    public function get_realname() {
        return $this->_realname;
    }
    
    public function get_realnameLowercase() {
        $str = mb_convert_case($this->get_realname(), MB_CASE_TITLE, "UTF-8").' ';
        $str = preg_replace('/σ\s/i', 'ς ', $str);
        return trim($str);
    }

    public function get_realnameCondensed() {
        return str_replace(" ", "", $this->get_realname());
    }

    public function set_realname($_realname) {
        $this->_realname = $_realname;
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
        return $this->get_realname();
    }
}
?>