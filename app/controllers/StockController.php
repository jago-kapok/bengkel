<?php

class StockController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Stok');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','stock/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$stock = new Stock($this->db);
		die($stock->data($draw, $length, $offset, $search));
	}
	
	public function create(){
		$stock = new Stock($this->db);
		$stock->add();
			
		\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.item_id').'"', 'success');
		$this->f3->reroute('/stock');
	}
	
	public function update(){
		$stock = new Stock($this->db);
		$stock->edit($this->f3->get('POST.stock_id'));			
			
		\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.item_id').'"', 'success');
		$this->f3->reroute('/stock');
	}
}