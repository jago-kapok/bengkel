<?php
date_default_timezone_set("Asia/Jakarta");

class QuotationDetail extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'quotation_detail');
    }

    public function add($quotation_id){
		$f3 = \Base::instance();
		
		$data = $f3->get('POST.data');
		
		foreach($data as $value){
			if($value['quotation_detail_item_desc'] <> ''){
				$this->db->exec("INSERT INTO quotation_detail
				(quotation_id,
				quotation_detail_item_code,
				quotation_detail_item_part_no,
				quotation_detail_item_desc,
				quotation_detail_qty,
				quotation_detail_qty_up,
				quotation_detail_unit_price,
				quotation_detail_unit_price_up,
				quotation_detail_amount,
				quotation_detail_brand,
				quotation_detail_profit) VALUES
				(:quotation_id,
				:quotation_detail_item_code,
				:quotation_detail_item_part_no,
				:quotation_detail_item_desc,
				:quotation_detail_qty,
				:quotation_detail_qty_up,
				:quotation_detail_unit_price,
				:quotation_detail_unit_price_up,
				:quotation_detail_amount,
				:quotation_detail_brand,
				:quotation_detail_profit)",
				array(
					':quotation_id' => $quotation_id,
					':quotation_detail_item_code' => $value['quotation_detail_item_code'],
					':quotation_detail_item_part_no' => $value['quotation_detail_item_part_no'],
					':quotation_detail_item_desc' => $value['quotation_detail_item_desc'],
					':quotation_detail_qty' => str_replace(',', '', $value['quotation_detail_qty']),
					':quotation_detail_qty_up' => str_replace(',', '', $value['quotation_detail_qty_up']),
					':quotation_detail_unit_price' => str_replace(',', '', $value['quotation_detail_unit_price']),
					':quotation_detail_unit_price_up' => str_replace(',', '', $value['quotation_detail_unit_price_up']),
					':quotation_detail_amount' => str_replace(',', '', $value['quotation_detail_qty']) * str_replace(',', '', $value['quotation_detail_unit_price']),
					':quotation_detail_brand' => $value['quotation_detail_brand'],
					':quotation_detail_profit' => (str_replace(',', '', $value['quotation_detail_unit_price']) - $value['quotation_detail_unit_price_temp']) * str_replace(',', '', $value['quotation_detail_qty'])
				));
			}
		}
	}
	
	function getById($quotation_id){
		$this->load(array('quotation_id = ?', $quotation_id));
		return $this->query;
	}
}