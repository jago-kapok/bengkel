<?php

class MerkController extends Controller {

	public function index(){
		$current = 'M';
		$query = $this->db->exec("SELECT MAX(merk_code) AS last FROM merk WHERE merk_code LIKE '$current%'");
		foreach($query as $result){
			$last = $result['last'];
		}
		$lastNo = substr($last, 1, 4);
		$nextNo = $lastNo + 1;
		$merk_code = $current.sprintf('%04s', $nextNo);
		$this->f3->set('merk_code', $merk_code);
		/* Merk Code */
		
		$this->f3->set('page_title','Master Merk');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','merk/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$merk = new Merk($this->db);
		die($merk->data($draw, $length, $offset, $search));
	}
	
	public function save(){
		if(empty($this->f3->get('POST.merk_desc'))){
			echo json_encode(array("status"=>100));
		} else {
			if(empty($this->f3->get('POST.merk_id'))){
				$merk = new Merk($this->db);
				$merk->add();
			
				echo json_encode(array("status"=>200, "merk_id"=>$merk->merk_id));
			} else {
				$merk = new Merk($this->db);
				$merk->edit($this->f3->get('POST.merk_id'));
				
				echo json_encode(array("status"=>250));
			}
		}
		
		die();
	}
}