<?php
date_default_timezone_set("Asia/Jakarta");

class Customer extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'customer');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM customer WHERE
			customer_code LIKE ? OR
			customer_name LIKE ? OR
			customer_address LIKE ? OR
			customer_city LIKE ? OR
			customer_phone LIKE ? OR
			customer_name_other LIKE ? OR
			customer_address_other LIKE ? OR
			customer_city_other LIKE ? OR
			customer_phone_other LIKE ? OR
			customer_note LIKE ? ORDER BY customer_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM customer WHERE
            customer_code LIKE ? OR
			customer_name LIKE ? OR
			customer_address LIKE ? OR
			customer_city LIKE ? OR
			customer_phone LIKE ? OR
			customer_name_other LIKE ? OR
			customer_address_other LIKE ? OR
			customer_city_other LIKE ? OR
			customer_phone_other LIKE ? OR
			customer_note LIKE ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
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
				$data['customer_code'],
				$data['customer_name'],
				$data['customer_address'],
				$data['customer_city'],
				$data['customer_phone'],
				$data['customer_name_other'],
				$data['customer_address_other'],
				$data['customer_city_other'],
				$data['customer_phone_other'],
				$data['customer_note'],
				$data['customer_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($customer_id){
		$this->load(array('customer_id = ?', $customer_id));
		$this->copyTo('POST');
	}
	
	public function edit($customer_id){
		$this->load(array('customer_id = ?', $customer_id));
		$this->copyFrom('POST');
		$this->update();
	}
}