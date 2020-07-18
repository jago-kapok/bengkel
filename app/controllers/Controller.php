<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
require ('ui/fpdf.php');

class Controller extends FPDF {
	protected $f3;
	protected $db;

	function beforeroute(){
		if($this->f3->get('SESSION.id') === NULL){
			$this->f3->reroute('/login');
			exit;
		}
	}

	function afterroute(){		
		echo Template::instance()->render('layout.html');
	}

	function __construct(){
		parent::__construct();
		
        $f3 = \Base::instance();

        $db = new DB\SQL(
            $f3->get('db_dns'),
            $f3->get('db_user'),
            $f3->get('db_pass')
        );

		$this->f3 = $f3;
		$this->db = $db;
		
		$f3->ONERROR = function($f3){
		  if($f3->get('ERROR.code') == 404){
			echo "<h1>Forbidden Access</h1>";
			echo "<a href='/reminder'>Go Back</a>";
		  } else
			return FALSE;
		};
	}
}