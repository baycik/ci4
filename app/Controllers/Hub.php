<?php

namespace App\Controllers;
//use CodeIgniter\API\ResponseTrait;
/**
 * Description of Hub
 *
 * @author Baycik
 */
class Hub extends BaseController {
    
    public function on() {
        $uri = current_url(true);
        $class_name=$uri->getSegment(1);
        $method_name=$uri->getSegment(2);
        $plugin_name=$this->plugin_override($class_name,$method_name);
        $class_path="App\Models\\$class_name";
        $this->execute($class_path,$class_name,$method_name);
    }
    
    private function execute($class_path,$class_name,$method_name){
        try{
            $this->$class_name=model($class_path,true);
        } catch (Exception $ex) {
            //die;;
        }
        if( !$this->$class_name ){
            throw new \Exception("Model '$class_path' not found");
        }
	if( isset($this->$class_name->min_level) ){
	    $this->set_level($this->$class_name->min_level);
            //return $this->failUnauthorized('Invalid Auth token');
	}
        
        $arguments=[];
        $this->$class_name->Hub=$this;
        if( !$method_name ){
            $method_name='index';
        }
        $this->$class_name->$method_name(...$arguments);
    }
    
    private function plugin_override($class_name,$method_name){
        return $this->plugins['trigger_before']["$class_name::$method_name"] ?? $this->plugins['trigger_before']["$class_name"] ?? null;
    }

    public function svar($name, $value = NULL) {
	if ( isset($value) ) {
            $this->session->set($name, $value);
	}
	return $this->session->get($name);
    }

    
    public function msg( $msg ){
        $this->response->setBody($msg);
        $this->response->send();
    }
}
