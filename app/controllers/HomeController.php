<?php

class HomeController extends Controller {

	public function index(){
		$report = new Report($this->db);
		
		$this->f3->set('total_invoice', $report->getInvoice());
		$this->f3->set('total_quotation', $report->getQuotation());
		$this->f3->set('total_rework', $report->getRework());
		$this->f3->set('total_item', $report->getItem());
		
		$year = $this->f3->get('POST.year') ? $this->f3->get('POST.year') : date('Y');
		$this->f3->set('year', $year);
		
		$this->f3->set('jan', $report->getReport($year, 1));
		$this->f3->set('feb', $report->getReport($year, 2));
		$this->f3->set('mar', $report->getReport($year, 3));
		$this->f3->set('apr', $report->getReport($year, 4));
		$this->f3->set('may', $report->getReport($year, 5));
		$this->f3->set('jun', $report->getReport($year, 6));
		$this->f3->set('jul', $report->getReport($year, 7));
		$this->f3->set('aug', $report->getReport($year, 8));
		$this->f3->set('sep', $report->getReport($year, 9));
		$this->f3->set('oct', $report->getReport($year, 10));
		$this->f3->set('nov', $report->getReport($year, 11));
		$this->f3->set('dec', $report->getReport($year, 12));
		
		$stock = new Stock($this->db);
		$this->f3->set('data_min_stock', $stock->getMinStock());
		
		$stock_history = new StockHistory($this->db);
		$this->f3->set('data_stock_history', $stock_history->getThree());
		
		$this->f3->set('page_title','Monitoring Data dan Transaksi');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','home/index.html');
	}
	
	public function indexs(){
		$report = new Report($this->db);
		
		$sales = $this->f3->get('SESSION.manager') ? $this->f3->get('SESSION.id') : '%';
		$group = $this->f3->get('SESSION.group') ? $this->f3->get('SESSION.group') : '%';
		
		$this->f3->set('total',$report->total($sales, $group));
		$this->f3->set('new',$report->new_quotation($sales, $group));
		$this->f3->set('revision',$report->revision_quotation($sales, $group));
		$this->f3->set('cancel',$report->cancel_quotation($sales, $group));
		$this->f3->set('purchase_order',$report->purchase_order($sales, $group));
		
		$year = $this->f3->get('POST.year') ? $this->f3->get('POST.year') : date('Y');
		
		$this->f3->set('jan', $report->getReport($year, 1));
		$this->f3->set('feb', $report->getReport($year, 2));
		$this->f3->set('mar', $report->getReport($year, 3));
		$this->f3->set('apr', $report->getReport($year, 4));
		$this->f3->set('may', $report->getReport($year, 5));
		$this->f3->set('jun', $report->getReport($year, 6));
		$this->f3->set('jul', $report->getReport($year, 7));
		$this->f3->set('aug', $report->getReport($year, 8));
		$this->f3->set('sep', $report->getReport($year, 9));
		$this->f3->set('oct', $report->getReport($year, 10));
		$this->f3->set('nov', $report->getReport($year, 11));
		$this->f3->set('dec', $report->getReport($year, 12));
		
		$this->f3->set('customer_report',$report->getCustomerCount($year));
		
		$this->f3->set('page_title','Sales Quotation');
		$this->f3->set('header','header/header.html');
        $this->f3->set('view','home/index.html');
	}
}