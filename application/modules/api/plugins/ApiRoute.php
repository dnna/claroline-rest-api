<?php
/**
 * @author Dimosthenis Nikoudis <dnna@dnna.gr>
 */
class Api_Plugin_ApiRoute extends Zend_Rest_Route {
    public function match($request, $partial = false) {
        $pathInfo = $request->getPathInfo();
        $pathInfo = preg_replace("/(\/)\\1+/", "$1", $pathInfo);
        $request->setPathInfo($pathInfo);
        $extparams = array();
        if(strpos($pathInfo, '.') !== false) {
            // Αφαίρεση της κατάληξης ώστε να μην επιρεάσει την διαδικασία
            $extparams['format'] = substr(strrchr($pathInfo, '.'), 1);
            $pathInfo = substr($pathInfo, 0, -strlen(strrchr($pathInfo, '.'))); // Αφαίρεση του extension
        }
        $requestClass = get_class($request);
        $newRequest = new $requestClass();
        $newRequest->setPathInfo($pathInfo);
        $newRequest->setParamSources(array());
        $newRequest->setParams(array());
        $params = parent::match($newRequest, $partial); // Array merge
        if(!in_array($params['module'], $this->_restfulModules)) {
            return false;
        }
        $params = $params + $extparams;
        if($params != false) {
            $newcontroller = $this->getNewController($params);
            if($newcontroller != null) {
                $params['controller'] = $newcontroller;
                if(isset($params['id'])) {
                    $action = parent::match($request);
                    $action = $action['action'];
                    $params['id'] = null;
                    if($action === 'get') {
                        $params['action'] = 'index';
                    } else {
                        $params['action'] = $action;
                    }
                } else {
                    $keys = array_keys($params);
                    $values = array_values($params);
                    if(isset($params['module']) && isset($keys[3])) {
                        $params[$keys[3]] = null;
                        $params['id'] = $values[3];
                    } else if(!isset($params['module']) && isset($keys[2])) {
                        $params[$keys[2]] = null;
                        $params['id'] = $values[2];
                    }
                }
            }
        }
        if($params['action'] === 'index' && isset($params['id'])) {
            $params['action'] = 'get';
        }

        $params = $params + $request->getParams();
        // Αλλαγή του action σε schema αν υπάρχει η παράμετρος schema
        if((isset($params['id']) && $params['id'] === 'schema') || isset($params['schema'])) {
            $params['action'] = 'schema';
            $params['format'] = 'xml';
        }
        return $params;
    }

    public function assemble($data = array(), $reset = false, $encode = true) {
        $url = strtolower(parent::assemble($data, $reset, $encode));
        if(!isset($data['controller'])) {
            $params = $this->_front->getRequest()->getParams();
            $oldController = $params['controller'];
        } else {
            $oldController = $data['controller'];
        }
        $oldController = strtolower($oldController);
        if(strpos($oldController, '_') !== false) {
            $controllerParts = explode('_', $oldController);
            $newController = str_replace('_', '/', $oldController);
            if(!isset($data['controller'])) {
                $url = str_replace($controllerParts[0], $newController, $url);
            } else {
                $url = str_replace($oldController, $newController, $url);
            }
        }
        return $url;
    }

    protected function getNewController($params) {
        $controllerdir = $this->_front->getControllerDirectory('api');
        if(is_dir($controllerdir.'/'.ucfirst($params['controller']))) {
            $params['controller'] = ucfirst($params['controller']);
            $controllerdir = $controllerdir.'/'.ucfirst($params['controller']);
        } else if(is_dir($controllerdir.'/'.strtolower($params['controller']))) {
            $params['controller'] = strtolower($params['controller']);
            $controllerdir = $controllerdir.'/'.strtolower($params['controller']);
        } else {
            return null;
        }

        if(isset($params['id'])) {
            $case[1] = $controllerdir.'/'.ucfirst($params['id'].'Controller.php');
            if(file_exists($case[1])) {
                $newcontrollerpath =  $case[1];
            }
        } else {
            $keys = array_keys($params);
            if(isset($params['module']) && isset($keys[3])) {
                $case[2] = $controllerdir.'/'.ucfirst($keys[3].'Controller.php');
            } else if(!isset($params['module']) && isset($keys[2])) {
                $case[3] = $controllerdir.'/'.ucfirst($keys[2].'Controller.php');
            }

            if(isset($case[2]) && file_exists($case[2])) {
                $newcontrollerpath = $case[2];
            } else if(isset($case[3]) && file_exists($case[3])) {
                $newcontrollerpath = $case[3]; 
            }
        }

        if(isset($newcontrollerpath)) {
            $newcontrollerpath = $params['controller'].'_'.basename($newcontrollerpath);
            return substr($newcontrollerpath, 0, -strlen(strrchr($newcontrollerpath, 'Controller.php')));
        } else {
            return null;
        }
    }
}
?>