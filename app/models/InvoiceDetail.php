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
			if($value['invoice_detail_item_desc'] <> ''){
				$this->db->exec("INSERT INTO invoice_detail
				(invoice_id,
				invoice_detail_item_code,
				invoice_detail_item_part_no,
				invoice_detail_item_desc,
				invoice_detail_qty,
				invoice_detail_qty_up,
				invoice_detail_unit_price,
				invoice_detail_unit_price_up,
				invoice_detail_unit_price_temp,
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
				:invoice_detail_unit_price_temp,
				:invoice_detail_amount,
				:invoice_detail_brand,
				:invoice_detail_profit)",
				array(
					':invoice_id' => $invoice_id,
					':invoice_detail_item_code' => $value['invoice_detail_item_code'],
					':invoice_detail_item_part_no' => $value['invoice_detail_item_part_no'],
					':invoice_detail_item_desc' => $value['invoice_detail_item_desc'],
					':invoice_detail_qty' => str_replace(',', '', $value['invoice_detail_qty']),
					':invoice_detail_qty_up' => str_replace(',', '', $value['invoice_detail_qty_up']),
					':invoice_detail_unit_price' => str_replace(',', '', $value['invoice_detail_unit_price']),
					':invoice_detail_unit_price_up' => str_replace(',', '', $value['invoice_detail_unit_price_up']),
					':invoice_detail_unit_price_temp' => str_replace(',', '', $value['invoice_detail_unit_price_temp']),
					':invoice_detail_amount' => str_replace(',', '', $value['invoice_detail_qty']) * str_replace(',', '', $value['invoice_detail_unit_price']),
					':invoice_detail_brand' => $value['invoice_detail_brand'],
					':invoice_detail_profit' => (str_replace(',', '', $value['invoice_detail_unit_price']) - $value['invoice_detail_unit_price_temp']) * str_replace(',', '', $value['invoice_detail_qty'])
				));
				
				$this->db->exec("UPDATE stock SET stock_on_hand = (stock_on_hand - ?) WHERE item_id IN (SELECT item_id FROM item WHERE item_code = ?)",
				array(
					$value['invoice_detail_qty'],
					$value['invoice_detail_item_code']
				));
				
				$this->db->exec("INSERT INTO stock_history
					(item_id,
					invoice_id,
					stock_history_value,
					stock_history_date) SELECT
					item_id,
					:invoice_id,
					:stock_history_value,
					:stock_history_date FROM item WHERE item_code = :item_code",
					array(
						':invoice_id' => $invoice_id,
						':stock_history_value' => $value['invoice_detail_qty'],
						':stock_history_date' => date('Y-m-d H:i:s'),
						':item_code' => $value['invoice_detail_item_code']
					)
				);
			}
		}
	}
	
	function getById($invoice_id){
		$this->load(array('invoice_id = ?', $invoice_id));
		return $this->query;
	}
	
	function beforeEdit($invoice_id){
		$this->db->exec("DELETE FROM invoice_detail WHERE invoice_id = ?", $invoice_id);
	}
	
	public function getPriceHistory($invoice_detail_item_code){
		$output = array();
		
		$query = $this->db->exec("SELECT c.customer_name, i.invoice_date, d.invoice_detail_unit_price FROM invoice i JOIN customer c ON i.customer_id = c.customer_id JOIN invoice_detail d ON i.invoice_id = d.invoice_id WHERE d.invoice_detail_item_code = ? LIMIT 5", $invoice_detail_item_code);
			
		foreach($query as $data){
			$output[] = array(
				"customer_name" => $data['customer_name'],
				"invoice_detail_unit_price" => date('d-M-Y', strtotime($data['invoice_date'])).' | Rp '.number_format($data['invoice_detail_unit_price'])
			);
		}
		
		echo json_encode($output);
    }
}