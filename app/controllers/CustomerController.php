<?php

class CustomerController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Customer');
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
	
	public function create(){
		if($this->f3->exists('POST.create')){
			$customer = new Customer($this->db);
			$customer->add(str_replace(" ", "_", $this->f3->get('POST.customer_code')));
			
			\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.customer_code').'"', 'success');
			$this->f3->reroute('/customer');
		} else {			
			$this->f3->set('page_title','Tambah Data Customer');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','customer/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$customer = new Customer($this->db);
						
			$customer->edit($this->f3->get('PARAMS.customer_id'));			
			
			\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.customer_code').'"', 'success');
			$this->f3->reroute('/customer');
		} else {
			$customer = new Customer($this->db);
			$customer->getById($this->f3->get('PARAMS.customer_id'));
			
			$this->f3->set('page_title','Perbarui Data Customer');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','customer/update.html');
		}
	}
}
