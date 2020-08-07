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
		
		$this->f3->set('page_title','Data Supplier');
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
	
	public function create(){
		$supplier = new Supplier($this->db);
		$supplier->add();
			
		\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.supplier_code').'"', 'success');
		$this->f3->reroute('/supplier');
	}
	
	public function update(){
		$supplier = new Supplier($this->db);
		$supplier->edit($this->f3->get('POST.supplier_id'));			
			
		\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.supplier_code').'"', 'success');
		$this->f3->reroute('/supplier');
	}
}