<?php
date_default_timezone_set("Asia/Jakarta");

class Level extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'level');
    }
	
	public function all(){
		$this->load();
		return $this->query;
	}
}