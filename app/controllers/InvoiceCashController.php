<?php

class InvoiceCashController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Laporan Cash');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice/index_cash.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$invoice = new InvoiceCash($this->db);
		die($invoice->data($draw, $length, $offset, $search));
	}
}