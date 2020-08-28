<?php

class ItemController extends Controller {

	public function index(){
		$item = new Item($this->db);
		$this->f3->set('data_item', $item->getAll());
		
		$this->f3->set('page_title','Master Barang');
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
	
	public function get_data(){
		$item_code = $this->f3->get('PARAMS.item_code');
				
		$item = new Item($this->db);
		die($item->getData($item_code));
	}
	
	public function exist(){
		$item = new Item($this->db);
		die($item->exist($this->f3->get('PARAMS.item_code')));
	}
	
	public function create(){
		if($this->f3->exists('POST.create')){
			$item = new Item($this->db);
			$item->add(str_replace(" ", "_", $this->f3->get('POST.item_code')).'.'.pathinfo($this->f3->get('FILES.item_image["name"]'), PATHINFO_EXTENSION));
			
			self::upload($this->f3->get('FILES.item_image'), str_replace(" ", "_", $this->f3->get('POST.item_code')));
			
			\Flash::instance()->addMessage('Berhasil menambah data "'.$this->f3->get('POST.otem_code').'"', 'success');
			$this->f3->reroute('/item/update/'.$item->item_id);
		} else {
			$level = new Level($this->db);
			$this->f3->set('data_level', $level->all());
			
			$this->f3->set('page_title','Tambah Master Barang');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','item/create.html');
		}
	}
	
	public function update(){
		if($this->f3->exists('POST.update')){
			$item = new Item($this->db);
			
			if($this->f3->get('FILES.item_image["name"]') != ''){
				$item_image = str_replace(" ", "_", $this->f3->get('POST.item_code')).'.'.pathinfo($this->f3->get('FILES.item_image["name"]'), PATHINFO_EXTENSION);
			} else {
				$item_image = $this->f3->get('POST.item_image_temp');
			}
			
			$item->edit($this->f3->get('PARAMS.item_id'), $item_image);
			
			self::upload($this->f3->get('FILES.item_image'), str_replace(" ", "_", $this->f3->get('POST.item_code')));
			
			\Flash::instance()->addMessage('Berhasil memperbarui data "'.$this->f3->get('POST.item_code').'"', 'success');
			$this->f3->reroute('/item/update/'.$this->f3->get('PARAMS.item_id'));
		} else {
			$item = new Item($this->db);
			$item->getById($this->f3->get('PARAMS.item_id'));
			
			$level = new Level($this->db);
			$this->f3->set('data_level', $level->all());
			
			$this->f3->set('page_title','Edit Master Barang');
			$this->f3->set('header','header/header.html');
			$this->f3->set('view','item/update.html');
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