<?php
date_default_timezone_set("Asia/Jakarta");

class Stock extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'stock');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM stock JOIN item ON stock.item_id = item.item_id WHERE
			item_code LIKE ? OR
			item_desc LIKE ? OR
			stock_min LIKE ? OR
			stock_on_hand LIKE ? ORDER BY stock_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM stock JOIN item ON stock.item_id = item.item_id WHERE
			item_code LIKE ? OR
			item_desc LIKE ? OR
			stock_min LIKE ? OR
			stock_on_hand LIKE ?",
			array(
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
				$data['item_code'],
				$data['item_desc'],
				$data['stock_min'],
				$data['stock_on_hand'],
				$data['stock_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($stock_id){
		$this->load(array('stock_id = ?', $stock_id));
		$this->copyTo('POST');
	}
	
	public function edit($stock_id){
		$this->load(array('stock_id = ?', $stock_id));
		$this->copyFrom('POST');
		$this->update();
	}
}