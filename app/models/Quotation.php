<?php
date_default_timezone_set("Asia/Jakarta");

class Quotation extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'quotation');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT quotation.*, invoice.invoice_number AS invoice_number FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id LEFT JOIN invoice ON quotation.quotation_id = invoice.quotation_id WHERE
			quotation_number LIKE ? OR
			customer_name LIKE ? OR
			invoice_number LIKE ? ORDER BY quotation.quotation_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id LEFT JOIN invoice ON quotation.quotation_id = invoice.quotation_id WHERE
			quotation_number LIKE ? OR
			customer_name LIKE ? OR
			invoice_number LIKE ?",
			array(
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
				date('d/m/Y', strtotime($data['quotation_date'])),
				$data['customer_name'],
				number_format($data['quotation_part_charge'] + $data['quotation_service_charge']),
				$data['invoice_number'],
				$data['quotation_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$f3 = \Base::instance();
		
		$this->copyFrom('POST');
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
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyTo('POST');
	}
	
	public function getViewById($quotation_id){
		$query = $this->db->exec("SELECT * FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id JOIN status ON quotation.quotation_status = status.status_id JOIN user ON quotation.created_by = user.user_id WHERE quotation.quotation_id = ?", $quotation_id);
		return $query;
	}
	
	public function edit($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		
		$this->copyFrom('POST');
		$this->update();
	}
}