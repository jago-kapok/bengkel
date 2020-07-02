<?php

class CustomerController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Pelanggan');
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
		$customer_id = $this->f3->get('PARAMS.customer_id');
				
		$customer = new Customer($this->db);
		die($customer->getData($customer_id));
	}
	
	public function create(){
		$customer = new Customer($this->db);
		$customer->add();
			
		\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.customer_name').'"', 'success');
		$this->f3->reroute('/customer');
	}
	
	public function update(){
		$customer = new Customer($this->db);				
		$customer->edit($this->f3->get('POST.customer_id'));			
			
		\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.customer_name').'"', 'success');
		$this->f3->reroute('/customer');
	}
}