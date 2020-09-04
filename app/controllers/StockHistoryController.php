<?php

class StockHistoryController extends Controller {
	
	public function data(){
		$draw = intval($this->f3->get('REQUEST.draw'));
		$length = intval($this->f3->get('REQUEST.length'));
		$offset = intval($this->f3->get('REQUEST.start'));
		$search = $_REQUEST['search']['value'] ? $_REQUEST['search']['value'] : '%';
		$date = $_REQUEST['columns'][0]['search']['value'] ? $_REQUEST['columns'][0]['search']['value'] : '%';
		$sorting = $_REQUEST['columns'][6]['search']['value'] ? $_REQUEST['columns'][6]['search']['value'] : 'DESC';
		
		$stock_history = new StockHistory($this->db);
		die($stock_history->data($draw, $length, $offset, $search, $date, $sorting));
	}
}