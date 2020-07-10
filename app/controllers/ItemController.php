<?php

class ItemController extends Controller {

	public function index(){
		$this->f3->set('page_title','Data Barang');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','item/index.html');
	}
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$item = new Item($this->db);
		die($item->data($draw, $length, $offset, $search));
	}
	
	public function create(){
		if($this->f3->exists('POST.update')){
			$item = new Item($this->db);
			$item->add(str_replace(" ", "_", $this->f3->get('POST.item_code')).'.'.pathinfo($this->f3->get('FILES.item_image["name"]'), PATHINFO_EXTENSION));
			
			self::upload($this->f3->get('FILES.item_image'), str_replace(" ", "_", $this->f3->get('POST.item_code')));
			
			\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.item_code').'"', 'success');
			$this->f3->reroute('/item');
		} else {
			$this->f3->set('page_title','Data Barang');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','item/index.html');
		}	
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$item = new Item($this->db);
			$item->add(str_replace(" ", "_", $this->f3->get('POST.item_code')).'.'.pathinfo($this->f3->get('FILES.item_image["name"]'), PATHINFO_EXTENSION));
			
			self::upload($this->f3->get('FILES.item_image'), str_replace(" ", "_", $this->f3->get('POST.item_code')));
			
			\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.item_code').'"', 'success');
			$this->f3->reroute('/item');
		} else {
			$this->f3->set('page_title','Data Barang');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','item/index.html');
		}	
	}
	
	public function upload($file, $file_name){
		$web = \Web::instance();
		$this->f3->set('UPLOADS','ui/assets/img/item/');

		$files = $web->receive(function($file, $formFieldName){
				return true;
			},
			true, // Overwrite file
			function($fileBaseName, $formFieldName) use ($file_name){
				$temp = explode(".", $fileBaseName);
				return $file_name . "." . end($temp);
			}
		);
	}
}