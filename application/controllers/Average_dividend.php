<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Average_dividend extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();
		
		$this->db->select(array(
			'id',
			'year', 
			'dividend_percent', 
			'average_percent',
			'dividend_value',
			'average_return_value',
			'status'
		));
		$this->db->from('coop_dividend_average_master');
		$this->db->order_by('year DESC');
		$row = $this->db->get()->result_array();
		
		$arr_data['data'] = $row;
		
		$this->libraries->template('average_dividend/index',$arr_data);
	}
	function save_data(){
		$data_insert = array();
		$data_insert['year'] = (date('Y')+543);
		$data_insert['status'] = '0';
		$this->db->insert('coop_dividend_average_master', $data_insert);
		
		$last_id = $this->db->insert_id();
		$average_percent = ($_POST['average_percent']/100);
		$dividend_percent = ($_POST['dividend_percent']/100);
		
		$sum_average_return = 0;
		$sum_dividend_return = 0;
		
		$data_arr = array();
			$this->db->select(array('SUM(t1.interest) as sum_interest','t1.member_id'));
			$this->db->from('coop_finance_transaction as t1');
			$this->db->where("
				t1.interest > 0
				AND t1.payment_date BETWEEN '".date('Y')."-01-01' AND '".date('Y')."-12-31'
			");
			$this->db->group_by("t1.member_id");
			$rs = $this->db->get()->result_array();
			
			foreach($rs as $key => $row){
				$data_arr[$row['member_id']]['sum_interest'] = $row['sum_interest'];
				$average_return = $row['sum_interest']*$average_percent;
				//echo "average : ".$average_return."<br>";
				@$data_arr[$row['member_id']]['sum_average_return_now'] += $average_return;
			}
			
			$this->db->select(array(
				't1.member_id',
				't1.share_collect_value',
				't1.share_date'
			));
			$this->db->from('coop_mem_share as t1');
			$this->db->where("
				share_status != '2'
				AND t1.share_date <= '".(date('Y')-1)."-12-31'
			");
			$this->db->order_by("t1.share_date ASC");
			$rs = $this->db->get()->result_array();
			$data_share = array();
			foreach($rs as $key => $row){
				$data_share[$row['member_id']]['member_id'] = $row['member_id'];
				$data_share[$row['member_id']]['share_collect_value'] = $row['share_collect_value'];
			}
			foreach($data_share as $key => $row){
				$share_collect_value = $row['share_collect_value'];
				$dividend_return = $share_collect_value*$dividend_percent*(12/12);
				@$data_arr[$row['member_id']]['sum_dividend_return_now'] += $dividend_return;
			}
			
			for($i=1; $i<=11; $i++){
				$this->db->select(array(
					't1.member_id',
					't1.share_collect_value',
					't1.share_date'
				));
				$this->db->from('coop_mem_share as t1');
				$this->db->where("
					share_status != '2'
					AND t1.share_date LIKE '".date('Y')."-".sprintf('%02d',$i)."%'
					AND t1.share_type = 'SPM'
				");
				$this->db->order_by("t1.share_date ASC");
				$rs = $this->db->get()->result_array();
				$data_share = array();
				foreach($rs as $key => $row){
					$data_share[$row['member_id']]['member_id'] = $row['member_id'];
					$data_share[$row['member_id']]['share_collect_value'] = $row['share_collect_value'];
				}
				foreach($data_share as $key => $row){
					$dividend_return = $row['share_collect_value']*$dividend_percent*((12-$i)/12);
					@$data_arr[$row['member_id']]['sum_dividend_return_now'] += $dividend_return;
				}
				
				
				$prev_date = date('Y-m-d',strtotime('-1 month' ,strtotime(date('Y')."-".sprintf('%02d',$i)."-07")));
				$now_date = date('Y')."-".sprintf('%02d',$i)."-07";
				$this->db->select(array(
					't1.member_id',
					't1.share_collect_value',
					't1.share_date'
				));
				$this->db->from('coop_mem_share as t1');
				$this->db->where("
					share_status != '2'
					AND t1.share_date BETWEEN '".$prev_date."' AND '".$now_date."'
					AND t1.share_type = 'SPA'
				");
				$this->db->order_by("t1.share_date ASC");
				$rs = $this->db->get()->result_array();
				$data_share = array();
				foreach($rs as $key => $row){
					$data_share[$row['member_id']]['member_id'] = $row['member_id'];
					$data_share[$row['member_id']]['share_collect_value'] = $row['share_collect_value'];
				}
				foreach($data_share as $key => $row){
					$dividend_return = $row['share_collect_value']*$dividend_percent*((12-$i)/12);
					@$data_arr[$row['member_id']]['sum_dividend_return_now'] += $dividend_return;
				}
				
			}
			//echo"<pre>";print_r($data_arr);exit;
			foreach($data_arr as $key => $value){
				$data_insert = array();
				$data_insert['member_id'] = $key;
				$data_insert['year'] = (date('Y')+543);
				$data_insert['dividend_percent'] = $_POST['dividend_percent'];
				$data_insert['average_percent'] = $_POST['average_percent'];
				$data_insert['dividend_value'] = number_format(@$value['sum_dividend_return_now'],2,'.','');
				$data_insert['average_return_value'] = number_format(@$value['sum_average_return_now'],2,'.','');
				$data_insert['master_id'] = $last_id;
				$this->db->insert('coop_dividend_average', $data_insert);
				
				//echo $sql_insert."<br>";
				$sum_dividend_return += number_format(@$value['sum_dividend_return_now'],2,'.','');
				$sum_average_return += number_format(@$value['sum_average_return_now'],2,'.','');
			}
			
		$data_insert = array();
		$data_insert['dividend_percent'] = $_POST['dividend_percent'];
		$data_insert['average_percent'] = $_POST['average_percent'];
		$data_insert['dividend_value'] = number_format($sum_dividend_return,2,'.','');
		$data_insert['average_return_value'] = number_format($sum_average_return,2,'.','');
		$this->db->where('id', $last_id);
		$this->db->update('coop_dividend_average_master', $data_insert);
		
	  $this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
	  //exit;
	  echo"<script>document.location.href='".base_url(PROJECTPATH.'/average_dividend')."'</script>";
	}
	function average_dividend_expect(){
		$data_arr = array();
		$arr_data = array();
		for($j=1; $j<=3; $j++){
			if($_POST['average_percent'][$j]=='' || $_POST['dividend_percent'][$j]==''){
				continue;
			}
			$average_percent = ($_POST['average_percent'][$j]/100);
			$dividend_percent = ($_POST['dividend_percent'][$j]/100);
			
			$sum_average_return = 0;
			$sum_dividend_return = 0;
				
				$this->db->select(array('SUM(t1.interest) as sum_interest'));
				$this->db->from('coop_finance_transaction as t1');
				$this->db->where("
					t1.interest > 0
					AND t1.payment_date BETWEEN '".date('Y')."-01-01' AND '".date('Y')."-12-31'
				");
				$row = $this->db->get()->result_array();
				
				$sum_interest = $row[0]['sum_interest'];
				
				$average_return = $sum_interest*$average_percent;
				$sum_average_return += $average_return;
				
				$this->db->select(array(
					't1.member_id',
					't1.share_collect_value',
					't1.share_date'
				));
				$this->db->from('coop_mem_share as t1');
				$this->db->where("t1.share_date <= '".(date('Y')-1)."-12-31'");
				$this->db->order_by("t1.share_date ASC");
				$rs = $this->db->get()->result_array();
				$data_share = array();
				foreach($rs as $key => $row){
					$data_share[$row['member_id']]['share_collect_value'] = $row['share_collect_value'];
				}
				foreach($data_share as $key => $row){
					$share_collect_value = $row['share_collect_value'];
					$dividend_return = $share_collect_value*$dividend_percent*(12/12);
					$sum_dividend_return += $dividend_return;
				}
				
				for($i=1; $i<=11; $i++){
					$this->db->select(array(
						't1.member_id',
						't1.share_collect_value',
						't1.share_date'
					));
					$this->db->from('coop_mem_share as t1');
					$this->db->where("
						share_status != '2'
						AND t1.share_type = 'SPM'
						AND t1.share_date LIKE '".date('Y')."-".sprintf('%02d',$i)."%'
					");
					$this->db->order_by("t1.share_date ASC");
					$rs = $this->db->get()->result_array();
					$data_share = array();
					foreach($rs as $key => $row){
						$data_share[$row['member_id']]['share_collect_value'] = $row['share_collect_value'];
					}
					foreach($data_share as $key => $row){
						$dividend_return = $row['share_collect_value']*$dividend_percent*((12-$i)/12);
						$sum_dividend_return += $dividend_return;
					}
					
					$prev_date = date('Y-m-d',strtotime('-1 month' ,strtotime(date('Y')."-".sprintf('%02d',$i)."-07")));
					$now_date = date('Y')."-".sprintf('%02d',$i)."-07";
					
					$this->db->select(array(
						't1.member_id',
						't1.share_collect_value',
						't1.share_date'
					));
					$this->db->from('coop_mem_share as t1');
					$this->db->where("
						share_status != '2'
						AND t1.share_date BETWEEN '".$prev_date."' AND '".$now_date."'
						AND t1.share_type = 'SPA'
					");
					$this->db->order_by("t1.share_date ASC");
					$rs = $this->db->get()->result_array();
					$data_share = array();
					foreach($rs as $key => $row){
						$data_share[$row['member_id']]['share_collect_value'] = $row['share_collect_value'];
					}
					foreach($data_share as $key => $row){
						$dividend_return = $row['share_collect_value']*$dividend_percent*((12-$i)/12);
						$sum_dividend_return += $dividend_return;
					}
					
				}
			//}
			$data_arr[$j]['average_percent'] = $_POST['average_percent'][$j];
			$data_arr[$j]['average_return'] = $sum_average_return;
			$data_arr[$j]['dividend_percent'] = $_POST['dividend_percent'][$j];
			$data_arr[$j]['dividend_return'] = $sum_dividend_return;
			
			
			$arr_data['data_arr'] = $data_arr;				
		}
		
		$this->db->select(array(
			'coop_name_th',
			'address1',
			'address2',
			'coop_img'
		));
		$this->db->from('coop_profile');
		$this->db->limit(1);
		$row_profile = $this->db->get()->result_array();
		$arr_data['row_profile'] = $row_profile[0];
			
		$this->load->view('average_dividend/average_dividend_expect',$arr_data);
	}
	function average_dividend_excel(){
		$arr_data = array();
		
		$arr_data['month_arr'] = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$arr_data['month_short_arr'] = array('1'=>'ม.ค.','2'=>'ก.พ.','3'=>'มี.ค.','4'=>'เม.ย.','5'=>'พ.ค.','6'=>'มิ.ย.','7'=>'ก.ค.','8'=>'ส.ค.','9'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');

		$this->db->select(array('id','mem_group_name'));
		$this->db->from('coop_mem_group');
		$rs_group = $this->db->get()->result_array();
		$mem_group_arr = array();
		foreach($rs_group as $key => $row_group){
			$mem_group_arr[$row_group['id']] = $row_group['mem_group_name'];
		}
		$arr_data['mem_group_arr'] = $mem_group_arr;
		
		$this->db->select(array(
				't1.member_id',
				't1.employee_id',
				't1.prename_short',
				't1.firstname_th',
				't1.lastname_th',
				't1.level',
				't1.dividend_percent',
				't1.dividend_value',
				't1.average_percent',
				't1.average_return_value'
			));
		$this->db->from('coop_average_dividend_excel as t1');
		$this->db->where("t1.master_id = '".$_GET['master_id']."'");
		$row = $this->db->get()->result_array();
		$arr_data['data'] = $row;
		$this->load->view('average_dividend/average_dividend_excel',$arr_data);
	}
	function approve(){
		if($_GET){
			$data_insert = array();
			$data_insert['status'] = $_GET['status_to'];
			$this->db->where('id', $_GET['id']);
			$this->db->update('coop_dividend_average_master', $data_insert);
				
			  if($_GET['status_to'] == '1'){
				$this->db->select(array(
					'member_id', 
					'dividend_value', 
					'average_return_value'
				));
				$this->db->from('coop_dividend_average');
				$this->db->where("master_id = '".$_GET['id']."'");
				$rs = $this->db->get()->result_array();
				foreach($rs as $key => $row){
					$this->db->select(array(
						'account_id'
					));
					$this->db->from('coop_maco_account');
					$this->db->where("mem_id = '".$row['member_id']."'");
					$this->db->limit(1);
					$row_account = $this->db->get()->result_array();
					$row_account = @$row_account[0];
					
					$this->db->select(array(
						'transaction_balance'
					));
					$this->db->from('coop_account_transaction');
					$this->db->where("account_id = '".$row_account['account_id']."'");
					$this->db->order_by('transaction_id DESC');
					$this->db->limit(1);
					$row_prev_trans = $this->db->get()->result_array();
					$row_prev_trans = @$row_prev_trans[0];
					
					$transaction_balance = @$row_prev_trans['transaction_balance'] + ($row['dividend_value']+$row['average_return_value']);
					
					$data_insert = array();
					$data_insert['transaction_time'] = date('Y-m-d H:i:s');
					$data_insert['transaction_list'] = 'XD';
					$data_insert['transaction_withdrawal'] = '0';
					$data_insert['transaction_deposit'] = ($row['dividend_value']+$row['average_return_value']);
					$data_insert['transaction_balance'] = $transaction_balance;
					$data_insert['user_id'] = $_SESSION['USER_ID'];
					$data_insert['account_id'] = $row_account['account_id'];
					$this->db->insert('coop_account_transaction', $data_insert);
				}
				$this->db->select(array(
					'dividend_value',
					'average_return_value'
				));
				$this->db->from('coop_dividend_average_master');
				$this->db->where("id = '".$_GET['id']."'");
				$row = $this->db->get()->result_array();
				$row = @$row[0];
				
				$data['coop_account']['account_description'] = "โอนเงินปันผลและเฉลี่ยคืนให้สมาชิก";
				$data['coop_account']['account_datetime'] = date('Y-m-d H:i:s');
				$i=0;
				$data['coop_account_detail'][$i]['account_type'] = 'debit';
				$data['coop_account_detail'][$i]['account_amount'] = ($row['dividend_value']+$row['average_return_value']);
				$data['coop_account_detail'][$i]['account_chart_id'] = '50700';
				$i++;
				$data['coop_account_detail'][$i]['account_type'] = 'credit';
				$data['coop_account_detail'][$i]['account_amount'] = ($row['dividend_value']+$row['average_return_value']);
				$data['coop_account_detail'][$i]['account_chart_id'] = '20100';
				$this->account_transaction->account_process($data);
			  }
			  $this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			   echo"<script>document.location.href='".base_url(PROJECTPATH.'/average_dividend/approve')."'</script>";
		  }
		  $arr_data = array();
		  
			$this->db->select(array(
				'id',
				'year', 
				'dividend_percent', 
				'average_percent',
				'dividend_value',
				'average_return_value',
				'status'
			));
			$this->db->from('coop_dividend_average_master');
			$this->db->order_by("id DESC"); 
			$row = $this->db->get()->result_array();
			$arr_data['data'] = $row;
		  $this->libraries->template('average_dividend/approve',$arr_data);
	}
}
