<?php

class ModelController extends Controller {

	public function index(){
		$this->f3->set('page_title','Master Model');
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
	
	public function save(){
		if(empty($this->f3->get('POST.model_code'))){
			echo json_encode(array("status"=>100));
		} else if(empty($this->f3->get('POST.model_desc'))){
			echo json_encode(array("status"=>150));
		} else {
			if(empty($this->f3->get('POST.model_id'))){
				$model = new Model($this->db);
				
				if(count($model->exist($this->f3->get('POST.model_code'))) == 0){
					$model->add();
					
					echo json_encode(array("status"=>200, "model_id"=>$model->model_id));
				} else {
					echo json_encode(array("status"=>250));
				}
			} else {
				$model = new Model($this->db);
				$model->edit($this->f3->get('POST.model_id'));
				
				echo json_encode(array("status"=>300));
			}
		}
		
		die();
	}
}