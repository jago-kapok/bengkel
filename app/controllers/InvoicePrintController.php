<?php

class InvoicePrintController extends Controller {

	function Header(){
		$company = new Company($this->db);
		$company->getAll();
		
		$this->SetXY(5,5);
		$this->Image('ui/assets/img/logo.jpg',8.5,5,18,18);
		
		$this->SetXY(28,5);
		$this->SetFont('Arial','B',15);
		$this->Cell(80,6,$company->company_name,0,2,'C');
		
		$this->SetFont('Arial','B',9);
		$this->Cell(80,4,$company->company_desc,0,2,'C');
		$this->MultiCell(80,4,$company->company_info,0,'C');
		$this->Ln(0);
		
		$this->SetX(28);
		$this->SetFont('Arial','',9);
		$this->Cell(80,4,$company->company_address,0,2,'C');
		$this->Cell(80,4,'Telp. '.$company->company_phone.' Fax. '.$company->company_fax,0,2,'C');
	}
	
	public function prints(){
		$invoice = new Invoice($this->db);
		$invoice->getById($this->f3->get('PARAMS.invoice_id'));
		
		$invoice_total = $invoice->invoice_part_charge + $invoice->invoice_service_charge - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		
		$invoice_detail = new InvoiceDetail($this->db);
		$invoice_detail_list = $invoice_detail->getById($this->f3->get('PARAMS.invoice_id'));
		
		$pdf = new InvoicePrintController();
		$pdf->AliasNbPages();
		$pdf->AddPage('P','A4');
		$pdf->SetMargins(8,1,8);
		
		$pdf->SetY(35);
		$pdf->SetFont('Arial','BU',12);
		$pdf->Cell(195,5,'I N V O I C E',0,1,'C');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(195,4,'No : '.$invoice->invoice_number,0,1,'C');
		$pdf->Ln(5);
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'A / B / R',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.$invoice->quotation_number,0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'TT. IN',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.date('d F Y', strtotime($invoice->invoice_received_date)),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Model',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($invoice->quotation_model),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Unit / Engine',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($invoice->quotation_engine),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Serial No.',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($invoice->quotation_serial_number),0,1);
		$pdf->SetFont('Arial','B',10);
		
		$pdf->SetXY(120,49);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(80,5,'Kepada Yth.',0,2);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(80,5,$invoice->customer_name,0,2);
		$pdf->Cell(80,5,$invoice->customer_address,0,2);
		$pdf->Cell(80,5,$invoice->customer_city,0,2);
		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(195,5,'Hal. 1',0,2,'R');
		
		$header = array('No.', 'No. Part', 'Deskripsi', 'Qty', 'Harga per Unit', 'Total');
		$pdf->invoice_detail($header, $invoice_detail_list);
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(165,6,'Sub Total ','RL',0,'R');
		$pdf->Cell(5,6,'Rp','T',0);
		$pdf->Cell(25,6,number_format($invoice->invoice_part_charge),'RT',1,'R');
		
		$pdf->Cell(165,6,'','RL',0,0);
		$pdf->Cell(30,6,'','R',1,0);
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(165,6,'Biaya Servis ','RL',0,'R');
		$pdf->Cell(5,6,'Rp',0,0);
		$pdf->Cell(25,6,number_format($invoice->invoice_service_charge),'R',1,'R');
		
		if($invoice->invoice_ppn != 0){
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(165,6,'PPN '.$invoice->invoice_ppn.'% .....','RL',0,'R');
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(5,6,'Rp',0,0);
			$pdf->Cell(25,6,number_format($invoice_ppn),0,1,'R');
		}
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(165,8,'Harga Total ','RLB',0,'R');
		$pdf->Cell(5,8,'Rp','BT',0);
		$pdf->Cell(25,8,number_format($invoice_total + $invoice_ppn),'RBT',1,'R');
		
		$pdf->SetFont('Arial','I',10);
		$pdf->Cell(165,10,'Keterangan :',0,1);
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(195,6,$invoice->invoice_note_customer,1,1);
		$pdf->Ln();
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,8,'Penerima,',0,0,'C');
		$pdf->Cell(95,8,'',0,0,'C');
		$pdf->Cell(50,8,'Banjarmasin, '.date('d F Y', strtotime($invoice->invoice_date)),0,2,'C');
		$pdf->Ln(12);
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,8,'(                                )',0,0,'C');
		$pdf->Cell(95,8,'',0,0,'C');
		$pdf->Cell(50,8,'(                                )',0,0,'C');
			
		$pdf->Output($invoice->invoice_number.'.pdf','I');
		exit;
	}
	
	function invoice_detail($header, $invoice_detail_list){
		$this->SetFillColor(140,140,140);
		$this->SetTextColor(255);
		$width = array(10, 30, 80, 15, 30, 30);
		
		$this->SetFont('Arial','B',9);
		for($i = 0; $i < count($header); $i++)
			$this->Cell($width[$i],7,$header[$i],1,0,'C',true);
		$this->Ln();
		
		$this->SetFont('Arial','',9);
		$this->SetTextColor(0);
		foreach($invoice_detail_list as $key => $value)
		{
			$this->Cell($width[0],5,$key + 1,'LR',0,'C');
			$this->Cell($width[1],5,$value['invoice_detail_item_part_no'],'LR',0);
			$this->Cell($width[2],5,strtoupper($value['invoice_detail_item_desc']),'LR',0);
			$this->Cell($width[3],5,$value['invoice_detail_qty'] + $value['invoice_detail_qty_up'],'LR',0,'C');
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['invoice_detail_unit_price'] + $value['invoice_detail_unit_price_up']),'R',0,'R');
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['invoice_detail_amount']),'R',0,'R');
			$this->Ln();
		}
		
		$this->Cell(array_sum($width),0,'','T');
		$this->Ln();
	}
}