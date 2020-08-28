<?php

class CustomerController extends Controller {

	public function index(){
		$current = 'P-';
		$query = $this->db->exec("SELECT MAX(customer_code) AS last FROM customer WHERE customer_code LIKE '$current%'");
		foreach($query as $result){
			$last = $result['last'];
		}
		$lastNo = substr($last, 2, 6);
		$nextNo = $lastNo + 1;
		$customer_code = $current.sprintf('%06s', $nextNo);
		$this->f3->set('customer_code', $customer_code);
		/* Customer Code */
		
		$this->f3->set('page_title','Master Pelanggan');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','customer/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$customer = new Customer($this->db);
		die($customer->data($draw, $length, $offset, $search));
	}
	
	public function get_data(){
		$customer_code = $this->f3->get('PARAMS.customer_code');
				
		$customer = new Customer($this->db);
		die($customer->getData($customer_code));
	}
	
	public function save(){
		if(empty($this->f3->get('POST.customer_name'))){
			echo json_encode(array("status"=>100));
		} else if(empty($this->f3->get('POST.customer_city'))){
			echo json_encode(array("status"=>150));
		} else {
			if(empty($this->f3->get('POST.customer_id'))){
				$customer = new Customer($this->db);
				$customer->add();
			
				echo json_encode(array("status"=>200, "customer_id"=>$customer->customer_id));
			} else {
				$customer = new Customer($this->db);
				$customer->edit($this->f3->get('POST.customer_id'));
				
				echo json_encode(array("status"=>250));
			}
		}
		
		die();
	}
}
