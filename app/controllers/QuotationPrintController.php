<?php

class QuotationPrintController extends Controller {

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
		
		$this->SetXY(120,12.5);
		$this->SetFont('Arial','BU',12);
		$this->Cell(80,5,'ANALISA BIAYA PERBAIKAN',0,2,'C');
		
		$this->SetFont('Arial','BI',10);
		$this->Cell(80,5,'COST OF SERVICE ANALYSIS',0,2,'C');
	}
	
	public function prints(){
		$quotation = new Quotation($this->db);
		$quotation->getById($this->f3->get('PARAMS.quotation_id'));
		
		$quotation_total = $quotation->quotation_part_charge + $quotation->quotation_service_charge - $quotation->quotation_discount;
		$quotation_ppn = $quotation_total * ($quotation->quotation_ppn / 100);
		
		$quotation_detail = new QuotationDetail($this->db);
		$quotation_detail_list = $quotation_detail->getById($this->f3->get('PARAMS.quotation_id'));
		
		$pdf = new QuotationPrintController();
		$pdf->AliasNbPages();
		$pdf->AddPage('P','A4');
		$pdf->SetMargins(8,1,8);
		
		$pdf->SetY(40);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'No. Penawaran',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.$quotation->quotation_number,0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'TT. IN',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.date('d F Y', strtotime($quotation->quotation_received_date)),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Model',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($quotation->quotation_model),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Unit / Engine',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($quotation->quotation_engine),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Serial No.',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($quotation->quotation_serial_number),0,1);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(30,5,'Keterangan',0,0);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,5,': '.strtoupper($quotation->quotation_note_1),0,1);
		
		$pdf->SetXY(120,38);
		$pdf->Cell(80,10,'Banjarmasin, '.date('d F Y', strtotime($quotation->quotation_date)),0,2,'R');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(80,5,'Kepada Yth.',0,2);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(80,5,$quotation->customer_name,0,2);
		$pdf->Cell(80,5,$quotation->customer_address,0,2);
		$pdf->Cell(80,5,$quotation->customer_city,0,2);
		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(195,5,'Hal. 1',0,2,'R');
		
		$header = array('No.', 'No. Part', 'Deskripsi', 'Qty', 'Harga per Unit', 'Total');
		$pdf->quotation_detail($header, $quotation_detail_list);
		
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(165,6,'SUB TOTAL',1,0,'C');
		$pdf->Cell(5,6,'Rp','BT',0);
		$pdf->Cell(25,6,number_format($quotation->quotation_part_charge),'RBT',1,'R');
		$pdf->Ln(1);
		
		$pdf->SetFont('Arial','BI',10);
		$pdf->Cell(40,6,'BIAYA SERVIS',0,0,'R');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(125,6,'............................................................................................................................',0,0);
		$pdf->Cell(5,6,'Rp',0,0);
		$pdf->Cell(25,6,number_format($quotation->quotation_service_charge),0,1,'R');
		
		if($quotation->quotation_ppn != 0){
			$pdf->SetFont('Arial','BI',10);
			$pdf->Cell(40,6,'PPN '.$quotation->quotation_ppn.'%',0,0,'R');
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(125,6,'............................................................................................................................',0,0);
			$pdf->Cell(5,6,'Rp',0,0);
			$pdf->Cell(25,6,number_format($quotation_ppn),0,1,'R');
		}
		
		$pdf->SetFont('Arial','BI',10);
		$pdf->Cell(40,8,'HARGA TOTAL',0,0,'R');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(125,8,'',0,0);
		$pdf->Cell(5,8,'Rp','T',0);
		$pdf->Cell(25,8,number_format($quotation_total + $quotation_ppn),'T',1,'R');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,8,'Penerima Order,',0,0,'C');
		$pdf->Cell(95,8,'',0,0,'C');
		$pdf->Cell(50,8,'Menyetujui,',0,2,'C');
		$pdf->Ln(12);
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(50,8,'(                                )',0,0,'C');
		$pdf->Cell(95,8,'',0,0,'C');
		$pdf->Cell(50,8,'(                                )',0,0,'C');
			
		$pdf->Output($quotation->quotation_number.'.pdf','I');
		exit;
	}
	
	function quotation_detail($header, $quotation_detail_list){
		$this->SetFillColor(140,140,140);
		$this->SetTextColor(255);
		$width = array(10, 30, 80, 15, 30, 30);
		
		$this->SetFont('Arial','B',9);
		for($i = 0; $i < count($header); $i++)
			$this->Cell($width[$i],7,$header[$i],1,0,'C',true);
		$this->Ln();
		
		$this->SetFont('Arial','',9);
		$this->SetTextColor(0);
		foreach($quotation_detail_list as $key => $value)
		{
			$this->Cell($width[0],5,$key + 1,'LR',0,'C');
			$this->Cell($width[1],5,$value['quotation_detail_item_part_no'],'LR',0);
			$this->Cell($width[2],5,strtoupper($value['quotation_detail_item_desc']),'LR',0);
			$this->Cell($width[3],5,$value['quotation_detail_qty'] + $value['quotation_detail_qty_up'],'LR',0,'C');
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['quotation_detail_unit_price'] + $value['quotation_detail_unit_price_up']),'R',0,'R');
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['quotation_detail_amount']),'R',0,'R');
			$this->Ln();
		}
		
		$this->Cell(array_sum($width),0,'','T');
		$this->Ln();
	}
}