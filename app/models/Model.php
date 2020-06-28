<?php
date_default_timezone_set("Asia/Jakarta");

class Model extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'model');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM model WHERE
			model_code LIKE ? OR
			model_desc LIKE ? ORDER BY model_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM model WHERE
			model_code LIKE ? OR
			model_desc LIKE ?",
			array(
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
				$data['model_code'],
				$data['model_desc'],
				$data['model_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($model_id){
		$this->load(array('model_id = ?', $model_id));
		$this->copyTo('POST');
	}
	
	public function edit($model_id){
		$this->load(array('model_id = ?', $model_id));
		$this->copyFrom('POST');
		$this->update();
	}
}