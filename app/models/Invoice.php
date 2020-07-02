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
			invoice_number LIKE ? OR
			customer_name LIKE ? OR
			quotation_number LIKE ? ORDER BY invoice.invoice_number DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM invoice JOIN customer ON invoice.customer_id = customer.customer_id JOIN quotation ON invoice.quotation_id = quotation.quotation_id WHERE
			invoice_number LIKE ? OR
			customer_name LIKE ? OR
			quotation_number LIKE ?",
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
				$data['invoice_number'],
				date('d/m/Y', strtotime($data['invoice_date'])),
				$data['customer_name'],
				number_format($data['invoice_part_charge'] + $data['invoice_service_charge']),
				$data['quotation_number'],
				date('d/m/Y', strtotime($data['quotation_date'])),
				$data['quotation_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		$this->copyTo('POST');
	}
	
	public function edit($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		
		$this->copyFrom('POST');
		$this->update();
	}
}