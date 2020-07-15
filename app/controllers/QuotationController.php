<?php

class QuotationController extends Controller {

	public function index(){
		$this->f3->set('page_title','PENAWARAN');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','quotation/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$quotation = new Quotation($this->db);
		die($quotation->data($draw, $length, $offset, $search));
	}
	
	public function create(){
		if($this->f3->exists('POST.create')){
			$quotation = new Quotation($this->db);
			$quotation->add();
			
			$quotation_detail = new QuotationDetail($this->db);
			$quotation_detail->add($quotation->quotation_id);
			
			$this->f3->reroute('/quotation/view/'.$quotation->quotation_id);
		} else {
			$current = '/'.date("Y");
			$query = $this->db->exec("SELECT MAX(quotation_number) AS last FROM quotation WHERE quotation_number LIKE '%$current'");
			foreach($query as $result){
				$last = $result['last'];
			}
			$lastNo = substr($last, 0, 6);
			$nextNo = $lastNo + 1;
			$quotation_no = sprintf('%06s', $nextNo).$current;
			$this->f3->set('quotation_number',$quotation_no);
			/* Quotation Number */
			
			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());
			
			$this->f3->set('page_title','PENAWARAN BARU');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','quotation/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$quotation = new Quotation($this->db);
			$quotation->edit($this->f3->get('PARAMS.quotation_id'));
			
			$quotation_detail = new QuotationDetail($this->db);
			$quotation_detail->beforeEdit($this->f3->get('PARAMS.quotation_id'));
			$quotation_detail->add($this->f3->get('PARAMS.quotation_id'));
			
			$this->f3->reroute('/quotation/view/'.$this->f3->get('PARAMS.quotation_id'));
		} else {
			$quotation = new Quotation($this->db);
			$quotation->getById($this->f3->get('PARAMS.quotation_id'));
			
			$quotation_total = $quotation->quotation_part_charge + $quotation->quotation_service_charge - $quotation->quotation_discount;
			$quotation_ppn = $quotation_total * ($quotation->quotation_ppn / 100);
			$this->f3->set('quotation_ppn', $quotation_ppn);
			
			$quotation_detail = new QuotationDetail($this->db);
			$this->f3->set('data_quotation_detail', $quotation_detail->getById($this->f3->get('PARAMS.quotation_id')));
			
			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());
			
			$this->f3->set('page_title','PENAWARAN : '.$quotation->quotation_number);
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','quotation/update.html');
		}
	}
	
	public function view(){
		$quotation = new Quotation($this->db);
		$quotation->getById($this->f3->get('PARAMS.quotation_id'));
		
		$quotation_total = ($quotation->quotation_part_charge + $quotation->quotation_service_charge) - $quotation->quotation_discount;
		$quotation_ppn = $quotation_total * ($quotation->quotation_ppn / 100);
		$this->f3->set('quotation_ppn', $quotation_ppn);
		
		$quotation_detail = new QuotationDetail($this->db);
		$this->f3->set('data_quotation_detail', $quotation_detail->getById($this->f3->get('PARAMS.quotation_id')));
		
		$this->f3->set('page_title','PENAWARAN : '.$quotation->quotation_number);
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','quotation/view.html');
	}
	
	public function cancel(){
		$quotation = new Quotation($this->db);
		$quotation->cancel($this->f3->get('PARAMS.quotation_id'));
		
		$this->f3->reroute('/quotation/view/'.$this->f3->get('PARAMS.quotation_id'));
	}
	
	public function active(){
		$quotation = new Quotation($this->db);
		$quotation->active($this->f3->get('PARAMS.quotation_id'));
		
		$this->f3->reroute('/quotation/view/'.$this->f3->get('PARAMS.quotation_id'));
	}
}