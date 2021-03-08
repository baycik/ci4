<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class BaseController extends Controller {
    public $level_names=["Нет доступа","Ограниченный","Менеджер","Бухгалтер","Администратор"];
    protected $helpers = [];

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->authorize();
        $this->pluginsFill();
    }
    
    private function pluginsFill(){
        $this->plugins = $this->session->get('plugins');
        if( $this->plugins['trigger_before'] ){
            foreach( $this->plugins['trigger_before'] as $plugin_class ){
                
            }
        }
    }
    
    public function authorize(){
        $user_id=$this->session->get('user_id');
        if( !$user_id ){
            $uri = current_url(true);
            $class_name=$uri->getSegment(1);
            if( $class_name!='User' ){
                $this->session->set('_from_uri',$uri);
                $this->session->set('_from_request',$_REQUEST);
                redirect()->to("User/login")->send();die;
            }
        }
    }
    
    public function set_level( $allowed_level ){
        $user_level=(int) session('user_level');
        if ( $user_level >= $allowed_level ) {
            return true;
        }
        $level_names=["Нет доступа","Ограниченный","Менеджер","Бухгалтер","Администратор"];
        if( $user_level==0 ){
            $this->authorize();
        } else {
            $description ="Текущий уровень '{$level_names[$user_level]}'. Необходим '{$level_names[$allowed_level]}'";
	    $this->response->setStatusCode(401);
            $this->response->setBody($description);
            $this->response->send();
	}
        die;
    }

    
    public function load_model( $name ){
	$trigger_before=$this->svar('trigger_before');
	$trigger_after=$this->svar('trigger_after');
	if( isset($trigger_before[$name]) || isset($trigger_after[$name]) ){
	    $name=isset($trigger_before[$name])?$trigger_before[$name]:$trigger_after[$name];
            $this->load->add_package_path(APPPATH.'plugins/'.$name, 1);
            $this->load->add_package_path(BAY_STORAGE.'plugin_modifications/plugins/'.$name, 1);
        }
	$model=$this->{$name}=model($name);
        if( !$model ){
            throw new \Exception("Can't load model $name",404);
        }
	if( isset($model->min_level) ){
	    $this->set_level($model->min_level);
	}
	$model->Hub=$this;
	if( method_exists($model, 'init') ){
	    $model->init();
	}
	return $model;
    }
    public function acomp($name){/*@TODO move to lazy loading of pcomp/acomp in v4.0*/
	$acomp=$this->svar('acomp');
	return isset($acomp->$name)?$acomp->$name:NULL;
    }
    
    public function pcomp($name){/*@TODO move to lazy loading of pcomp/acomp in v4.0*/
	$pcomp=$this->svar('pcomp');
	return isset($pcomp->$name)?$pcomp->$name:NULL;
    }
    
    public function pref($name){
	if( !isset($this->pref) ){
	    $Pref=$this->load_model('Pref');
	    $this->pref=$Pref->getPrefs();
	}
	return isset($this->pref->$name)?$this->pref->$name:NULL;
    }
    
    public function svar($name, $value = NULL) {
	if (isset($value)) {
            $this->session->set($name,$value);
	}
	return $this->session->get($name);
    }


}
