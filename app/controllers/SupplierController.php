<?php

class SupplierController extends Controller {

	public function index(){
		$current = 'S';
		$query = $this->db->exec("SELECT MAX(supplier_code) AS last FROM supplier WHERE supplier_code LIKE '$current%'");
		foreach($query as $result){
			$last = $result['last'];
		}
		$lastNo = substr($last, 1, 4);
		$nextNo = $lastNo + 1;
		$supplier_code = $current.sprintf('%04s', $nextNo);
		$this->f3->set('supplier_code', $supplier_code);
		/* Supplier Code */
		
		$this->f3->set('page_title','Master Supplier');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','supplier/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$supplier = new Supplier($this->db);
		die($supplier->data($draw, $length, $offset, $search));
	}
	
	public function get_data(){
		$supplier_code = $this->f3->get('PARAMS.supplier_code');
				
		$supplier = new Supplier($this->db);
		die($supplier->getData($supplier_code));
	}
	
	public function save(){
		if(empty($this->f3->get('POST.supplier_name'))){
			echo json_encode(array("status"=>100));
		} else if(empty($this->f3->get('POST.supplier_city'))){
			echo json_encode(array("status"=>150));
		} else {
			if(empty($this->f3->get('POST.supplier_id'))){
				$supplier = new Supplier($this->db);
				$supplier->add();
			
				echo json_encode(array("status"=>200, "supplier_id"=>$supplier->supplier_id));
			} else {
				$supplier = new Supplier($this->db);
				$supplier->edit($this->f3->get('POST.supplier_id'));
				
				echo json_encode(array("status"=>250));
			}
		}
		
		die();
	}
}