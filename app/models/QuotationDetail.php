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
				quotation_detail_brand) VALUES
				(:quotation_id,
				:quotation_detail_item_code,
				:quotation_detail_item_part_no,
				:quotation_detail_item_desc,
				:quotation_detail_qty,
				:quotation_detail_qty_up,
				:quotation_detail_unit_price,
				:quotation_detail_unit_price_up,
				:quotation_detail_amount,
				:quotation_detail_brand)",
				array(
					':quotation_id' => $quotation_id,
					':quotation_detail_item_code' => $value['quotation_detail_item_code'],
					':quotation_detail_item_part_no' => $value['quotation_detail_item_part_no'],
					':quotation_detail_item_desc' => $value['quotation_detail_item_desc'],
					':quotation_detail_qty' => $value['quotation_detail_qty'],
					':quotation_detail_qty_up' => $value['quotation_detail_qty_up'],
					':quotation_detail_unit_price' => $value['quotation_detail_unit_price'],
					':quotation_detail_unit_price_up' => $value['quotation_detail_unit_price_up'],
					':quotation_detail_amount' => $value['quotation_detail_qty'] * $value['quotation_detail_unit_price'],
					':quotation_detail_brand' => $value['quotation_detail_brand']
				));
			}
		}
	}
}