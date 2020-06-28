<?php

class ModelController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Model');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','model/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$model = new Model($this->db);
		die($model->data($draw, $length, $offset, $search));
	}
	
	public function create(){
		if($this->f3->exists('POST.create')){
			$model = new Model($this->db);
			$model->add(str_replace(" ", "_", $this->f3->get('POST.model_code')));
			
			\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.model_code').'"', 'success');
			$this->f3->reroute('/model');
		} else {
			$level = new Level($this->db);
			$this->f3->set('data_level', $level->all());
			
			$this->f3->set('page_title','Tambah Data Model');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','model/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$model = new Model($this->db);
						
			$model->edit($this->f3->get('PARAMS.model_id'));			
			
			\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.model_code').'"', 'success');
			$this->f3->reroute('/model');
		} else {
			$model = new Model($this->db);
			$model->getById($this->f3->get('PARAMS.model_id'));
			
			$this->f3->set('page_title','Perbarui Data Model');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','model/update.html');
		}
	}
}
