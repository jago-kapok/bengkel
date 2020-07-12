<?php
date_default_timezone_set("Asia/Jakarta");

class Report extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'invoice');
    }
	
	public function getDataPPN($month, $year){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->load(array('MONTH(invoice_date) LIKE ? AND YEAR(invoice_date) = ? AND invoice_ppn != 0', array('%'.$month.'%', $year)));
		return $this->query;
	}
	
	public function getDataCash($month, $year){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->load(array('MONTH(invoice_date) LIKE ? AND YEAR(invoice_date) = ? AND invoice_note_payment != ""', array('%'.$month.'%', $year)));
		return $this->query;
	}
}