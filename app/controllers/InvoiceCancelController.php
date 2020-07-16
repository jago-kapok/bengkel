<?php

class InvoiceCancelController extends Controller {

	public function index(){
		$quotation = new Quotation($this->db);
		$this->f3->set('data_quotation', $quotation->getAll());
		
		$this->f3->set('page_title','INVOICE BATAL');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice-cancel/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$invoice = new InvoiceCancel($this->db);
		die($invoice->data($draw, $length, $offset, $search));
	}
	
}