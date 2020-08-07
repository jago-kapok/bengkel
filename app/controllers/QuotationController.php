<?php

class QuotationController extends Controller {

	public function index(){
		$quotation = new Quotation($this->db);
		$this->f3->set('data_quotation', $quotation->getAll());
		
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
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->add($quotation->quotation_id);
			
			$this->f3->reroute('/quotation/update/'.$quotation->quotation_id);
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
			
			$this->f3->set('page_title','PENAWARAN BARU : '.$quotation_no);
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
			
			$this->f3->reroute('/quotation/update/'.$this->f3->get('PARAMS.quotation_id'));
		} else {
			$quotation = new Quotation($this->db);
			$quotation->getById($this->f3->get('PARAMS.quotation_id'));
			
			$quotation_total = $quotation->quotation_part_charge + $quotation->quotation_service_charge - $quotation->quotation_discount;
			$quotation_ppn = $quotation_total * ($quotation->quotation_ppn / 100);
			$this->f3->set('quotation_ppn', $quotation_ppn);
			$this->f3->set('quotation_terbilang', self::terbilang($quotation_total + $quotation_ppn));
			
			$quotation_detail = new QuotationDetail($this->db);
			$this->f3->set('data_quotation_detail', $quotation_detail->getById($this->f3->get('PARAMS.quotation_id')));
			
			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file_exist = count($quotation_file->getDataExist($this->f3->get('PARAMS.quotation_id')));
			
			if($quotation_file_exist == 0){
				$quotation_file->add($this->f3->get('PARAMS.quotation_id'));
			}
			
			$invoice = new Invoice($this->db);
			$invoice->getByQuotation($this->f3->get('PARAMS.quotation_id'));
			if(count($invoice->getByQuotation($this->f3->get('PARAMS.quotation_id'))) > 0){
				$this->f3->set('invoice_tax_number', $invoice->invoice_tax_number);
				$invoice_number = ' -- INVOICE : '.$invoice->invoice_number.' ('.date('d-m-Y', strtotime($invoice->invoice_date)).')';
			}
			
			$this->f3->set('page_title','PENAWARAN : '.$quotation->quotation_number.''.$invoice_number);
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
		$this->f3->set('quotation_terbilang', self::terbilang($quotation_total + $quotation_ppn));
		
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
	
	public function terbilang($nominal){
		$a = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if($nominal == 0){
            return "";
        } elseif ($nominal < 12 & $nominal != 0) {
            return "" . $a[$nominal];
        } elseif ($nominal < 20) {
            return self::terbilang($nominal - 10) . " Belas ";
        } elseif ($nominal < 100) {
            return self::terbilang($nominal / 10) . " Puluh " . self::terbilang($nominal % 10);
        } elseif ($nominal < 200) {
            return " Seratus " . self::terbilang($nominal - 100);
        } elseif ($nominal < 1000) {
            return self::terbilang($nominal / 100) . " Ratus " . self::terbilang($nominal % 100);
        } elseif ($nominal < 2000) {
            return " Seribu " . self::terbilang($nominal - 1000);
        } elseif ($nominal < 1000000) {
            return self::terbilang($nominal / 1000) . " Ribu " . self::terbilang($nominal % 1000);
        } elseif ($nominal < 1000000000) {
            return self::terbilang($nominal / 1000000) . " Juta " . self::terbilang($nominal % 1000000);
        } elseif ($nominal < 1000000000000) {
            return self::terbilang($nominal / 1000000000) . " Milyar " . self::terbilang($nominal % 1000000000);
        } elseif ($nominal < 100000000000000) {
            return self::terbilang($nominal / 1000000000000) . " Trilyun " . self::terbilang($nominal % 1000000000000);
        } elseif ($nominal <= 100000000000000) {
            return "Maaf Tidak Dapat di Prose Karena Jumlah nominal Terlalu Besar ";
        }
	}
}