<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Application_Plugin_LayoutPlugin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if($request->getUserParam('stripped') == 'true') {
            $layout= Zend_Controller_Action_HelperBroker::getStaticHelper('Layout');
            $layout->disableLayout();
        } else if($request->getUserParam('print') == 'true' || $request->getUserParam('barebone') == 'true') {
            $layout= Zend_Controller_Action_HelperBroker::getStaticHelper('Layout');
            $layout->setLayout('barebone');
        }
    }
}
?>