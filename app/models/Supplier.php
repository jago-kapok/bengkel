<?php
date_default_timezone_set("Asia/Jakarta");

class Supplier extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'supplier');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM supplier WHERE
			supplier_code LIKE ? OR
			supplier_name LIKE ? OR
			supplier_address LIKE ? OR
			supplier_city LIKE ? OR
			supplier_phone LIKE ? ORDER BY supplier_id DESC LIMIT ? OFFSET ?",
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
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM supplier WHERE
			supplier_code LIKE ? OR
			supplier_name LIKE ? OR
			supplier_address LIKE ? OR
			supplier_city LIKE ? OR
			supplier_phone LIKE ?",
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
				$data['supplier_code'],
				$data['supplier_name'],
				$data['supplier_address'],
				$data['supplier_city'],
				$data['supplier_phone'],
				$data['supplier_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($supplier_id){
		$this->load(array('supplier_id = ?', $supplier_id));
		$this->copyTo('POST');
	}
	
	public function edit($supplier_id){
		$this->load(array('supplier_id = ?', $supplier_id));
		$this->copyFrom('POST');
		$this->update();
	}
}