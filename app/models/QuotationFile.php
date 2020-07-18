<?php
date_default_timezone_set("Asia/Jakarta");

class QuotationFile extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'quotation_file');
    }
	
	public function add($quotation_id){
		$this->copyFrom('POST');
		$this->quotation_id = $quotation_id;
		$this->save();
	}
	
	public function getById($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyTo('POST');
	}

    public function receipt($quotation_id, $quotation_number, $quotation_file_receipt){
		$f3 = \Base::instance();
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyFrom('POST');
		$this->quotation_file_receipt = $quotation_file_receipt;
		$this->update();
	}
	
	public function delivery($quotation_id, $quotation_number, $quotation_file_delivery){
		$f3 = \Base::instance();
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyFrom('POST');
		$this->quotation_file_delivery = $quotation_file_delivery;
		$this->update();
	}
	
	public function fip($quotation_id, $quotation_number, $quotation_file_fip){
		$f3 = \Base::instance();
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyFrom('POST');
		$this->quotation_file_fip = $quotation_file_fip;
		$this->update();
	}
	
	public function delivery_cancel($quotation_id, $quotation_number, $quotation_file_delivery_cancel){
		$f3 = \Base::instance();
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyFrom('POST');
		$this->quotation_file_delivery_cancel = $quotation_file_delivery_cancel;
		$this->update();
	}
	
	public function fip_agreement($quotation_id, $quotation_number, $quotation_file_fip_agreement){
		$f3 = \Base::instance();
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyFrom('POST');
		$this->quotation_file_fip_agreement = $quotation_file_fip_agreement;
		$this->update();
	}
	
	public function po_wo($quotation_id, $quotation_number, $quotation_file_po_wo){
		$f3 = \Base::instance();
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyFrom('POST');
		$this->quotation_file_po_wo = $quotation_file_po_wo;
		$this->update();
	}
}