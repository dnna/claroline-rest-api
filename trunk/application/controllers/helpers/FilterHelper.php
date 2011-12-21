<?php
class Application_Action_Helper_FilterHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct(Zend_Controller_Action $controller, $filterForm) {
        $filterform = new $filterForm($controller->view);
        $filterform->isValid($controller->getRequest()->getParams());
        $filterform->setAttrib('id', 'filters');
        $filters = $controller->getRequest()->getParam('filters');
        $controller->view->filterform = $filterform;
        $controller->view->filters = $filters;
        return $filters;
    }
}
?>