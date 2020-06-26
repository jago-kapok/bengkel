<?php

class UserController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Pengguna');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','user/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$user = new User($this->db);
		die($user->data($draw, $length, $offset, $search));
	}
	
	public function create(){
		if($this->f3->exists('POST.create')){
			$user = new User($this->db);
			$user->add(str_replace(" ", "_", $this->f3->get('POST.user_fullname')).'.'.pathinfo($this->f3->get('FILES.user_image["name"]'), PATHINFO_EXTENSION));
			
			self::upload($this->f3->get('FILES.user_image'), str_replace(" ", "_", $this->f3->get('POST.user_fullname')));
			
			\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.user_fullname').'"', 'success');
			$this->f3->reroute('/user');
		} else {
			$level = new Level($this->db);
			$this->f3->set('data_level', $level->all());
			
			$this->f3->set('page_title','Tambah Data Pengguna');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','user/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$user = new User($this->db);
			
			if($this->f3->get('FILES.user_image["name"]') != ''){
				$user_image = str_replace(" ", "_", $this->f3->get('POST.user_fullname')).'.'.pathinfo($this->f3->get('FILES.user_image["name"]'), PATHINFO_EXTENSION);
			} else {
				$user->getById($this->f3->get('PARAMS.user_id'));
				$user_image = $user->user_image;
			}
			
			$user->edit($this->f3->get('PARAMS.user_id'), $user_image);
			
			self::upload($this->f3->get('FILES.user_image'), str_replace(" ", "_", $this->f3->get('POST.user_fullname')));
			
			\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.user_fullname').'"', 'success');
			$this->f3->reroute('/user');
		} else {
			$user = new User($this->db);
			$user->getById($this->f3->get('PARAMS.user_id'));
			
			$level = new Level($this->db);
			$this->f3->set('data_level', $level->all());
			
			$this->f3->set('page_title','Perbarui Data User');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','user/update.html');
		}
	}
	
	public function upload($file, $file_name){
		$web = \Web::instance();
		$this->f3->set('UPLOADS','ui/assets/img/user/');

		$files = $web->receive(function($file, $formFieldName){
				return true;
			},
			true, // Overwrite file
			function($fileBaseName, $formFieldName) use ($file_name){
				$temp = explode(".", $fileBaseName);
				return $file_name . "." . end($temp);
			}
		);
	}
}