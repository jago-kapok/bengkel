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
		
		$query = $this->db->exec("SELECT * FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id WHERE
			quotation_number LIKE ? OR
			customer_name LIKE ? OR
			customer_name_other LIKE ? ORDER BY quotation_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id WHERE
			quotation_number LIKE ? OR
			customer_name LIKE ? OR
			customer_name_other LIKE ?",
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
				$data['customer_name'],
				$data['quotation_date'],
				$data['quotation_received_date'],
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