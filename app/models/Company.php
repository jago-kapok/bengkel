<?php
date_default_timezone_set("Asia/Jakarta");

class Company extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'company');
    }

    public function getAll(){
		$this->load(array('company_id = 1'));
		return $this->query;
	}
	
	public function getData(){
		$this->load(array('company_id = 1'));
		$this->copyTo('POST');
	}
	
	public function edit($company_id){
		$this->load(array('company_id = ?', $company_id));
		$this->copyFrom('POST');
		$this->update();
	}
}