<?php

class InvoiceController extends Controller {

	public function index(){
		$quotation = new Quotation($this->db);
		$this->f3->set('data_quotation', $quotation->getQuotationBeforeInvoice());

		$invoice = new Invoice($this->db);
		$this->f3->set('data_invoice', $invoice->getAll());

		$this->f3->set('page_title','INVOICE');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','invoice/index.html');
	}

	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		$date = $_REQUEST['columns'][1]['search']['value'] ? $_REQUEST['columns'][1]['search']['value'] : '%';

		$invoice = new Invoice($this->db);
		die($invoice->data($draw, $length, $offset, $search, $date));
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
			$this->f3->set('invoice_terbilang', self::terbilang($quotation_total + $invoice_ppn));

			$quotation_detail = new QuotationDetail($this->db);
			$this->f3->set('data_quotation_detail', $quotation_detail->getById($quotation->quotation_id));

			$model = new Model($this->db);
			$this->f3->set('data_model', $model->getAll());

			$this->f3->set('page_title','INVOICE BARU : '.$invoice_no.' -- PENAWARAN : '.$quotation->quotation_number.' ('.date('d-m-Y', strtotime($quotation->quotation_date)).')');
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
			$stock = new Stock($this->db);
			$stock->beforeEdit($this->f3->get('PARAMS.invoice_id'));
			
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
			$this->f3->set('invoice_terbilang', self::terbilang($invoice_total + $invoice_ppn));

			$invoice_detail = new InvoiceDetail($this->db);
			$this->f3->set('data_invoice_detail', $invoice_detail->getById($this->f3->get('PARAMS.invoice_id')));

			$quotation = new Quotation($this->db);
			$quotation->getById($invoice->quotation_id);

			$this->f3->set('page_title','INVOICE : '.$invoice->invoice_number.' -- PENAWARAN : '.$quotation->quotation_number.' ('.date('d-m-Y', strtotime($quotation->quotation_date)).')');
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
		$this->f3->set('invoice_terbilang', self::terbilang($invoice_total + $invoice_ppn));

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
