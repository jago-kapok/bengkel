<?php

class InvoiceController extends Controller {

	public function index(){
		$quotation = new Quotation($this->db);
		$this->f3->set('data_quotation', $quotation->getAll());
		
		$this->f3->set('page_title','Data Invoice');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$invoice = new Invoice($this->db);
		die($invoice->data($draw, $length, $offset, $search));
	}
	
	public function from_quotation(){
		if($this->f3->exists('POST.create')){
			$current = '/'.date("Y");
			$query = $this->db->exec("SELECT MAX(invoice_number) AS last FROM invoice WHERE invoice_number LIKE '%$current'");
			foreach($query as $result){
				$last = $result['last'];
			}
			$lastNo = substr($last, 0, 6);
			$nextNo = $lastNo + 1;
			$invoice_no = sprintf('%06s', $nextNo).$current;
			$this->f3->set('invoice_number',$invoice_no);
			/* Invoice Number */
			
			$quotation = new Quotation($this->db);
			$quotation->getByNumber($this->f3->get('POST.quotation_number'));
			$quotation->getById($quotation->quotation_id);
			
			$quotation_total = $quotation->quotation_part_charge + $quotation->quotation_service_charge - $quotation->quotation_discount;
			$quotation_ppn = $quotation_total * ($quotation->quotation_ppn / 100);
			$this->f3->set('quotation_ppn', $quotation_ppn);
			
			$quotation_detail = new QuotationDetail($this->db);
			$this->f3->set('data_quotation_detail', $quotation_detail->getById($quotation->quotation_id));
			
			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());
			
			$item = new Item($this->db);
			$this->f3->set('data_item', $item->getAll());
			
			$this->f3->set('page_title','Invoice Baru - No. Penawaran : '.$quotation->quotation_number);
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','invoice/create.html');
		}
	}
	
	public function create(){
		$invoice = new Invoice($this->db);
		$invoice->add();
		
		$invoice_detail = new InvoiceDetail($this->db);
		$invoice_detail->add($invoice->invoice_id);
		
		$this->f3->reroute('/invoice/view/'.$invoice->invoice_id);
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$user = new User($this->db);
			
			if($this->f3->get('FILES.user_image["name"]') != ''){
				$user_image = str_replace(" ", "_", $this->f3->get('POST.user_fullname')).'.'.pathinfo($this->f3->get('FILES.user_image["name"]'), PATHINFO_EXTENSION);
			} else {
				$user_image = $this->f3->get('POST.user_image_temp');
			}
			
			$user->edit($this->f3->get('PARAMS.user_id'), $user_image);
			
			self::upload($this->f3->get('FILES.user_image'), str_replace(" ", "_", $this->f3->get('POST.user_fullname')));
			
			\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.user_fullname').'"', 'success');
			$this->f3->reroute('/user');
		} else {
			$user = new User($this->db);
			$user->getById($this->f3->get('PARAMS.user_id'));
			
			$level = new Level($this->db);
			$this->f3->set('data_level', $level->all());
			
			$this->f3->set('page_title','Perbarui Data User');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','user/update.html');
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
		
		$this->f3->set('page_title','Detil Invoice - No : '.$invoice->invoice_number);
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice/view.html');
	}
}