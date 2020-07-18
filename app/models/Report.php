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
	
	public function getQuotation(){
		$total = $this->db->exec("SELECT COUNT(*) AS count FROM quotation WHERE quotation_status = 1");
		
		foreach($total as $count){
			$output = $count['count'];
		}
		return $output;
	}
	
	public function getInvoice(){
		$total = $this->db->exec("SELECT COUNT(*) AS count FROM invoice WHERE invoice_status = 1");
		
		foreach($total as $count){
			$output = $count['count'];
		}
		return $output;
	}
	
	public function getRework(){
		$total = $this->db->exec("SELECT COUNT(*) AS count FROM rework");
		
		foreach($total as $count){
			$output = $count['count'];
		}
		return $output;
	}
	
	public function getItem(){
		$total = $this->db->exec("SELECT COUNT(*) AS count FROM item");
		
		foreach($total as $count){
			$output = $count['count'];
		}
		return $output;
	}
	
	public function getReport($year, $month){
		$total = $this->db->exec("SELECT COUNT(*) AS count FROM invoice WHERE YEAR(invoice_date) = ? AND MONTH(invoice_date) = ?", array($year, $month));
		
		foreach($total as $count){
			$output = $count['count'];
		}
		return $output;
	}
}