<?php
namespace App\Controllers;

/**
 * Description of User
 *
 * @author Baycik
 */

class User extends BaseController{
    
    public function index(){
        //return $this->return();
    }
    
    private function return(){
        $uri=$this->session->get('_from_uri');
        if( !$uri ){
            $uri=getenv('app.baseURL');
        }
        redirect()->to($uri)->send();die;
    }
    
    public function login(){
        $user_login=$this->request->getVar('user_login');
        $user_pass= $this->request->getVar('user_pass');
        if( $user_login && $user_pass && preg_match("'^[a-zA-Z_0-9]*$'", $user_login) && preg_match("'^[a-zA-Z_0-9]*$'", $user_pass) ){
            $User=model('App\Models\User');
            $User->Hub=$this;
            $userData=$User->SignIn($user_login,$user_pass,'get_user_data');
            if( $userData ){
                return $this->loginSucceded($userData);
            }
        }
        return $this->loginAutorize();
    }
    
    private function loginSucceded($userData){
        $this->session->set($userData);
        $this->return();
        return $userData;
    }
    
    private function loginAutorize(){
        $this->response->setStatusCode(401);
        return view('dialog/login.html');
    }
    
    public function logout(){
        $this->session->destroy();
        redirect()->to("/")->send();
    }
}
