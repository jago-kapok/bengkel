<?php
date_default_timezone_set("Asia/Jakarta");

class Report extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'invoice');
    }
	
	public function getDataPPN($month, $year){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->total_part = "SELECT SUM(invoice_part_charge) FROM invoice WHERE MONTH(invoice_date) LIKE '%$month%' AND YEAR(invoice_date) = $year";
		$this->total_service = "SELECT SUM(invoice_service_charge) FROM invoice WHERE MONTH(invoice_date) LIKE '%$month%' AND YEAR(invoice_date) = $year";
		$this->total_ppn = "SELECT SUM((invoice_part_charge + invoice_service_charge - invoice_discount) * (invoice_ppn / 100)) FROM invoice WHERE MONTH(invoice_date) LIKE '%$month%' AND YEAR(invoice_date) = $year";
		
		$this->load(array('MONTH(invoice_date) LIKE ? AND YEAR(invoice_date) = ? AND invoice_ppn != 0', array('%'.$month.'%', $year)));
		return $this->query;
	}
	
	public function getDataCash($month, $year){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->total = "SELECT SUM(invoice_total) FROM invoice WHERE MONTH(invoice_date) LIKE '%$month%' AND YEAR(invoice_date) = $year";
		
		$this->load(array('MONTH(invoice_date) LIKE ? AND YEAR(invoice_date) = ?', array('%'.$month.'%', $year)));
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