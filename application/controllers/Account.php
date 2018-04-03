<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();
		
		
		$x=0;
		$join_arr = array();
<<<<<<< HEAD
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_account');
		$this->paginater_all->where("account_status != '2'");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(5);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('account_id DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		
		
=======
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_account');
		$this->paginater_all->where("account_status != '2'");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(5);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('account_id DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		
		
>>>>>>> 902ec510cba942871d7ce389dc3e50a883cebde8
		foreach($row['data'] as $key => $value){
			
			$this->db->select(array('t1.*','t2.account_chart'));
			$this->db->from('coop_account_detail as t1');
			$this->db->join('coop_account_chart as t2','t1.account_chart_id = t2.account_chart_id','inner');
			$this->db->where("t1.account_id = '".$value['account_id']."'");
			$this->db->order_by("account_detail_id ASC");
			$row_detail = $this->db->get()->result_array();
			
			$row['data'][$key]['account_detail'] = $row_detail;
		}

		$i = $row['page_start'];

		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		
		$arr_data['space'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
		
		$this->libraries->template('account/index',$arr_data);
	}
	public function account_save()
	{
		//echo"<pre>";print_r($_POST);exit;
		$data = $_POST['data'];
		$account_datetime_arr = explode('/',$data['coop_account']['account_datetime']);
		$data['coop_account']['account_datetime'] = ($account_datetime_arr[2]-543)."-".sprintf('%02d',$account_datetime_arr[1])."-".sprintf('%02d',$account_datetime_arr[0]);
		
		$data_insert = array();
		$data_insert['account_description'] = $data['coop_account']['account_description'];
		$data_insert['account_datetime'] = $data['coop_account']['account_datetime'];
		$data_insert['account_status'] = '0';
		$this->db->insert('coop_account', $data_insert);
		
		$account_id = $this->db->insert_id();
		
		foreach($data['coop_account_detail'] as $key => $value){
			
			$data_insert = array();
			$data_insert['account_id'] = $account_id;
			$data_insert['account_type'] = $value['account_type'];
			$data_insert['account_amount'] = $value['account_amount'];
			$data_insert['account_chart_id'] = $value['account_chart_id'];
			$this->db->insert('coop_account_detail', $data_insert);
			
		}
		echo"<script> document.location.href='".base_url(PROJECTPATH.'/account')."'; </script>";
	}
	
	function ajax_add_account_detail(){
		$arr_data = array();
		$arr_data['type'] = $_POST['type'];
		$arr_data['input_number'] = $_POST['input_number'];
		
		$this->db->select(array('*'));
		$this->db->from('coop_account_chart');
		$this->db->order_by("account_chart_id ASC");
		$row_account_chart = $this->db->get()->result_array();
		
		$arr_data['row_account_chart'] = $row_account_chart;
	
		$this->load->view('account/ajax_add_account_detail',$arr_data);
	}
	
	function account_day_book(){
		$arr_data = array();
		
		$arr_data['month_arr'] = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		
		$this->libraries->template('account/account_day_book',$arr_data);
	}
	
<<<<<<< HEAD
	function ajax_check_day_book(){
=======
	function ajax_check_day_book(){		
>>>>>>> 902ec510cba942871d7ce389dc3e50a883cebde8
		if(@$_POST['report_date'] != ''){
			$date_arr = explode('/',$_POST['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
<<<<<<< HEAD
			$where = " AND account_datetime LIKE '".@$year."-".sprintf("%02d",@$month)."-".sprintf("%02d",@$day)."%'";
=======
		
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
>>>>>>> 902ec510cba942871d7ce389dc3e50a883cebde8
		}else{
			if(@$_POST['month']!='' && @$_POST['year']!=''){
				$day = '';
				$month = @$_POST['month'];
				$year = (@$_POST['year']-543);
<<<<<<< HEAD
				$where = "AND account_datetime LIKE '".@$year.'-'.sprintf("%02d",@$month)."%'";
=======
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
>>>>>>> 902ec510cba942871d7ce389dc3e50a883cebde8
			}else{
				$day = '';
				$month = '';
				$year = (@$_POST['year']-543);
<<<<<<< HEAD
				$where = "AND account_datetime LIKE '".@$year."%'";
=======
				$where = " AND account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
>>>>>>> 902ec510cba942871d7ce389dc3e50a883cebde8
			}
		}
		$this->db->select(array('*'));
		$this->db->from('coop_account');
		$this->db->where("account_status != '2' ".$where);
		$this->db->order_by("account_datetime ASC");
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		//print_r($this->db->last_query());exit;
		if(@$row[0]['account_id'] != ''){
			echo "success";
		}
		exit;
	}
	
	function account_day_book_excel(){
		$arr_data = array();
		
		if(@$_GET['report_date'] != ''){
			$date_arr = explode('/',@$_GET['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_GET['month']!='' && @$_GET['year']!=''){
				$day = '';
				$month = @$_GET['month'];
				$year = (@$_GET['year']-543);
				
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_GET['year']-543);
				$where = " AND account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}
		$arr_data['day'] = $day;
		$arr_data['month'] = $month;
		$arr_data['year'] = $year;
		
		$this->db->select(array(
			'account_id',
			'account_datetime',
			'account_description',
			'account_detail_id',
			'account_type',
			'account_amount',
			'account_chart_id',
			'account_chart'
		));
		$this->db->from('account_day_book');
		$this->db->where("1=1 ".$where);
		$this->db->order_by("
			account_datetime ASC,
			account_detail_id ASC
			"
		);
		$rs = $this->db->get()->result_array();
		$data = array();
		foreach($rs as $key => $row){
			$data[$row['account_id']]['account_datetime'] = $row['account_datetime'];
			$data[$row['account_id']]['account_description'] = $row['account_description'];
			$data[$row['account_id']]['detail'][$row['account_detail_id']] = $row;
		}
		$arr_data['data'] = $data;
		$this->load->view('account/account_day_book_excel',$arr_data);
	}
	
	function account_chart_report(){
		$arr_data = array();
		$arr_data['month_arr'] = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		
		$this->libraries->template('account/account_chart_report',$arr_data);
	}
	
	function ajax_check_account_chart_report(){
		if($_POST['report_date'] != ''){
			$date_arr = explode('/',$_POST['report_date']);
			$day = (int)$date_arr[0];
			$month = (int)$date_arr[1];
			$year = (int)$date_arr[2];
			$year -= 543;
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if($_POST['month']!='' && $_POST['year']!=''){
				$day = '';
				$month = $_POST['month'];
				$year = ($_POST['year']-543);
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = ($_POST['year']-543);
				$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}
		$this->db->select(array(
			't1.account_id',
			't1.account_datetime',
			't2.account_type'
		));
		$this->db->from('coop_account as t1');
		$this->db->join('coop_account_detail as t2','t1.account_id = t2.account_id','inner');
		$this->db->where("t1.account_status != '2' ".$where);
		$this->db->order_by("account_datetime ASC");
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		if(@$row[0]['account_id'] != ''){
			echo "success";
		}
		exit;
	}
	
	function account_chart_report_excel(){
		$arr_data = array();
		
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$month_short_arr = array('1'=>'ม.ค.','2'=>'ก.พ.','3'=>'มี.ค.','4'=>'เม.ย.','5'=>'พ.ค.','6'=>'มิ.ย.','7'=>'ก.ค.','8'=>'ส.ค.','9'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
		$arr_data['month_arr'] = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		
		if(@$_GET['report_date'] != ''){
			$date_arr = explode('/',@$_GET['report_date']);
			$day = (int)$date_arr[0];
			$month = (int)$date_arr[1];
			$year = (int)$date_arr[2];
			$year -= 543;
			$file_name_text = $day."_".$month_arr[$month]."_".($year+543);
			
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where_date = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_GET['month']!='' && @$_GET['year']!=''){
				$day = '';
				$month = @$_GET['month'];
				$year = (@$_GET['year']-543);
				$file_name_text = $month_arr[$month]."_".($year+543);			
				
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where_date = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_GET['year']-543);
				$file_name_text = ($year+543);
				$where_date = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}
		$arr_data['day'] = $day;
		$arr_data['month'] = $month;
		$arr_data['year'] = $year;
		$arr_data['where_date'] = $where_date;
		$arr_data['file_name_text'] = $file_name_text;
		
		$this->db->select(array(
			't1.account_id',
			't1.account_datetime',
			't2.account_type',
			't2.account_chart_id',
			't3.account_chart',
			't2.account_amount'
		));
		$this->db->from('coop_account as t1');
		$this->db->join('coop_account_detail as t2','t1.account_id = t2.account_id','left');
		$this->db->join('coop_account_chart as t3','t2.account_chart_id = t3.account_chart_id','left');
		$this->db->where("t1.account_status <> '2' ".$where_date);
		$rs = $this->db->get()->result_array();
		
		$data = array();
		$sum_debit = array();
		$sum_credit = array();
		$count_type = array();
		foreach($rs as $key => $row){
			$this->db->select(array(
				'account_type'
			));
			$this->db->from('coop_account_detail');
			$this->db->where("account_id = '".$row['account_id']."'");
			$rs_main = $this->db->get()->result_array();
			$data_main_arr = array();
			foreach($rs_main as $key3 => $row_main){
				@$data_main_arr[$row_main['account_type']]++;
			}
			if($data_main_arr['credit'] > $data_main_arr['debit']){
				$data_main = 'debit';
			}else{
				$data_main = 'credit';
			}
			if($row['account_type'] == 'credit'){
				$account_find = 'debit';
			}else{
				$account_find = 'credit';
			}
			
			$this->db->select(array(
				't1.account_chart_id',
				't1.account_type',
				't1.account_amount',
				't2.account_chart'
			));
			$this->db->from('coop_account_detail as t1');
			$this->db->join('coop_account_chart as t2','t1.account_chart_id = t2.account_chart_id','inner');
			$this->db->where("account_id = '".$row['account_id']."' AND account_type = '".$account_find."'");
			$rs_detail = $this->db->get()->result_array();
			
			foreach($rs_detail as $key2 => $row_detail){
				if($row_detail['account_type'] == $data_main){
					$account_amount = $row['account_amount'];
				}else{
					$account_amount = $row_detail['account_amount'];
				}
				if(empty($count_type[$row['account_chart_id']])){
					$count_type[$row['account_chart_id']]['debit'] = 0;
					$count_type[$row['account_chart_id']]['credit'] = 0;
				}
				if(empty($sum_debit[$row['account_chart_id']])){
					$sum_debit[$row['account_chart_id']] = 0;
				}
				if(empty($sum_credit[$row['account_chart_id']])){
					$sum_credit[$row['account_chart_id']] = 0;
				}
				$data[$row['account_chart_id']]['account_chart'] = $row['account_chart'];
				if($row_detail['account_type']=='debit'){
					$data[$row['account_chart_id']][$row_detail['account_type']][$count_type[$row['account_chart_id']]['debit']]['account_chart'] = $row_detail['account_chart'];
					$data[$row['account_chart_id']][$row_detail['account_type']][$count_type[$row['account_chart_id']]['debit']]['account_amount'] = $account_amount;
					$data[$row['account_chart_id']][$row_detail['account_type']][$count_type[$row['account_chart_id']]['debit']]['account_datetime'] = date('Y-m-d',strtotime($row['account_datetime']));
					$sum_debit[$row['account_chart_id']] += $account_amount;
					$count_type[$row['account_chart_id']]['debit']++;
				}else{
					$data[$row['account_chart_id']][$row_detail['account_type']][$count_type[$row['account_chart_id']]['credit']]['account_chart'] = $row_detail['account_chart'];
					$data[$row['account_chart_id']][$row_detail['account_type']][$count_type[$row['account_chart_id']]['credit']]['account_amount'] = $account_amount;
					$data[$row['account_chart_id']][$row_detail['account_type']][$count_type[$row['account_chart_id']]['credit']]['account_datetime'] = date('Y-m-d',strtotime($row['account_datetime']));
					$sum_credit[$row['account_chart_id']] += $account_amount;
					$count_type[$row['account_chart_id']]['credit']++;
				}
				
			}
		}
		$arr_data['data'] = $data;
		$arr_data['sum_debit'] = $sum_debit;
		$arr_data['sum_credit'] = $sum_credit;
		$arr_data['count_type'] = $count_type;
		$this->load->view('account/account_chart_report_excel',$arr_data);
	}

	function coop_account_experimental_budget(){
		$arr_data = array();
		$arr_data['month_arr'] = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		
		$this->libraries->template('account/coop_account_experimental_budget',$arr_data);
	}
	
	function ajax_check_account_experimental_budget(){

		if(@$_POST['report_date'] != ''){
			$date_arr = explode('/',@$_POST['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_POST['month']!='' && @$_POST['year']!=''){
				$day = '';
				$month = @$_POST['month'];
				$year = (@$_POST['year']-543);
				
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_POST['year']-543);
				$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}

		$this->db->select(array('t1.account_id',
								't2.account_type',
								't2.account_amount',
								't2.account_chart_id',
								't3.account_chart'
						));
		$this->db->from('coop_account as t1');
		$this->db->join("coop_account_detail as t2", "t1.account_id = t2.account_id", "inner");
		$this->db->join("coop_account_chart as t3", "t2.account_chart_id = t3.account_chart_id", "inner");
		$this->db->where("1=1 {$where}");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0];
		if(@$row['account_id'] != ''){
			echo "success";
		}
	}
	
	function coop_account_experimental_budget_excel(){
		$arr_data = array();
		
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$month_short_arr = array('1'=>'ม.ค.','2'=>'ก.พ.','3'=>'มี.ค.','4'=>'เม.ย.','5'=>'พ.ค.','6'=>'มิ.ย.','7'=>'ก.ค.','8'=>'ส.ค.','9'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
		$arr_data['month_arr'] = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		
		if(@$_GET['report_date'] != ''){
			$date_arr = explode('/',@$_GET['report_date']);
			$day = (int)$date_arr[0];
			$month = (int)$date_arr[1];
			$year = (int)$date_arr[2];
			$year -= 543;
			$textTitle = "วันที่ ".$day." ".$month_arr[$month]." ".($year+543);
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_GET['month']!='' && @$_GET['year']!=''){
				$day = '';
				$month = @$_GET['month'];
				$year = (@$_GET['year']-543);
				$textTitle = "เดือน ".$month_arr[$month]." ".($year+543);
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_GET['year']-543);
				$textTitle = "ปี ".($year+543);
				$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}

		$arr_data['day'] = $day;
		$arr_data['month'] = $month;
		$arr_data['year'] = $year;
		$arr_data['textTitle'] = $textTitle;
		
		$this->db->select(array(
			't2.account_type',
			't2.account_amount',
			't2.account_chart_id',
			't3.account_chart'
		));
		$this->db->from('coop_account as t1');
		$this->db->join("coop_account_detail as t2", "t1.account_id = t2.account_id", "inner");
		$this->db->join("coop_account_chart as t3", "t2.account_chart_id = t3.account_chart_id", "inner");
		$this->db->where("1=1 {$where}");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = @$rs;
		
		$this->load->view('account/coop_account_experimental_budget_excel',$arr_data);
	}
	
	function coop_account_balance_sheet(){
		$arr_data = array();
		$arr_data['month_arr'] = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		
		$this->libraries->template('account/coop_account_balance_sheet',$arr_data);
	}
	
	function ajax_check_account_balance_sheet(){
		
		if(@$_POST['report_date'] != ''){
			$date_arr = explode('/',@$_POST['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_POST['month']!='' && @$_POST['year']!=''){
				$day = '';
				$month = @$_POST['month'];
				$year = (@$_POST['year']-543);
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_POST['year']-543);
				$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}

		$this->db->select(array('t1.account_id',
								't1.account_datetime',
								't2.account_type'
							));
		$this->db->from('coop_account as t1');
		$this->db->join("coop_account_detail as t2", "t1.account_id = t2.account_id", "inner");
		$this->db->where("t1.account_status <> '2' {$where}");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0];
		if(@$row['account_id'] != ''){
			echo "success";
		}
	}
	
	function coop_account_balance_sheet_excel(){
		$arr_data = array();
		
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$month_short_arr = array('1'=>'ม.ค.','2'=>'ก.พ.','3'=>'มี.ค.','4'=>'เม.ย.','5'=>'พ.ค.','6'=>'มิ.ย.','7'=>'ก.ค.','8'=>'ส.ค.','9'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
		$arr_data['month_arr'] = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		
		if(@$_GET['report_date'] != ''){
			$date_arr = explode('/',@$_GET['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_GET['month']!='' && @$_GET['year']!=''){
				$day = '';
				$month = @$_GET['month'];
				$year = (@$_GET['year']-543);
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_GET['year']-543);
				$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}
		$arr_data['day'] = $day;
		$arr_data['month'] = $month;
		$arr_data['year'] = $year;
		
		$this->load->view('account/coop_account_balance_sheet_excel',$arr_data);
	}
	
	function coop_account_profit_lost_statement(){
		$arr_data = array();
		$arr_data['month_arr'] = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		
		$this->libraries->template('account/coop_account_profit_lost_statement',$arr_data);
	}
	
	function ajax_check_account_profit_lost_statement(){
		
		if(@$_POST['report_date'] != ''){
			$date_arr = explode('/',@$_POST['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
			$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
			$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
		}else{
			if(@$_POST['month']!='' && @$_POST['year']!=''){
				$day = '';
				$month = @$_POST['month'];
				$year = (@$_POST['year']-543);
				$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
				$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";

			}else{
				$day = '';
				$month = '';
				$year = (@$_POST['year']-543);
				$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
			}
		}

		$this->db->select(array('t1.account_id',
								't1.account_datetime',
								't2.account_type'
							));
		$this->db->from('coop_account as t1');
		$this->db->join("coop_account_detail as t2", "t1.account_id = t2.account_id", "inner");
		$this->db->where("t1.account_status <> '2' {$where}");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0];
		if(@$row['account_id'] != ''){
			echo "success";
		}
	}
	
	function coop_account_profit_lost_statement_excel(){
		$arr_data = array();
		
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$month_short_arr = array('1'=>'ม.ค.','2'=>'ก.พ.','3'=>'มี.ค.','4'=>'เม.ย.','5'=>'พ.ค.','6'=>'มิ.ย.','7'=>'ก.ค.','8'=>'ส.ค.','9'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
		$arr_data['month_arr'] = $month_arr;
		$arr_data['month_short_arr'] = $month_short_arr;
		
		if(@$_GET['report_date'] != ''){
				$date_arr = explode('/',@$_GET['report_date']);
				$day = (int)@$date_arr[0];
				$month = (int)@$date_arr[1];
				$year = (int)@$date_arr[2];
				$year -= 543;
				$s_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 00:00:00.000';
				$e_date = $year.'-'.sprintf("%02d",@$month).'-'.sprintf("%02d",@$day).' 23:59:59.000';
				$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";
			}else{
				if(@$_GET['month']!='' && @$_GET['year']!=''){
					$day = '';
					$month = @$_GET['month'];
					$year = (@$_GET['year']-543);
					$s_date = $year.'-'.sprintf("%02d",@$month).'-01'.' 00:00:00.000';
					$e_date = date('Y-m-t',strtotime($s_date)).' 23:59:59.000';
					$where = " AND t1.account_datetime BETWEEN '".$s_date."' AND '".$e_date."'";

				}else{
					$day = '';
					$month = '';
					$year = (@$_GET['year']-543);
					$where = " AND t1.account_datetime BETWEEN '".$year."-01-01 00:00:00.000' AND '".$year."-12-31 23:59:59.000' ";
				}
			}
		$arr_data['day'] = $day;
		$arr_data['month'] = $month;
		$arr_data['year'] = $year;

		$this->db->select(array('account_chart_id','account_chart',));
		$this->db->from('coop_account_chart');
		$this->db->order_by("account_chart_id ASC");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = @$rs;
		
		$this->db->select(array('t1.account_datetime',
								't2.account_chart_id',
								't2.account_amount'
							));
		$this->db->from('coop_account as t1');
		$this->db->join("coop_account_detail as t2", "t1.account_id = t2.account_id", "inner");
		$this->db->where("t1.account_datetime BETWEEN '".($year-1)."-01-01' AND '".$year."-12-31'");
		$rs_detail = $this->db->get()->result_array();
		$arr_data['rs_detail'] = @$rs_detail[0];
		
		$this->load->view('account/coop_account_profit_lost_statement_excel',$arr_data);
	}
	
	
}
