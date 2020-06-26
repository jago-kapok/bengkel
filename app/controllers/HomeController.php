<?php

class HomeController extends Controller {

	public function index(){		
		$this->f3->set('page_title','Monitoring Data dan Transaksi');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','home/index.html');
	}
}