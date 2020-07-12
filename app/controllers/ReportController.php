<?php

class ReportController extends Controller {

	public function invoice(){
		$invoice_month = $this->f3->get('POST.invoice_month') != 'ALL' ? $this->f3->get('POST.invoice_month') : '%';
		
		$invoice = new Invoice($this->db);
		$this->f3->set('data_invoice', $invoice->getDataMonth($invoice_month, $this->f3->get('POST.invoice_year')));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		$this->f3->set('invoice_ppn', $invoice_ppn);
		
		$this->f3->set('invoice_month', $this->f3->get('POST.invoice_month'));
		$this->f3->set('invoice_year', $this->f3->get('POST.invoice_year'));
		
		$this->f3->set('page_title','Rekapitulasi Invoice Bulan : '.$this->f3->get('POST.invoice_month').'/'.$this->f3->get('POST.invoice_year'));
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','report/invoice.html');
	}
	
	public function ppn(){
		$invoice_month = $this->f3->get('POST.invoice_month') != 'ALL' ? $this->f3->get('POST.invoice_month') : '%';
		
		$invoice = new Report($this->db);
		$this->f3->set('data_invoice', $invoice->getDataPPN($invoice_month, $this->f3->get('POST.invoice_year')));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		$this->f3->set('invoice_ppn', $invoice_ppn);
		
		$this->f3->set('invoice_month', $this->f3->get('POST.invoice_month'));
		$this->f3->set('invoice_year', $this->f3->get('POST.invoice_year'));
		
		$this->f3->set('page_title','Rekapitulasi PPN Bulan : '.$this->f3->get('POST.invoice_month').'/'.$this->f3->get('POST.invoice_year'));
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','report/ppn.html');
	}
	
	public function cash(){
		$invoice_month = $this->f3->get('POST.invoice_month') != 'ALL' ? $this->f3->get('POST.invoice_month') : '%';
		
		$invoice = new Report($this->db);
		$this->f3->set('data_invoice', $invoice->getDataCash($invoice_month, $this->f3->get('POST.invoice_year')));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		$this->f3->set('invoice_ppn', $invoice_ppn);
		
		$this->f3->set('invoice_month', $this->f3->get('POST.invoice_month'));
		$this->f3->set('invoice_year', $this->f3->get('POST.invoice_year'));
		
		$this->f3->set('page_title','Rekapitulasi Cash Bulan : '.$this->f3->get('POST.invoice_month').'/'.$this->f3->get('POST.invoice_year'));
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','report/cash.html');
	}
}