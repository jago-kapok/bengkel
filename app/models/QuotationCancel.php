<?php
date_default_timezone_set("Asia/Jakarta");

class QuotationCancel extends DB\SQL\Mapper {
    public function __construct(DB\SQL $db){
        parent::__construct($db, 'quotation');
    }

    public function data($draw, $length, $offset, $search){
		$total = $this->count();
		$output = array();
		$output['draw'] = $draw;
		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();
		
		$query = $this->db->exec("SELECT quotation.*, invoice.invoice_number AS invoice_number, customer.customer_name FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id LEFT JOIN invoice ON quotation.quotation_id = invoice.quotation_id WHERE
			(quotation_number LIKE ? OR
			customer_name LIKE ? OR
			invoice_number LIKE ?) AND
			quotation_status = 2 ORDER BY quotation.quotation_id DESC LIMIT ? OFFSET ?",
			array(
				'%'.$search.'%',
				'%'.$search.'%',
				'%'.$search.'%',
				$length,
				$offset
			)
		);
			
		$total = $this->db->exec("SELECT COUNT(*) AS TotalFilter FROM quotation JOIN customer ON quotation.customer_id = customer.customer_id LEFT JOIN invoice ON quotation.quotation_id = invoice.quotation_id WHERE
			(quotation_number LIKE ? OR
			customer_name LIKE ? OR
			invoice_number LIKE ?) AND
			quotation_status = 2",
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
				$data['quotation_number'],
				date('d F Y', strtotime($data['quotation_date'])),
				$data['customer_name'],
				$data['quotation_serial_number'],
				$data['quotation_engine'],
				number_format($data['quotation_part_charge'] + $data['quotation_service_charge']),
				$data['invoice_number'],
				$data['quotation_id']
			);
		}

		echo json_encode($output);
	}
}