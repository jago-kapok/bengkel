<?php
date_default_timezone_set("Asia/Jakarta");

class Rework extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'rework');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT * FROM rework JOIN invoice ON rework.invoice_id = invoice.invoice_id JOIN customer ON invoice.customer_id = customer.customer_id JOIN quotation ON invoice.invoice_id = quotation.quotation_id WHERE
			invoice_number LIKE ? OR
			rework_total LIKE ? OR
			DATE_FORMAT(rework_date, '%d-%m-%Y') LIKE ? OR
			DATE_FORMAT(rework_date, '%d-%M-%Y') LIKE ? OR
			customer_name LIKE ? OR
			quotation_serial_number LIKE ? OR
			quotation_engine LIKE ? ORDER BY rework.rework_id DESC LIMIT ? OFFSET ?",
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
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM rework JOIN invoice ON rework.invoice_id = invoice.invoice_id JOIN customer ON invoice.customer_id = customer.customer_id JOIN quotation ON invoice.invoice_id = quotation.quotation_id WHERE
			invoice_number LIKE ? OR
			rework_total LIKE ? OR
			DATE_FORMAT(rework_date, '%d-%m-%Y') LIKE ? OR
			DATE_FORMAT(rework_date, '%d-%M-%Y') LIKE ? OR
			customer_name LIKE ? OR
			quotation_serial_number LIKE ? OR
			quotation_engine LIKE ?",
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
				$data['invoice_number'],
				date('d-m-Y', strtotime($data['invoice_date'])),
				date('d-m-Y', strtotime($data['rework_date'])),
				$data['customer_name'],
				$data['quotation_serial_number'].' | '.$data['quotation_engine'],
				number_format($data['rework_total']),
				$data['rework_id']
			);
		}

		echo json_encode($output);
	}
	
	public function add(){
		$f3 = \Base::instance();
		
		$this->copyFrom('POST');
		$this->rework_total = str_replace(',', '', $f3->get('POST.rework_total'));
		$this->save();
	}
	
	public function getById($rework_id){
		$this->load(array('rework_id = ?', $rework_id));
		$this->copyTo('POST');
	}
	
	public function edit($rework_id){
		$this->load(array('rework_id = ?', $rework_id));
		
		$this->copyFrom('POST');
		$this->update();
	}
	
	public function beforeEdit($rework_id){
		$query = $this->db->exec("SELECT * FROM rework_detail WHERE rework_id = ?", $rework_id);
		
		foreach($query as $data){
			$this->db->exec("UPDATE stock SET stock_on_hand = (stock_on_hand + ?) WHERE item_id IN (SELECT item_id FROM stock_history WHERE rework_id = ?)",
				array(
					$data['invoice_detail_qty'],
					$rework_id
				)
			);
		}
		
		$this->db->exec("DELETE FROM stock_history WHERE rework_id = ?", $rework_id);
	}
}