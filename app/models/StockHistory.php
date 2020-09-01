<?php
date_default_timezone_set("Asia/Jakarta");

class StockHistory extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'stock_history');
    }
	
	public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM stock_history JOIN item ON stock_history.item_id = item.item_id LEFT JOIN purchase ON stock_history.purchase_id = purchase.purchase_id LEFT JOIN invoice ON stock_history.invoice_id = invoice.invoice_id LEFT JOIN rework ON stock_history.rework_id = rework.rework_id WHERE
			DATE_FORMAT(stock_history_date, '%d-%m-%Y') LIKE ? OR
			DATE_FORMAT(stock_history_date, '%d-%M-%Y') LIKE ? OR
			item.item_code LIKE ? OR
			item.item_desc LIKE ? OR
			purchase.purchase_number LIKE ? OR
			invoice.invoice_number LIKE ? OR
			stock_history.rework_invoice LIKE ? ORDER BY stock_history_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM stock_history JOIN item ON stock_history.item_id = item.item_id LEFT JOIN purchase ON stock_history.purchase_id = purchase.purchase_id LEFT JOIN invoice ON stock_history.invoice_id = invoice.invoice_id LEFT JOIN rework ON stock_history.rework_id = rework.rework_id WHERE
			DATE_FORMAT(stock_history_date, '%d-%m-%Y') LIKE ? OR
			DATE_FORMAT(stock_history_date, '%d-%M-%Y') LIKE ? OR
			item.item_code LIKE ? OR
			item.item_desc LIKE ? OR
			purchase.purchase_number LIKE ? OR
			invoice.invoice_number LIKE ? OR
			stock_history.rework_invoice LIKE ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
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
				date('d-m-Y', strtotime($data['stock_history_date'])),
				$data['item_code'].' | '.$data['item_part_no'],
				$data['item_desc'].' | '.$data['item_unit'],
				$data['purchase_number'].' | '.number_format($data['purchase_total']),
				$data['invoice_number'].' | '.number_format($data['invoice_total']),
				$data['rework_invoice'],
				$data['stock_history_value'],
			);
		}

		echo json_encode($output);
	}

    public function getAll(){
		$this->item_code = "SELECT item_code FROM item WHERE item.item_id = stock_history.item_id";
		$this->item_part_no = "SELECT item_part_no FROM item WHERE item.item_id = stock_history.item_id";
		$this->item_desc = "SELECT item_desc FROM item WHERE item.item_id = stock_history.item_id";
		$this->purchase_number = "SELECT purchase_number FROM purchase WHERE purchase.purchase_id = stock_history.purchase_id";
		$this->invoice_number = "SELECT invoice_number FROM invoice WHERE invoice.invoice_id = stock_history.invoice_id";
		$this->rework_number = "SELECT rework_invoice FROM rework WHERE rework.rework_id = stock_history.rework_id";
		
		$this->load(NULL, array('order'=>'stock_history_id DESC'));
		return $this->query;
	}
	
	public function getThree(){
		$this->item_code = "SELECT item_code FROM item WHERE item.item_id = stock_history.item_id";
		$this->item_part_no = "SELECT item_part_no FROM item WHERE item.item_id = stock_history.item_id";
		$this->item_desc = "SELECT item_desc FROM item WHERE item.item_id = stock_history.item_id";
		$this->purchase_number = "SELECT purchase_number FROM purchase WHERE purchase.purchase_id = stock_history.purchase_id";
		$this->invoice_number = "SELECT invoice_number FROM invoice WHERE invoice.invoice_id = stock_history.invoice_id";
		$this->rework_number = "SELECT rework_invoice FROM rework WHERE rework.rework_id = stock_history.rework_id";
		
		$this->load(NULL, array('limit'=>3, 'order'=>'stock_history_id DESC'));
		return $this->query;
	}
}