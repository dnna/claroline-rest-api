<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Api_IndexController extends Zend_Rest_Controller
{
    const name = 'Ευρετήριο API';

    protected $_allowAnonymous = false;
    protected $_returnhtml = false;

    public function init()
    {
        $this->view->request = $this->_request;
        $this->view->response = $this->_response;
        $this->view->format = $this->_request->getParam('format');

        // We remove ALL contexts before adding our own. This makes sure we ONLY use the contexts we supply here.
        $this->_helper->restContextSwitch()
            ->clearContexts();
        if($this->_returnhtml != true) {
            $this->_helper->restContextSwitch()
                ->clearContexts()
                ->addContext(
                    'xml',
                    array('suffix' => 'xml', 'headers' => array('Content-Type' => 'text/xml')))
                ->addContext(
                    'json',
                    array('suffix' => 'json', 'headers' => array('Content-Type' => 'application/json')))
                ->clearActionContexts()
                ->addGlobalContext(array('xml', 'json'))
                ->setDefaultContext('xml')
                ->initContext();
        }
    }

    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $apiindex = array();
        foreach(Dnna_Model_ApiIndex::getApiIndex('api') as $curApiIndex) {
            $apiindex[] = new Dnna_Model_ApiIndex($curApiIndex['id'], $curApiIndex['name']);
        }
        $this->_helper->Index($this, $apiindex, 'api', array('resource' => 'get_id'));
    }

    public function getAction() {
        throw new Exception('Δεν υποστηρίζεται');
    }

    public function postAction() {
        throw new Exception('Δεν υποστηρίζεται');
    }

    public function putAction() {
        throw new Exception('Δεν υποστηρίζεται');
    }

    public function deleteAction() {
        throw new Exception('Δεν υποστηρίζεται');
    }

    public function schemaAction() {
        throw new Exception('Δεν έχει οριστεί schema για το συγκεκριμένο resource.');
    }

    public function get_returnhtml() {
        return $this->_returnhtml;
    }

    protected function utf8_urldecode($str) {
        $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
        return html_entity_decode($str,null,'UTF-8');
    }
}
?>