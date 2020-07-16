<?php
date_default_timezone_set("Asia/Jakarta");

class StockHistory extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'stock_history');
    }

    public function getAll(){
		$this->item_code = "SELECT item_code FROM item WHERE item.item_id = stock_history.item_id";
		$this->item_part_no = "SELECT item_part_no FROM item WHERE item.item_id = stock_history.item_id";
		$this->item_desc = "SELECT item_desc FROM item WHERE item.item_id = stock_history.item_id";
		$this->purchase_number = "SELECT purchase_number FROM purchase WHERE purchase.purchase_id = stock_history.purchase_id";
		$this->invoice_number = "SELECT invoice_number FROM invoice WHERE invoice.invoice_id = stock_history.invoice_id";
		$this->rework_number = "SELECT rework_invoice FROM rework WHERE rework.rework_id = stock_history.rework_id";
		
		$this->load();
		return $this->query;
	}
}