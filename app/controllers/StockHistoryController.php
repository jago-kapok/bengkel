<?php

class StockHistoryController extends Controller {
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		
		$stock_history = new StockHistory($this->db);
		die($stock_history->data($draw, $length, $offset, $search));
	}
}