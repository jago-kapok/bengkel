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
			DATE_FORMAT(purchase_date, '%d-%m-%Y') LIKE ? OR
			DATE_FORMAT(purchase_date, '%d-%M-%Y') LIKE ? OR
			purchase_ppn LIKE ? OR
			purchase_total LIKE ? ORDER BY purchase_id DESC LIMIT ? OFFSET ?",
			array(
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
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM purchase JOIN supplier ON purchase.supplier_id = supplier.supplier_id WHERE
			purchase_number LIKE ? OR
			supplier_name LIKE ? OR
			DATE_FORMAT(purchase_date, '%d-%m-%Y') LIKE ? OR
			DATE_FORMAT(purchase_date, '%d-%M-%Y') LIKE ? OR
			purchase_ppn LIKE ? OR
			purchase_total LIKE ?",
			array(
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
				$data['purchase_number'],
				date('d-m-Y', strtotime($data['purchase_date'])),
				$data['supplier_name'],
				number_format($data['purchase_ppn']),
				number_format($data['purchase_total']),
				$data['purchase_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$f3 = \Base::instance();
		
		$this->copyFrom('POST');
		$this->purchase_ppn = str_replace(',', '', $f3->get('POST.purchase_ppn'));
		$this->purchase_discount = str_replace(',', '', $f3->get('POST.purchase_discount'));
		$this->purchase_total = str_replace(',', '', $f3->get('POST.purchase_total'));
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
	
	public function edit($purchase_id){
		$f3 = \Base::instance();
		
		$this->load(array('purchase_id = ?', $purchase_id));
		$this->copyFrom('POST');
		$this->purchase_ppn = str_replace(',', '', $f3->get('POST.purchase_ppn'));
		$this->purchase_discount = str_replace(',', '', $f3->get('POST.purchase_discount'));
		$this->purchase_total = str_replace(',', '', $f3->get('POST.purchase_total'));
		$this->update();
	}
	
	public function exist($purchase_number){
		$output = array();
		
		$this->load(array('purchase_number = ?', $purchase_number));
		$query = $this->query;
			
		foreach($query as $data){
			$output[] = array(
				"count" => count($data['purchase_number']),
				"purchase_number" => $data['purchase_number']
			);
		}
		
		echo json_encode($output);
	}
}