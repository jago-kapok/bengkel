<?php

class QuotationController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Penawaran');
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
			
			$customer = new Customer($this->db);
			$this->f3->set('data_customer', $customer->getAll());
			
			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());
			
			$item = new Item($this->db);
			$this->f3->set('data_item', $item->getAll());
			
			$this->f3->set('page_title','Penawaran Baru');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','quotation/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$user = new User($this->db);
			
			if($this->f3->get('FILES.user_image["name"]') != ''){
				$user_image = str_replace(" ", "_", $this->f3->get('POST.user_fullname')).'.'.pathinfo($this->f3->get('FILES.user_image["name"]'), PATHINFO_EXTENSION);
			} else {
				$user_image = $this->f3->get('POST.user_image_temp');
			}
			
			$this->f3->reroute('/user');
		} else {
			$quotation = new Quotation($this->db);
			$quotation->getById($this->f3->get('PARAMS.quotation_id'));
			
			$quotation_total = $quotation->quotation_part_charge + $quotation->quotation_service_charge - $quotation->quotation_discount;
			$quotation_ppn = $quotation_total * ($quotation->quotation_ppn / 100);
			$this->f3->set('quotation_ppn', $quotation_ppn);
			
			$quotation_detail = new QuotationDetail($this->db);
			$this->f3->set('data_quotation_detail', $quotation_detail->getById($this->f3->get('PARAMS.quotation_id')));
			
			$customer = new Customer($this->db);
			$this->f3->set('data_customer', $customer->getAll());
			
			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());
			
			$item = new Item($this->db);
			$this->f3->set('data_item', $item->getAll());
			
			$this->f3->set('page_title','Update Penawaran - No : '.$quotation->quotation_number);
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
		
		$profit_total = array();
		foreach($quotation_detail->getById($this->f3->get('PARAMS.quotation_id')) as $data){
			array_push($profit_total, $data['quotation_detail_profit']);
		}
		$this->f3->set('profit_total', array_sum($profit_total));
		
		$this->f3->set('page_title','Detil Penawaran - No : '.$quotation->quotation_number);
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','quotation/view.html');
	}
}