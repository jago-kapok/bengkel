<?php
date_default_timezone_set("Asia/Jakarta");

class InvoiceDetail extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'invoice_detail');
    }

    public function add($invoice_id){
		$f3 = \Base::instance();
		
		$data = $f3->get('POST.data');
		
		foreach($data as $value){
			if($value['quotation_detail_item_desc'] <> ''){
				$this->db->exec("INSERT INTO invoice_detail
				(invoice_id,
				invoice_detail_item_code,
				invoice_detail_item_part_no,
				invoice_detail_item_desc,
				invoice_detail_qty,
				invoice_detail_qty_up,
				invoice_detail_unit_price,
				invoice_detail_unit_price_up,
				invoice_detail_amount,
				invoice_detail_brand,
				invoice_detail_profit) VALUES
				(:invoice_id,
				:invoice_detail_item_code,
				:invoice_detail_item_part_no,
				:invoice_detail_item_desc,
				:invoice_detail_qty,
				:invoice_detail_qty_up,
				:invoice_detail_unit_price,
				:invoice_detail_unit_price_up,
				:invoice_detail_amount,
				:invoice_detail_brand,
				:invoice_detail_profit)",
				array(
					':invoice_id' => $invoice_id,
					':invoice_detail_item_code' => $value['quotation_detail_item_code'],
					':invoice_detail_item_part_no' => $value['quotation_detail_item_part_no'],
					':invoice_detail_item_desc' => $value['quotation_detail_item_desc'],
					':invoice_detail_qty' => str_replace(',', '', $value['quotation_detail_qty']),
					':invoice_detail_qty_up' => str_replace(',', '', $value['quotation_detail_qty_up']),
					':invoice_detail_unit_price' => str_replace(',', '', $value['quotation_detail_unit_price']),
					':invoice_detail_unit_price_up' => str_replace(',', '', $value['quotation_detail_unit_price_up']),
					':invoice_detail_amount' => str_replace(',', '', $value['quotation_detail_qty']) * str_replace(',', '', $value['quotation_detail_unit_price']),
					':invoice_detail_brand' => $value['quotation_detail_brand'],
					':invoice_detail_profit' => (str_replace(',', '', $value['quotation_detail_unit_price']) - $value['quotation_detail_unit_price_temp']) * str_replace(',', '', $value['quotation_detail_qty'])
				));
			}
		}
	}
	
	function getById($invoice_id){
		$this->load(array('invoice_id = ?', $invoice_id));
		return $this->query;
	}
}