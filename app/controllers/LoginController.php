<?php

class LoginController extends Controller {
	
	public function beforeroute(){}
	
	public function index(){
		$this->f3->set('header','header/login.html');
        $this->f3->set('view','home/login.html');
	}

	public function auth()
	{
		$username = $this->f3->get('POST.username');
		$password = $this->f3->get('POST.password');
		
		$user = new User($this->db);
		$user->getByUsername($username);
		
		if($user->dry()){
			$this->f3->reroute('/login');
		}

		if(md5($password) == $user->user_password){
			$user_id = $user->user_id;

			$user = new User($this->db);
			$user->getById($user_id);
			$user->lastLogin($user_id);
			
			$this->f3->set('SESSION.id', $user->user_id);
			$this->f3->set('SESSION.fullname', $user->user_fullname);
			$this->f3->set('SESSION.username', $user->user_name);
			$this->f3->set('SESSION.level', $user->user_level);
			
			$this->f3->reroute('/');
		} else {
			$this->f3->reroute('/login');
		}
	}
	
	public function logout(){
		$this->f3->clear('SESSION');
		$this->f3->reroute('/login');
	}
}