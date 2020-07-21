<?php

class InvoiceController extends Controller {

	public function index(){
		$quotation = new Quotation($this->db);
		$this->f3->set('data_quotation', $quotation->getAll());
		
		$this->f3->set('page_title','INVOICE');
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
	
	public function get_price(){
		$invoice_detail_item_code = $this->f3->get('PARAMS.invoice_detail_item_code');
		
		$invoice_detail = new InvoiceDetail($this->db);
		die($invoice_detail->getPriceHistory($invoice_detail_item_code));
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
			
			$this->f3->set('page_title','INVOICE BARU - PENAWARAN : '.$quotation->quotation_number);
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','invoice/create.html');
		}
	}
	
	public function create(){
		$invoice = new Invoice($this->db);
		$invoice->add();
		
		$invoice_detail = new InvoiceDetail($this->db);
		$invoice_detail->add($invoice->invoice_id);
		
		$quotation = new Quotation($this->db);
		$quotation->invoiced($invoice->quotation_id);
		
		$this->f3->reroute('/invoice/update/'.$invoice->invoice_id);
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$invoice = new Invoice($this->db);
			$invoice->edit($this->f3->get('PARAMS.invoice_id'));
			
			$invoice_detail = new InvoiceDetail($this->db);
			$invoice_detail->beforeEdit($this->f3->get('PARAMS.invoice_id'));
			$invoice_detail->add($this->f3->get('PARAMS.invoice_id'));
						
			$this->f3->reroute('/invoice/update/'.$this->f3->get('PARAMS.invoice_id'));
		} else {
			$invoice = new Invoice($this->db);
			$invoice->getById($this->f3->get('PARAMS.invoice_id'));
			
			$invoice_total = $invoice->invoice_part_charge + $invoice->invoice_service_charge - $invoice->invoice_discount;
			$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
			$this->f3->set('invoice_ppn', $invoice_ppn);
			
			$invoice_detail = new InvoiceDetail($this->db);
			$this->f3->set('data_invoice_detail', $invoice_detail->getById($this->f3->get('PARAMS.invoice_id')));
			
			$stock = new Stock($this->db);
			$stock->beforeEdit($this->f3->get('PARAMS.invoice_id'));
			
			$this->f3->set('page_title','INVOICE : '.$invoice->invoice_number);
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','invoice/update.html');
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
			array_push($profit_total, $data['invoice_detail_unit_price_temp'] * $data['invoice_detail_qty']);
		}
		$this->f3->set('profit_total', array_sum($profit_total));
		
		$this->f3->set('page_title','INVOICE : '.$invoice->invoice_number);
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice/view.html');
	}
	
	public function cancel(){
		$invoice = new Invoice($this->db);
		$invoice->cancel($this->f3->get('PARAMS.invoice_id'));
		
		$this->f3->reroute('/invoice/view/'.$this->f3->get('PARAMS.invoice_id'));
	}
	
	public function active(){
		$invoice = new Invoice($this->db);
		$invoice->active($this->f3->get('PARAMS.invoice_id'));
		
		$this->f3->reroute('/invoice/view/'.$this->f3->get('PARAMS.invoice_id'));
	}
}