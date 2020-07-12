<?php

class ReportPrintController extends Controller {

	function Header(){
		$this->SetXY(5,5);
		$this->SetFont('Arial','BU',12);
		$this->Cell(195,5,'REKAPITULASI BULANAN',0,2,'C');
		
		$this->SetFont('Arial','B',10);
		$this->Cell(195,5,$this->f3->get('PARAMS.invoice_month').' '.$this->f3->get('PARAMS.invoice_year'),0,0,'C');
	}
	
	public function invoice(){
		$invoice_month = $this->f3->get('PARAMS.invoice_month') != 'ALL' ? $this->f3->get('PARAMS.invoice_month') : '';
		
		$invoice = new Invoice($this->db);
		$invoice_list = $invoice->getDataMonth($invoice_month, $this->f3->get('PARAMS.invoice_year'));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		
		$pdf = new ReportPrintController();
		$pdf->AliasNbPages();
		$pdf->AddPage('P','A4');
		$pdf->SetMargins(8,1,8);
		
		$pdf->SetY(20);
		
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(195,5,'Hal : 1',0,1,'R');
		
		$pdf->SetFont('Arial','B',10);
		$header = array('No.', 'Tanggal', 'Invoice', 'Nama Pelanggan', 'Jasa', 'Parts', 'PPN');
		$pdf->invoice_list($header, $invoice_list);
		
		$pdf->Output('Rekapitulasi_Bulan_'.$this->f3->get('PARAMS.invoice_month').'.pdf','I');
		exit;
	}
	
	public function ppn(){
		$invoice_month = $this->f3->get('PARAMS.invoice_month') != 'ALL' ? $this->f3->get('PARAMS.invoice_month') : '';
		
		$invoice = new Report($this->db);
		$invoice_list = $invoice->getDataPPN($invoice_month, $this->f3->get('PARAMS.invoice_year'));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		
		$pdf = new ReportPrintController();
		$pdf->AliasNbPages();
		$pdf->AddPage('P','A4');
		$pdf->SetMargins(8,1,8);
		
		$pdf->SetY(20);
		
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(195,5,'Hal : 1',0,1,'R');
		
		$pdf->SetFont('Arial','B',10);
		$header = array('No.', 'Tanggal', 'Invoice', 'Nama Pelanggan', 'Jasa', 'Parts', 'PPN');
		$pdf->invoice_list($header, $invoice_list);
		
		$pdf->Output('Rekapitulasi_PPN_'.$this->f3->get('PARAMS.invoice_month').'.pdf','I');
		exit;
	}
	
	function invoice_list($header, $invoice_list){
		$width = array(10, 25, 25, 50, 30, 30, 25);
		
		$this->SetFont('Arial','B',9);
		for($i = 0; $i < count($header); $i++)
			$this->Cell($width[$i],7,$header[$i],1,0,'L');
		$this->Ln();
		
		$this->SetFont('Arial','',9);
		foreach($invoice_list as $key => $value)
		{
			$this->Cell($width[0],5,$key + 1,'LR',0,'C');
			$this->Cell($width[1],5,date('d F Y', strtotime($value['invoice_date'])),'LR',0);
			$this->Cell($width[2],5,$value['invoice_number'],'LR',0);
			$this->Cell($width[3],5,strtoupper($value['customer_name']),'LR',0);
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['invoice_service_charge']),'R',0,'R');
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['invoice_part_charge']),'R',0,'R');
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(20,5,number_format($invoice_ppn),'R',0,'R');
			$this->Ln();
		}
		
		$this->Cell(array_sum($width),0,'','T');
		$this->Ln();
	}
	
	public function cash(){
		$invoice_month = $this->f3->get('PARAMS.invoice_month') != 'ALL' ? $this->f3->get('PARAMS.invoice_month') : '';
		
		$invoice = new Report($this->db);
		$invoice_list = $invoice->getDataCash($invoice_month, $this->f3->get('PARAMS.invoice_year'));
		
		$invoice_total = ($invoice->invoice_part_charge + $invoice->invoice_service_charge) - $invoice->invoice_discount;
		$invoice_ppn = $invoice_total * ($invoice->invoice_ppn / 100);
		
		$pdf = new ReportPrintController();
		$pdf->AliasNbPages();
		$pdf->AddPage('P','A4');
		$pdf->SetMargins(8,1,8);
		
		$pdf->SetY(20);
		
		$pdf->SetFont('Arial','I',9);
		$pdf->Cell(195,5,'Hal : 1',0,1,'R');
		
		$pdf->SetFont('Arial','B',10);
		$header = array('No.', 'Tanggal', 'Invoice', 'Nama Pelanggan', 'Total', 'Cash');
		$pdf->cash_list($header, $invoice_list);
		
		$pdf->Output('Rekapitulasi_Cash_'.$this->f3->get('PARAMS.invoice_month').'.pdf','I');
		exit;
	}
	
	function cash_list($header, $invoice_list){
		$width = array(10, 25, 25, 50, 30, 55);
		
		$this->SetFont('Arial','B',9);
		for($i = 0; $i < count($header); $i++)
			$this->Cell($width[$i],7,$header[$i],1,0,'L');
		$this->Ln();
		
		$this->SetFont('Arial','',9);
		foreach($invoice_list as $key => $value)
		{
			$this->Cell($width[0],5,$key + 1,'LR',0,'C');
			$this->Cell($width[1],5,date('d F Y', strtotime($value['invoice_date'])),'LR',0);
			$this->Cell($width[2],5,$value['invoice_number'],'LR',0);
			$this->Cell($width[3],5,strtoupper($value['customer_name']),'LR',0);
			$this->Cell(5,5,'Rp','L',0);
			$this->Cell(25,5,number_format($value['invoice_total']),'R',0,'R');
			$this->MultiCell($width[5],5,$value['invoice_note_payment'],'R',2);
		}
		
		$this->Cell(array_sum($width),0,'','T');
		$this->Ln();
	}
}