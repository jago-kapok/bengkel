<?php

class ReworkController extends Controller {

	public function index(){
		$invoice = new Invoice($this->db);
		$this->f3->set('data_invoice', $invoice->getAll());
		
		$this->f3->set('page_title','REWORK');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','rework/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$rework = new Rework($this->db);
		die($rework->data($draw, $length, $offset, $search));
	}
	
	public function from_invoice(){
		if($this->f3->exists('POST.create')){
			$invoice = new Invoice($this->db);
			$invoice->getByNumber($this->f3->get('POST.invoice_number'));
			$invoice->getById($invoice->invoice_id);
			
			$this->f3->set('page_title','REWORK -- INVOICE : '.$invoice->invoice_number.' ('.date('d/m/y', strtotime($invoice->invoice_date)).')');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','rework/create.html');
		}
	}
	
	public function create(){
		$rework = new Rework($this->db);
		$rework->add();
		
		$rework_detail = new ReworkDetail($this->db);
		$rework_detail->add($rework->rework_id, $this->f3->get('POST.invoice_number'));
		
		$this->f3->reroute('/rework');
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$rework = new Rework($this->db);
			$rework->edit($this->f3->get('PARAMS.rework_id'));
			
			$rework_detail = new ReworkDetail($this->db);
			$rework_detail->beforeEdit($this->f3->get('PARAMS.rework_id'));
			$rework_detail->add($this->f3->get('PARAMS.rework_id'), $this->f3->get('POST.invoice_number'));
						
			$this->f3->reroute('/rework');
		} else {
			$rework = new Rework($this->db);
			$rework->getById($this->f3->get('PARAMS.rework_id'));
		
			$rework_detail = new ReworkDetail($this->db);
			$this->f3->set('data_rework_detail', $rework_detail->getById($this->f3->get('PARAMS.rework_id')));
			
			$stock = new Stock($this->db);
			$stock->beforeEdit($this->f3->get('PARAMS.rework_id'));
			
			$invoice = new Invoice($this->db);
			$invoice->getById($rework->invoice_id);
			
			$this->f3->set('page_title','REWORK - INVOICE : '.$invoice->invoice_number);
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','rework/update.html');
		}
	}
	
	public function view(){
		$invoice = new Invoice($this->db);
		$invoice->getById($this->f3->get('PARAMS.invoice_id'));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		$this->f3->set('invoice_ppn', $invoice_ppn);
		
		$invoice_detail = new InvoiceDetail($this->db);
		$this->f3->set('data_invoice_detail', $invoice_detail->getById($this->f3->get('PARAMS.invoice_id')));
		
		$profit_total = array();
		foreach($invoice_detail->getById($this->f3->get('PARAMS.invoice_id')) as $data){
			array_push($profit_total, $data['invoice_detail_profit']);
		}
		$this->f3->set('profit_total', array_sum($profit_total));
		
		$this->f3->set('page_title','INVOICE : '.$invoice->invoice_number);
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice/view.html');
	}
}