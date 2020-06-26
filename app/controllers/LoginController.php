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
		
		$admin = new Admin($this->db);
		$admin->getByUsername($username);
		
		if($admin->dry()){
			$this->f3->reroute('/login');
		}

		if(md5($password) == $admin->password){
			$id = $admin->id_admin;

			$admin = new Admin($this->db);
			$admin->getById($id);

			$this->f3->set('SESSION.id', $admin->id_admin);
			$this->f3->set('SESSION.fullname', $admin->fullname);
			$this->f3->set('SESSION.username', $admin->username);
			
			$this->f3->reroute('/');
		} else {
			$this->f3->reroute('/');
		}
	}
	
	public function logout(){
		$this->f3->clear('SESSION');
		$this->f3->reroute('/login');
	}
	
	public function cancel(){
		$payment = new Payment($this->db);
		
		foreach($payment->getByStatus(1) as $data){
			if(date('Y-m-d H', strtotime($data['tgl_order'].' +1 day')) <= date('Y-m-d H')){
				$payment->cancel($data['id_order']);
			}
		}
	}
}