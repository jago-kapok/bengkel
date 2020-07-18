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
		} else if($this->f3->exists('POST.submit2')){
			if($this->f3->get('FILES.quotation_file_delivery["name"]') != ''){
				$quotation_file_delivery = $quotation->quotation_number.'/quotation_file_delivery.'.pathinfo($this->f3->get('FILES.quotation_file_delivery["name"]'), PATHINFO_EXTENSION);
			} else {
				$quotation_file_delivery = $this->f3->get('POST.quotation_file_delivery_temp');
			}
			
			self::upload_file($this->f3->get('FILES.quotation_file_delivery'), 'quotation_file_delivery', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->delivery($this->f3->get('PARAMS.quotation_id'), $this->f3->get('PARAMS.quotation_number'), $quotation_file_delivery);
			
			$this->f3->reroute('/quotation/upload/'.$this->f3->get('PARAMS.quotation_id'));
		} else if($this->f3->exists('POST.submit3')){
			if($this->f3->get('FILES.quotation_file_fip["name"]') != ''){
				$quotation_file_fip = $quotation->quotation_number.'/quotation_file_fip.'.pathinfo($this->f3->get('FILES.quotation_file_fip["name"]'), PATHINFO_EXTENSION);
			} else {
				$quotation_file_fip = $this->f3->get('POST.quotation_file_fip_temp');
			}
			
			self::upload_file($this->f3->get('FILES.quotation_file_fip'), 'quotation_file_fip', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->fip($this->f3->get('PARAMS.quotation_id'), $this->f3->get('PARAMS.quotation_number'), $quotation_file_fip);
			
			$this->f3->reroute('/quotation/upload/'.$this->f3->get('PARAMS.quotation_id'));
		} else if($this->f3->exists('POST.submit4')){
			if($this->f3->get('FILES.quotation_file_delivery_cancel["name"]') != ''){
				$quotation_file_delivery_cancel = $quotation->quotation_number.'/quotation_file_delivery_cancel.'.pathinfo($this->f3->get('FILES.quotation_file_delivery_cancel["name"]'), PATHINFO_EXTENSION);
			} else {
				$quotation_file_delivery_cancel = $this->f3->get('POST.quotation_file_delivery_cancel_temp');
			}
			
			self::upload_file($this->f3->get('FILES.quotation_file_delivery_cancel'), 'quotation_file_delivery_cancel', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->delivery_cancel($this->f3->get('PARAMS.quotation_id'), $this->f3->get('PARAMS.quotation_number'), $quotation_file_delivery_cancel);
			
			$this->f3->reroute('/quotation/upload/'.$this->f3->get('PARAMS.quotation_id'));
		} else if($this->f3->exists('POST.submit5')){
			if($this->f3->get('FILES.quotation_file_fip_agreement["name"]') != ''){
				$quotation_file_fip_agreement = $quotation->quotation_number.'/quotation_file_fip_agreement.'.pathinfo($this->f3->get('FILES.quotation_file_fip_agreement["name"]'), PATHINFO_EXTENSION);
			} else {
				$quotation_file_fip_agreement = $this->f3->get('POST.quotation_file_fip_agreement_temp');
			}
			
			self::upload_file($this->f3->get('FILES.quotation_file_fip_agreement'), 'quotation_file_fip_agreement', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->fip_agreement($this->f3->get('PARAMS.quotation_id'), $this->f3->get('PARAMS.quotation_number'), $quotation_file_fip_agreement);
			
			$this->f3->reroute('/quotation/upload/'.$this->f3->get('PARAMS.quotation_id'));
		} else if($this->f3->exists('POST.submit6')){
			if($this->f3->get('FILES.quotation_file_po_wo["name"]') != ''){
				$quotation_file_po_wo = $quotation->quotation_number.'/quotation_file_po_wo.'.pathinfo($this->f3->get('FILES.quotation_file_po_wo["name"]'), PATHINFO_EXTENSION);
			} else {
				$quotation_file_po_wo = $this->f3->get('POST.quotation_file_po_wo_temp');
			}
			
			self::upload_file($this->f3->get('FILES.quotation_file_po_wo'), 'quotation_file_po_wo', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->po_wo($this->f3->get('PARAMS.quotation_id'), $this->f3->get('PARAMS.quotation_number'), $quotation_file_po_wo);
			
			$this->f3->reroute('/quotation/upload/'.$this->f3->get('PARAMS.quotation_id'));
		} else {
			$invoice = new Invoice($this->db);
			$invoice->getById($this->f3->get('PARAMS.invoice_id'));
			
			$quotation = new Quotation($this->db);
			$quotation->getById($invoice->quotation_id);
			$this->f3->set('quotation_number', $quotation->quotation_number);
			
			$quotation_file = new QuotationFile($this->db);
			$quotation_file->getById($invoice->quotation_id);
			
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