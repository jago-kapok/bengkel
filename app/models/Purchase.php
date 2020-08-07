<?php
date_default_timezone_set("Asia/Jakarta");

class Purchase extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'purchase');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM purchase JOIN supplier ON purchase.supplier_id = supplier.supplier_id WHERE
			purchase_number LIKE ? OR
			supplier_name LIKE ? OR
			purchase_date LIKE ? ORDER BY purchase_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM purchase JOIN supplier ON purchase.supplier_id = supplier.supplier_id WHERE
			purchase_number LIKE ? OR
			supplier_name LIKE ? OR
			purchase_date LIKE ?",
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
				$data['purchase_number'],
				date('d F Y', strtotime($data['purchase_date'])),
				$data['supplier_name'],
				$data['purchase_amount'],
				$data['purchase_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($purchase_id){
		$this->supplier_code = "SELECT supplier_code FROM supplier WHERE supplier.supplier_id = purchase.supplier_id";
		$this->supplier_name = "SELECT supplier_name FROM supplier WHERE supplier.supplier_id = purchase.supplier_id";
		$this->supplier_address = "SELECT supplier_address FROM supplier WHERE supplier.supplier_id = purchase.supplier_id";
		$this->supplier_city = "SELECT supplier_city FROM supplier WHERE supplier.supplier_id = purchase.supplier_id";
		$this->supplier_phone = "SELECT supplier_phone FROM supplier WHERE supplier.supplier_id = purchase.supplier_id";
		
		$this->load(array('purchase_id = ?', $purchase_id));
		$this->copyTo('POST');
	}
	
	public function edit($stock_id){
		$this->load(array('stock_id = ?', $stock_id));
		$this->copyFrom('POST');
		$this->update();
	}
}