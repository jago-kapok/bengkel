<?php
date_default_timezone_set("Asia/Jakarta");

class Item extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'item');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM item WHERE
			item_code LIKE ? OR
			item_part_no LIKE ? OR
			item_desc LIKE ? OR
			item_unit LIKE ? ORDER BY item_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM item WHERE
			item_code LIKE ? OR
			item_part_no LIKE ? OR
			item_desc LIKE ? OR
			item_unit LIKE ?",
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
				$data['item_part_no'],
				$data['item_desc'],
				number_format($data['item_price']),
				$data['item_unit'],
				$data['item_stamping'],
				$data['item_physical'],
				$data['item_similar'],
				$data['item_pn'],
				$data['item_brand_1'],
				$data['item_brand_2'],
				$data['item_brand_3'],
				$data['item_note'],
				$data['item_image'],
				$data['item_id']
			);
		}

		echo json_encode($output);
	}
	
	public function getAll(){
		$this->load();
		return $this->query;
	}
	
	public function add($item_image){
		$f3 = \Base::instance();
		
		$this->item_image = $item_image;
		$this->copyFrom('POST');
		$this->item_price = str_replace(',', '', $f3->get('POST.item_price'));
		$this->save();
	}
	
	public function getById($item_id){
		$this->load(array('item_id = ?', $item_id));
		$this->copyTo('POST');
	}
	
	public function edit($item_id, $item_image){
		$f3 = \Base::instance();
		$this->load(array('item_id = ?', $item_id));
		
		$this->item_image = $item_image;
		$this->copyFrom('POST');
		$this->item_price = str_replace(',', '', $f3->get('POST.item_price'));
		$this->update();
	}
	
	public function getData($item_code){
		$output = array();
		
		$this->load(array('item_code = ?', $item_code));
		$query = $this->query;
			
		foreach($query as $data){
			$output[] = array(
				"item_id" => $data['item_id'],
				"item_part_no" => $data['item_part_no'],
				"item_desc" => $data['item_desc'],
				"item_price" => $data['item_price'],
				"item_brand_1" => $data['item_brand_1']
			);
		}
		
		echo json_encode($output);
    }
}