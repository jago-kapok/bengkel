<?php

class QuotationCancelController extends Controller {

	public function index(){
		$this->f3->set('page_title','PENAWARAN BATAL');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','quotation-cancel/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$quotation = new QuotationCancel($this->db);
		die($quotation->data($draw, $length, $offset, $search));
	}
}