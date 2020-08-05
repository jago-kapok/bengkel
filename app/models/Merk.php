<?php
date_default_timezone_set("Asia/Jakarta");

class Merk extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'merk');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM merk WHERE
			merk_code LIKE ? OR
			merk_desc LIKE ? ORDER BY merk_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM merk WHERE
			merk_code LIKE ? OR
			merk_desc LIKE ?",
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
				$data['merk_code'],
				$data['merk_desc'],
				$data['merk_id']
			);
		}

		echo json_encode($output);
	}
	
	public function getAll(){
		$this->load();
		return $this->query;
	}
	
	public function add(){
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($merk_id){
		$this->load(array('merk_id = ?', $merk_id));
		$this->copyTo('POST');
	}
	
	public function edit($merk_id){
		$this->load(array('merk_id = ?', $merk_id));
		$this->copyFrom('POST');
		$this->update();
	}
}