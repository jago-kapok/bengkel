<?php
date_default_timezone_set("Asia/Jakarta");

class User extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'user');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM user WHERE
			user_fullname LIKE ? OR
			user_name LIKE ? OR
			user_last_login LIKE ? ORDER BY user_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM user WHERE
			user_fullname LIKE ? OR
			user_name LIKE ? OR
			user_last_login LIKE ?",
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
				$data['user_fullname'],
				$data['user_name'],
				$data['user_image'],
				$data['user_last_login'],
				$data['user_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add($user_image){
		$this->user_image = $user_image;
		$this->copyFrom('POST');
		$this->save();
	}
	
	public function getById($user_id){
		$this->load(array('user_id = ?', $user_id));
		$this->copyTo('POST');
	}
	
	public function edit($user_id, $user_image){
		$this->load(array('user_id = ?', $user_id));
		
		$this->user_image = $user_image;
		$this->copyFrom('POST');
		$this->update();
	}
}