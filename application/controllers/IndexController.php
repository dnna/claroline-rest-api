<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->pageTitle = "Webservices αυθεντικοποίησης του ΤΕΙ Αθήνας";
    }

    public function indexAction()
    {
        $this->view->apiIndex = Dnna_Model_ApiIndex::getApiIndex('api');
    }
}