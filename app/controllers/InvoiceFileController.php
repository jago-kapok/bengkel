<?php

class InvoiceFileController extends Controller {

	public function upload(){
		if($this->f3->exists('POST.submit1')){
			if($this->f3->get('FILES.quotation_file_receipt["name"]') != ''){
				$quotation_file_receipt = $quotation->quotation_number.'/quotation_file_receipt.'.pathinfo($this->f3->get('FILES.quotation_file_receipt["name"]'), PATHINFO_EXTENSION);
			} else {
				$quotation_file_receipt = $this->f3->get('POST.quotation_file_receipt_temp');
			}
			
			self::upload_file($this->f3->get('FILES.quotation_file_receipt'), 'quotation_file_receipt', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->receipt($this->f3->get('PARAMS.quotation_id'), $this->f3->get('PARAMS.quotation_number'), $quotation_file_receipt);
			
			$this->f3->reroute('/quotation/upload/'.$this->f3->get('PARAMS.quotation_id'));
		} else {
			$invoice = new Invoice($this->db);
			$invoice->getById($this->f3->get('PARAMS.invoice_id'));
			
			$quotation = new Quotation($this->db);
			$quotation->getById($invoice->quotation_id);
			$this->f3->set('quotation_number', $quotation->quotation_number);
			$this->f3->set('quotation_id', $invoice->quotation_id);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->getById($invoice->quotation_id);
			$quotation_file_exist = count($quotation_file->getDataExist($invoice->quotation_id));
			
			if($quotation_file_exist == 0){
				$quotation_file->add($invoice->quotation_id);
			}
			
			$this->f3->set('page_title','INVOICE : '.$quotation->quotation_number);
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','invoice/upload.html');
		}
	}
	
	public function upload_file($file, $file_name, $quotation_number){
		$web = \Web::instance();
		$this->f3->set('UPLOADS','ui/assets/img/quotation/'.$quotation_number.'/');

		$files = $web->receive(function($file, $formFieldName){
				return true;
			},
			true, // Overwrite file
			function($fileBaseName, $formFieldName) use ($file_name){
				$temp = explode(".", $fileBaseName);
				return $file_name . "." . end($temp);
			}
		);
	}
}