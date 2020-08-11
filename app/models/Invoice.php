<?php
date_default_timezone_set("Asia/Jakarta");

class Invoice extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'invoice');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM invoice JOIN customer ON invoice.customer_id = customer.customer_id JOIN quotation ON invoice.quotation_id = quotation.quotation_id WHERE
			(invoice_number LIKE ? OR
			customer_name LIKE ? OR
			quotation_number LIKE ? OR
			quotation_serial_number LIKE ? OR
			quotation_engine LIKE ?) AND
			invoice_status != 2 ORDER BY invoice.invoice_number DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM invoice JOIN customer ON invoice.customer_id = customer.customer_id JOIN quotation ON invoice.quotation_id = quotation.quotation_id WHERE
			(invoice_number LIKE ? OR
			customer_name LIKE ? OR
			quotation_number LIKE ? OR
			quotation_serial_number LIKE ? OR
			quotation_engine LIKE ?) AND
			invoice_status != 2",
			array(
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
				$data['invoice_number'],
				date('d-m-Y', strtotime($data['invoice_date'])),
				$data['customer_name'],
				$data['quotation_serial_number'],
				$data['quotation_engine'],
				number_format($data['invoice_part_charge'] + $data['invoice_service_charge']),
				$data['quotation_number'],
				$data['invoice_id']
			);
		}

		echo json_encode($output);
	}
	
	public function getAll(){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->quotation_number = "SELECT quotation_number FROM quotation WHERE quotation.quotation_id = invoice.quotation_id";
		
		$this->load();
		return $this->query;
	}
	
	public function getThree(){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		
		$this->load(NULL, array('limit'=>3, 'order'=>'invoice_number DESC'));
		return $this->query;
	}
	
	public function add(){
		$f3 = \Base::instance();
		
		$current = '/'.date("Y");
		$query = $this->db->exec("SELECT MAX(invoice_number) AS last FROM invoice WHERE invoice_number LIKE '%$current'");
		foreach($query as $result){
			$last = $result['last'];
		}
		$lastNo = substr($last, 0, 6);
		$nextNo = $lastNo + 1;
		$invoice_number = sprintf('%06s', $nextNo).$current;
		/* Invoice Number */
		
		$this->copyFrom('POST');
		$this->invoice_number = $invoice_number;
		$this->invoice_service_charge = str_replace(',', '', $f3->get('POST.invoice_service_charge'));
		$this->invoice_discount = str_replace(',', '', $f3->get('POST.invoice_discount'));
		$this->save();
	}
	
	public function getById($invoice_id){
		$this->customer_code = "SELECT customer_code FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_address = "SELECT customer_address FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_city = "SELECT customer_city FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_phone = "SELECT customer_phone FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_address_other = "SELECT customer_address_other FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_city_other = "SELECT customer_city_other FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_phone_other = "SELECT customer_phone_other FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->customer_note = "SELECT customer_note FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->quotation_number = "SELECT quotation_number FROM quotation WHERE quotation.quotation_id = invoice.quotation_id";
		$this->quotation_date = "SELECT quotation_date FROM quotation WHERE quotation.quotation_id = invoice.quotation_id";
		$this->quotation_model = "SELECT quotation_model FROM quotation WHERE quotation.quotation_id = invoice.quotation_id";
		$this->quotation_engine = "SELECT quotation_engine FROM quotation WHERE quotation.quotation_id = invoice.quotation_id";
		$this->quotation_serial_number = "SELECT quotation_serial_number FROM quotation WHERE quotation.quotation_id = invoice.quotation_id";
		
		$this->load(array('invoice_id = ?', $invoice_id));
		$this->copyTo('POST');
	}
	
	public function getByNumber($invoice_number){
		$this->load(array('invoice_number = ?', $invoice_number));
		return $this->query;
	}
	
	public function getByQuotation($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		return $this->query;
	}
	
	public function edit($invoice_id){
		$this->load(array('invoice_id = ?', $invoice_id));
		
		$this->copyFrom('POST');
		$this->update();
	}
	
	public function getDataMonth($month, $year){
		$this->customer_name = "SELECT customer_name FROM customer WHERE customer.customer_id = invoice.customer_id";
		$this->load(array('MONTH(invoice_date) LIKE ? AND YEAR(invoice_date) = ?', array('%'.$month.'%', $year)));
		return $this->query;
	}
	
	public function cancel($invoice_id){
		$this->db->exec("UPDATE invoice SET invoice_status = 2 WHERE invoice_id = ?", $invoice_id);
	}
	
	public function active($invoice_id){
		$this->db->exec("UPDATE invoice SET invoice_status = 1 WHERE invoice_id = ?", $invoice_id);
	}
}