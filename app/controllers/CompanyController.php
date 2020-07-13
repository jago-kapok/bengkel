<?php

class CompanyController extends Controller {

	public function index(){
		if($this->f3->exists('POST.update')){
			$company = new Company($this->db);
			$company->edit(1);
			
			$this->f3->reroute('/company');
		} else {
			$company = new Company($this->db);
			$company->getData();
			
			$this->f3->set('page_title','Pengaturan Perusahaan');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','company/index.html');
		}
	}
}