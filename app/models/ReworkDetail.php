<?php
date_default_timezone_set("Asia/Jakarta");

class ReworkDetail extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'rework_detail');
    }

    public function add($rework_id, $invoice_number){
		$f3 = \Base::instance();
		
		$data = $f3->get('POST.data');
		
		foreach($data as $value){
			if($value['rework_detail_item_desc'] <> ''){
				$this->db->exec("INSERT INTO rework_detail
				(rework_id,
				rework_detail_item_code,
				rework_detail_item_part_no,
				rework_detail_item_desc,
				rework_detail_unit,
				rework_detail_qty,
				rework_detail_unit_price,
				rework_detail_unit_price_temp,
				rework_detail_brand,
				rework_detail_amount) VALUES
				(:rework_id,
				:rework_detail_item_code,
				:rework_detail_item_part_no,
				:rework_detail_item_desc,
				:rework_detail_unit,
				:rework_detail_qty,
				:rework_detail_unit_price,
				:rework_detail_unit_price_temp,
				:rework_detail_brand,
				:rework_detail_amount)",
				array(
					':rework_id' => $rework_id,
					':rework_detail_item_code' => $value['rework_detail_item_code'],
					':rework_detail_item_part_no' => $value['rework_detail_item_part_no'],
					':rework_detail_item_desc' => $value['rework_detail_item_desc'],
					':rework_detail_unit' => $value['rework_detail_unit'],
					':rework_detail_qty' => str_replace(',', '', $value['rework_detail_qty']),
					':rework_detail_unit_price' => str_replace(',', '', $value['rework_detail_unit_price']),
					':rework_detail_unit_price_temp' => str_replace(',', '', $value['rework_detail_unit_price_temp']),
					':rework_detail_brand' => $value['rework_detail_brand'],
					':rework_detail_amount' => str_replace(',', '', $value['rework_detail_qty']) * str_replace(',', '', $value['rework_detail_unit_price'])
				));
				
				$this->db->exec("UPDATE stock SET stock_on_hand = (stock_on_hand - ?) WHERE item_id IN (SELECT item_id FROM item WHERE item_code = ?)",
				array(
					$value['rework_detail_qty'],
					$value['rework_detail_item_code']
				));
				
				$this->db->exec("INSERT INTO stock_history
					(item_id,
					rework_id,
					rework_invoice,
					stock_history_value,
					stock_history_date) SELECT
					item_id,
					:rework_id,
					:rework_invoice,
					:stock_history_value,
					:stock_history_date FROM item WHERE item_code = :item_code",
					array(
						':rework_id' => $rework_id,
						':rework_invoice' => $invoice_number,
						':stock_history_value' => $value['rework_detail_qty'],
						':stock_history_date' => date('Y-m-d H:i:s'),
						':item_code' => $value['rework_detail_item_code']
					)
				);
			}
		}
	}
	
	function getById($rework_id){
		$this->load(array('rework_id = ?', $rework_id));
		return $this->query;
	}
	
	function beforeEdit($rework_id){
		$this->db->exec("DELETE FROM rework_detail WHERE rework_id = ?", $rework_id);
	}
}