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
		
		$query = $this->db->exec("SELECT * FROM rework JOIN invoice ON rework.invoice_id = invoice.invoice_id JOIN customer ON invoice.customer_id = customer.customer_id WHERE
			invoice_number LIKE ? OR
			rework_total LIKE ? OR
			customer_name LIKE ? ORDER BY rework.rework_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM rework JOIN invoice ON rework.invoice_id = invoice.invoice_id JOIN customer ON invoice.customer_id = customer.customer_id WHERE
			invoice_number LIKE ? OR
			rework_total LIKE ? OR
			customer_name LIKE ?",
			array(
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
				date('d F Y', strtotime($data['rework_date'])),
				$data['customer_name'],
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
}