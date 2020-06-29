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
		$model = new Model($this->db);
		$model->add();
			
		\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.model_desc').'"', 'success');
		$this->f3->reroute('/model');
	}
	
	public function update(){
		$model = new Model($this->db);
		$model->edit($this->f3->get('POST.model_id'));			
			
		\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.model_desc').'"', 'success');
		$this->f3->reroute('/model');
	}
}