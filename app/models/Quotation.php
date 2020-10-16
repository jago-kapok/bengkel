<?php
date_default_timezone_set("Asia/Jakarta");

class Quotation extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'quotation');
    }

    public function data($draw, $length, $offset, $search, $date){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT quotation.*, invoice.invoice_number AS invoice_number, customer.customer_name FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id LEFT JOIN invoice ON quotation.quotation_id = invoice.quotation_id WHERE
			DATE_FORMAT(quotation_date, '%d-%m-%Y') LIKE ? AND
			(quotation_number LIKE ? OR
			customer_name LIKE ? OR
			invoice_number LIKE ? OR
			quotation_engine LIKE ? OR
			quotation_serial_number LIKE ?) AND
			quotation_status != 2 ORDER BY quotation.quotation_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$date,
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id LEFT JOIN invoice ON quotation.quotation_id = invoice.quotation_id WHERE
			DATE_FORMAT(quotation_date, '%d-%m-%Y') LIKE ? AND
			(quotation_number LIKE ? OR
			customer_name LIKE ? OR
			invoice_number LIKE ? OR
			quotation_engine LIKE ? OR
			quotation_serial_number LIKE ?) AND
			quotation_status != 2",
			array(
				'%'.$date,
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%'
			)
		);
			
		foreach($total as $count){
			$total_filter = $count['TotalFilter'];
		}

		$output['recordsTotal'] = $output['recordsFiltered'] = $total_filter;
		
		foreach($query as $data) {
			$output['data'][] = array(
				$data['quotation_number'],
				date('d-m-Y', strtotime($data['quotation_date'])),
				$data['customer_name'],
				$data['quotation_serial_number'],
				$data['quotation_engine'],
				number_format($data['quotation_part_charge'] + $data['quotation_service_charge']),
				$data['invoice_number'],
				$data['quotation_id']
			);
		}

		echo json_encode($output);
	}
	
	public function getAll(){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->invoice_number = "SELECT invoice_number FROM invoice WHERE invoice.quotation_id = quotation.quotation_id";
		
		$this->load(array('quotation_status = 1'));
		return $this->query;
	}
	
	public function add(){
		$f3 = \Base::instance();
		
		$current = '/'.date("Y");
		$query = $this->db->exec("SELECT MAX(quotation_number) AS last FROM quotation WHERE quotation_number LIKE '%$current'");
		foreach($query as $result){
			$last = $result['last'];
		}
		$lastNo = substr($last, 0, 6);
		$nextNo = $lastNo + 1;
		$quotation_number = sprintf('%06s', $nextNo).$current;
		/* Quotation Number */
		
		$this->copyFrom('POST');
		$this->quotation_number = $quotation_number;
		$this->quotation_service_charge = str_replace(',', '', $f3->get('POST.quotation_service_charge'));
		$this->quotation_discount = str_replace(',', '', $f3->get('POST.quotation_discount'));
		$this->save();
	}
	
	public function getById($quotation_id){
		$this->customer_code = "SELECT customer_code FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->customer_address = "SELECT customer_address FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->customer_city = "SELECT customer_city FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->customer_phone = "SELECT customer_phone FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->customer_note = "SELECT customer_note FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->invoice_number = "SELECT invoice_number FROM invoice WHERE invoice.quotation_id = quotation.quotation_id";
		$this->invoice_date = "SELECT invoice_date FROM invoice WHERE invoice.quotation_id = quotation.quotation_id";
		
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyTo('POST');
	}
	
	public function getByNumber($quotation_number){
		$this->load(array('quotation_number = ?', $quotation_number));
		return $this->query;
	}
	
	public function edit($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		
		$this->copyFrom('POST');
		$this->update();
	}
	
	public function getDataMonth($month, $year){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = quotation.customer_id";
		$this->total_part = "SELECT SUM(quotation_part_charge) FROM quotation WHERE MONTH(quotation_date) LIKE '%$month%' AND YEAR(quotation_date) = $year";
		$this->total_service = "SELECT SUM(quotation_service_charge) FROM quotation WHERE MONTH(quotation_date) LIKE '%$month%' AND YEAR(quotation_date) = $year";
		$this->total_ppn = "SELECT SUM((quotation_part_charge + quotation_service_charge - quotation_discount) * (quotation_ppn / 100)) FROM quotation WHERE MONTH(quotation_date) LIKE '%$month%' AND YEAR(quotation_date) = $year";
		
		$this->load(array('MONTH(quotation_date) LIKE ? AND YEAR(quotation_date) = ?', array('%'.$month.'%', $year)));
		return $this->query;
	}
	
	public function invoiced($quotation_id){
		$this->db->exec("UPDATE quotation SET quotation_status = 3 WHERE quotation_id = ?", $quotation_id);
	}
	
	public function cancel($quotation_id){
		$this->db->exec("UPDATE quotation SET quotation_status = 2 WHERE quotation_id = ?", $quotation_id);
	}
	
	public function active($quotation_id){
		$this->db->exec("UPDATE quotation SET quotation_status = 1 WHERE quotation_id = ?", $quotation_id);
	}
}