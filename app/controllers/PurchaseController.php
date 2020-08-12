<?php

class PurchaseController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Pembelian');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','purchase/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$purchase = new Purchase($this->db);
		die($purchase->data($draw, $length, $offset, $search));
	}
	
	public function create(){
		if($this->f3->exists('POST.create')){
			$purchase = new Purchase($this->db);
			$purchase->add();
			
			$purchase_detail = new PurchaseDetail($this->db);
			$purchase_detail->add($purchase->purchase_id);
				
			$this->f3->reroute('/purchase');
		} else {
			$item = new Item($this->db);
			$this->f3->set('data_item', $item->getAll());
			
			$supplier = new Supplier($this->db);
			$this->f3->set('data_supplier', $supplier->getAll());
			
			$merk = new Merk($this->db);
			$this->f3->set('data_merk', $merk->getAll());
			
			$this->f3->set('page_title','Transaksi Pembelian');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','purchase/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$purchase = new Purchase($this->db);
			$purchase->edit($this->f3->get('PARAMS.purchase_id'));
			
			$purchase_detail = new PurchaseDetail($this->db);
			$purchase_detail->beforeEdit($this->f3->get('PARAMS.purchase_id'));
			$purchase_detail->add($this->f3->get('PARAMS.purchase_id'));
				
			$this->f3->reroute('/purchase/update/'.$this->f3->get('PARAMS.purchase_id'));
		} else {
			$purchase = new Purchase($this->db);
			$purchase->getById($this->f3->get('PARAMS.purchase_id'));
			
			$purchase_detail = new PurchaseDetail($this->db);
			$this->f3->set('data_purchase_detail', $purchase_detail->getById($this->f3->get('PARAMS.purchase_id')));
			$purchase_detail->resetStock($this->f3->get('PARAMS.purchase_id'));
			
			$item = new Item($this->db);
			$this->f3->set('data_item', $item->getAll());
			
			$supplier = new Supplier($this->db);
			$this->f3->set('data_supplier', $supplier->getAll());
			
			$merk = new Merk($this->db);
			$this->f3->set('data_merk', $merk->getAll());
			
			$this->f3->set('page_title','Transaksi Pembelian');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','purchase/update.html');
		}
	}
	
	public function view(){
		$purchase = new Purchase($this->db);
		$purchase->getById($this->f3->get('PARAMS.purchase_id'));
		
		$purchase_detail = new PurchaseDetail($this->db);
		$this->f3->set('data_purchase_detail', $purchase_detail->getById($this->f3->get('PARAMS.purchase_id')));
		
		$this->f3->set('page_title','Detil Pembelian - No : '.$purchase->purchase_number);
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','purchase/view.html');
	}
}