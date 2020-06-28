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
			item_price LIKE ? OR
			item_unit LIKE ? OR
			item_stamping LIKE ? OR
			item_physical LIKE ? OR
			item_similar LIKE ? OR
			item_pn LIKE ? OR
			item_brand_1 LIKE ? OR
			item_bradn_2 LIKE ? OR
			item_brand_3 LIKE ? OR
			item_note LIKE ? ORDER BY item_id DESC LIMIT ? OFFSET ?",
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
			item_price LIKE ? OR
			item_unit LIKE ? OR
			item_stamping LIKE ? OR
			item_physical LIKE ? OR
			item_similar LIKE ? OR
			item_pn LIKE ? OR
			item_brand_1 LIKE ? OR
			item_bradn_2 LIKE ? OR
			item_brand_3 LIKE ? OR
			item_note LIKE ?",
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
				$data['item_price'],
				$data['item_unit'],
				$data['item_stamping'],
				$data['item_physical'],
				$data['item_similar'],
				$data['item_pn'],
				$data['item_brand_1'],
				$data['item_bradn_2'],
				$data['item_brand_3'],
				$data['item_note'],
				$data['item_image'],
				$data['item_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add($item_image){
		$this->item_image = $item_image;
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($item_id){
		$this->load(array('item_id = ?', $item_id));
		$this->copyTo('POST');
	}
	
	public function edit($item_id, $item_image){
		$this->load(array('item_id = ?', $item_id));
		$this->item_image = $item_image;
		$this->copyFrom('POST');
		$this->update();
	}
}