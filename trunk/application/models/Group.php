<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 * @Entity(readOnly=true) @Table(name="`group`")
 */
class Application_Model_Group extends Dnna_Model_Object {
    /**
     * @Id
     * @Column (name="id", type="string")
     * @FormFieldType Hidden
     * @FormFieldDisabled true
    */
    protected $_id;
    /**
     * @ManyToOne (targetEntity="Application_Model_Course", inversedBy="_groups")
     * @JoinColumn (name="course_id", referencedColumnName="cours_id")
     * @FormFieldLabel Μάθημα
     * @FormFieldType RecursiveId
     * @var Application_Model_Course
     */
    protected $_course;
    /**
     * @Column (name="name", type="string")
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
     * @Column (name="max_members", type="string")
     * @FormFieldLabel Μεγιστός Αριθμός Συμμετεχόντων
     * @FormFieldDisabled true
     */
    protected $_maxmembers;
    /**
     * @Column (name="secret_directory", type="string")
     * @FormFieldLabel Κρυφός Φάκελος
     * @FormFieldDisabled true
     */
    protected $_secretdirectory;
    /**
     * @ManyToMany (targetEntity="Application_Model_User", inversedBy="_groups")
     * @JoinTable (name="`group_members`",
     *      joinColumns={@JoinColumn(name="group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="user_id")}
     *      )
     */
    protected $_users;
    /**
     * @FormFieldLabel Ο σπουδαστής είναι εγγεγραμμένος
     * @FormFieldDisabled true
    */
    protected $_registered;

    public function get_id() {
        return $this->_id;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function get_course() {
        return $this->_course;
    }

    public function set_course($_course) {
        $this->_course = $_course;
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

    public function get_maxmembers() {
        return $this->_maxmembers;
    }

    public function set_maxmembers($_maxmembers) {
        $this->_maxmembers = $_maxmembers;
    }

    public function get_secretdirectory() {
        return $this->_secretdirectory;
    }

    public function set_secretdirectory($_secretdirectory) {
        $this->_secretdirectory = $_secretdirectory;
    }

    public function get_users() {
        return $this->_users;
    }

    public function set_users($_users) {
        $this->_users = $_users;
    }

    public function get_registered() {
        if($this->get_users()->contains(Zend_Registry::get('user'))) {
            $this->_registered = 'true';
        }
        return $this->_registered;
    }

    public function __toString() {
        return $this->get_name();
    }
}
?>