<?php
use Doctrine\ORM\EntityRepository;

/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Application_Model_Repositories_Users extends Application_Model_Repositories_BaseRepository {
    /**
     * Sanitizes ldap search strings.
     * See rfc2254
     * @link http://www.faqs.org/rfcs/rfc2254.html
     * @since 1.5.1 and 1.4.5
     * @param string $string
     * @return string sanitized string
     * @author Squirrelmail Team
     */
    protected function ldapspecialchars($string) {
        $sanitized=array('\\' => '\5c',
                         '*' => '\2a',
                         '(' => '\28',
                         ')' => '\29',
                         "\x00" => '\00');

        return str_replace(array_keys($sanitized),array_values($sanitized),$string);
    }

    /**
     * @return Application_Model_User
     */
    public function findOneByUsername($username) {
        $dbUser = parent::findOneBy(array('_username' => $username));
        if($dbUser != null) {
            return $dbUser;
        } else {
            // Search LDAP
            /*$ldap=$this->getLdapConn();
			$attributes = array('uid', 'cn;lang-el', 'mail');
			$search = @ldap_search($ldap->getResource(), 'ou=people,dc=teiath,dc=gr', '(&(objectClass=posixAccount)(uid=*'.$this->ldapspecialchars($username).'*))');
			$userArray = ldap_get_entries($ldap->getResource(), $search);
			if(isset($userArray[0])) {
				return $this->createUserFromLDAPEntry($userArray[0]);
			} else {
				return null;
			}*/
            throw new Exception('UserRetrievalError', '500');
        }
    }

    /**
     * @return Application_Model_User
     */
    public function findByToken($token) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u');
        $qb->from('Application_Model_User', 'u');

        $qb->andWhere('u._token = :token');
        $qb->setParameter('token', $token);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch(Exception $e) {
            return null;
        }
    }
    
    public function authenticate($username, $password) {
        // Auth adapter
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $options = $bootstrap->getOptions();
        $options = $options['ldap'];
        $authAdapter = new Zend_Auth_Adapter_Ldap($options);
        $authAdapter->setIdentity($username);
        $authAdapter->setCredential($password);
        
        // Authenticate based on the adapter
        $result = $authAdapter->authenticate($authAdapter);
        if(!$result->isValid()) {
            return false;
        } else {
            return $this->findOneByUsername($username);
        }
    }

    protected function createUserFromLDAPEntry($ldapArray) {
        if(!isset($ldapArray['cn;lang-el'][0])) {
            return null;
        }
        $mapping = Array(
                'realname' => $ldapArray['cn;lang-el'][0],
                'username' => $ldapArray['uid'][0],
                //'email' => $ldapArray['mail'][0]
                );
        $userObject = new Application_Model_User($mapping);

        return $userObject;
    }
}
?>