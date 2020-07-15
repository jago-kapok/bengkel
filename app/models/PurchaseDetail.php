<?php
date_default_timezone_set("Asia/Jakarta");

class PurchaseDetail extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'purchase_detail');
    }

    public function add($purchase_id){
		$f3 = \Base::instance();
		
		$data = $f3->get('POST.data');
		
		foreach($data as $value){
			if($value['item_desc'] <> ''){
				$this->db->exec("INSERT INTO purchase_detail
				(purchase_id,
				item_id,
				purchase_detail_value) VALUES
				(:purchase_id,
				:item_id,
				:purchase_detail_value)",
				array(
					':purchase_id' => $purchase_id,
					':item_id' => $value['item_id'],
					':purchase_detail_value' => $value['item_qty']
				));
				
				$this->db->exec("UPDATE stock SET stock_on_hand = (stock_on_hand + ?) WHERE item_id = ?",
				array(
					$value['item_qty'],
					$value['item_id']
				));
				
				$this->db->exec("INSERT INTO stock_history
					(item_id,
					purchase_id,
					stock_history_value,
					stock_history_date) VALUES
					:item_id,
					:purchase_id,
					:stock_history_value,
					:stock_history_date",
					array(
						':item_id' => $value['item_id'],
						':purchase_id' => $purchase_id,
						':stock_history_value' => $value['item_qty'],
						':stock_history_date' => date('Y-m-d H:i:s')
					)
				);
			}
		}
	}
	
	public function getById($purchase_id){
		$this->item_code = "SELECT item_code FROM item WHERE item.item_id = purchase_detail.item_id";
		$this->item_part_no = "SELECT item_part_no FROM item WHERE item.item_id = purchase_detail.item_id";
		$this->item_desc = "SELECT item_desc FROM item WHERE item.item_id = purchase_detail.item_id";
		$this->item_brand_1 = "SELECT item_brand_1 FROM item WHERE item.item_id = purchase_detail.item_id";
		
		$this->load(array('purchase_id = ?', $purchase_id));
		return $this->query;
	}
}