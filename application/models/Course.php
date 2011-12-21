<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 * @Entity(readOnly=true) @Table(name="cours")
 */
class Application_Model_Course extends Dnna_Model_Object {
    /**
     * @Id
     * @Column (name="cours_id", type="string")
     * @FormFieldType Hidden
     * @FormFieldDisabled true
    */
    protected $_id;
    /**
     * @Id
     * @Column (name="code", type="string")
     * @FormFieldLabel Κωδικός Μαθήματος
     * @FormFieldDisabled true
     */
    protected $_code;
    /**
     * @Column (name="intitule", type="string")
     * @FormFieldLabel Τίτλος Μαθήματος
     * @FormFieldDisabled true
     */
    protected $_name;
    /**
     * @Column (name="description", type="string")
     * @FormFieldLabel Περιγραφή
     * @FormFieldDisabled true
     */
    protected $_description;
    /**
     * @ManyToOne (targetEntity="Application_Model_Department", inversedBy="_courses")
     * @JoinColumn (name="faculteid", referencedColumnName="id")
     * @var Application_Model_Department
     */
    protected $_department;
    /**
     * @ManyToMany (targetEntity="Application_Model_User", inversedBy="_courses")
     * @JoinTable (name="cours_user",
     *      joinColumns={@JoinColumn(name="cours_id", referencedColumnName="cours_id")},
     *      inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="user_id")}
     *      )
     */
    protected $_users;
    /**
     * @OneToMany (targetEntity="Application_Model_Group", mappedBy="_course")
     * @FormFieldLabel Όμαδες χρηστών όπου έχει εγγραφεί ο χρήστης
     * @FormFieldType Recursive
     * @var Application_Model_Group
     */
    protected $_groups;

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

    public function get_description() {
        return $this->_description;
    }

    public function set_description($_description) {
        $this->_description = $_description;
    }

    public function get_department() {
        return $this->_department;
    }

    public function set_department($_department) {
        $this->_department = $_department;
    }

    public function get_users() {
        return $this->_users;
    }

    public function set_users($_users) {
        $this->_users = $_users;
    }

    public function get_groups() {
        if(Zend_Registry::isRegistered('personalized')) {
            $user = Zend_Registry::get('user');
            return $this->_groups->filter(
                        function($entry) use ($user) {
                           if ($user->get_groups()->contains($entry)) {
                               return true;
                           }

                           return false;
                        }
                    );
        } else {
            return $this->_groups;
        }
    }

    public function set_groups($_groups) {
        $this->_groups = $_groups;
    }

    public function __toString() {
        return $this->get_name();
    }
}
?>