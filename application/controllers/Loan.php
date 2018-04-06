<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();
		
		if($this->input->get('member_id')!=''){
			$member_id = $this->input->get('member_id');
		}else{
			$member_id = '';
		}
		$arr_data = array();
		$arr_data['member_id'] = $member_id;

		$this->db->select('*');
		$this->db->from('coop_share_setting');
		$this->db->order_by('setting_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['share_value'] = $row[0]['setting_value'];

		if($member_id != '') {
			$this->db->select('*');
			$this->db->from('coop_mem_apply');
			$this->db->where("member_id = '".$member_id."'");
			$row = $this->db->get()->result_array();
			$arr_data['row_member'] = $row[0];
			
			$this->db->select('*');
			$this->db->from('coop_mem_share');
			$this->db->where("member_id = '".$member_id."' AND share_status IN('1','2')");
			$this->db->order_by('share_date DESC');
			$this->db->limit(1);
			$row_prev_share = $this->db->get()->result_array();
			$row_prev_share = @$row_prev_share[0];
			
			$arr_data['count_share'] = $row_prev_share['share_collect'];
			$arr_data['cal_share'] = $row_prev_share['share_collect_value'];
			
			$this->db->select('*');
			$this->db->from('coop_maco_account');
			$this->db->where("mem_id = '".$member_id."'");
			$rs_account = $this->db->get()->result_array();
			$count_account = 0;
			$cal_account = 0;
			foreach($rs_account as $key => $row_account){
				$this->db->select('*');
				$this->db->from('coop_account_transaction');
				$this->db->where("account_id = '".$row_account['account_id']."'");
				$this->db->order_by('transaction_id DESC');
				$this->db->limit(1);
				$row_account_trans = $this->db->get()->result_array();
				
				$cal_account += @$row_account_trans[0]['transaction_balance'];
				$count_account++;
				
				$rs_account[$key]['transaction_balance'] = @$row_account_trans[0]['transaction_balance'];
			}
			$arr_data['data_account'] = $rs_account;
			$arr_data['count_account'] = $count_account;
			$arr_data['cal_account'] = $cal_account;
			
			$this->db->select(array(
				't2.id',
				't2.petition_number',
				't2.member_id',
				't3.firstname_th',
				't3.lastname_th',
				't2.loan_amount',
				't2.loan_amount_balance'
			));
			$this->db->from('coop_loan_guarantee_person as t1');
			$this->db->join('coop_loan as t2','t1.loan_id = t2.id','inner');
			$this->db->join('coop_mem_apply as t3','t2.member_id = t3.member_id','inner');
			$this->db->where("t1.guarantee_person_id = '".$member_id."' AND t2.loan_status IN('1','2')");
			$rs_guarantee = $this->db->get()->result_array();
			
			$arr_data['count_contract'] = 0;
			$arr_data['sum_guarantee_balance'] = 0;
			foreach($rs_guarantee as $key => $row_count_guarantee){
				@$arr_data['sum_balance'] += $row_count_guarantee['loan_amount_balance'];
				$arr_data['count_contract']++;
			}
			
			$this->db->select(array(
				'*'
			));
			$this->db->from('coop_loan as t1');
			$this->db->where("t1.member_id = '".$member_id."' AND t1.loan_status IN('1','2')");
			$rs_count_loan = $this->db->get()->result_array();
			
			$arr_data['count_loan'] = 0;
			$arr_data['sum_loan_balance'] = 0;
			foreach($rs_count_loan as $key => $row_count_loan){
				$arr_data['sum_loan_balance'] += $row_count_loan['loan_amount_balance'];
				$arr_data['count_loan']++;
			}
			
			$this->db->select(array(
				't1.createdatetime',
				't1.contract_number',
				't3.loan_type as loan_type_detail',
				't1.loan_amount',
				't1.loan_amount_balance',
				't2.user_name',
				't1.loan_status',
				't1.id',
				't1.loan_type',
				't4.file_name as transfer_file'
			));
			$this->db->from('coop_loan as t1');
			$this->db->join('coop_loan_type as t3','t1.loan_type = t3.id','inner');
			$this->db->join('coop_user as t2','t1.admin_id = t2.user_id','left');
			$this->db->join('coop_loan_transfer as t4','t1.id = t4.loan_id','left');
			$this->db->where("t1.member_id = '".$member_id."'");
			$this->db->order_by("t1.id DESC");
			$rs_loan = $this->db->get()->result_array();
			$arr_data['rs_loan'] = $rs_loan;
		}
		
		$this->db->select(array(
			'*'
		));
		$this->db->from('coop_term_of_loan');
		$this->db->where("start_date <= '".date('Y-m-d')."'");
		$this->db->order_by("start_date ASC");
		$rs_rule = $this->db->get()->result_array();
		foreach($rs_rule as $key => $value){
			$arr_data['rs_rule'][$value['type_id']] = $value;
		}
		$this->db->select(array(
			'loan_reason_id','loan_reason'
		));
		$this->db->from('coop_loan_reason');
		$rs_loan_reason = $this->db->get()->result_array();
		$arr_data['rs_loan_reason'] = $rs_loan_reason;
		
		$this->db->select(array(
			'id','loan_type'
		));
		$this->db->from('coop_loan_type');
		$rs_loan_type = $this->db->get()->result_array();
		$arr_data['rs_loan_type'] = $rs_loan_type;
			
		$this->libraries->template('loan/index',$arr_data);
	}
	public function loan_cancel()
	{
		if ($_GET) {
			$data_insert = array();
			$data_insert['loan_status'] = $_GET['status_to'];
			$this->db->where('id', $_GET['loan_id']);
			$this->db->update('coop_loan', $data_insert);
			
			$this->center_function->toast('บันทึกข้อมูลเรียบร้อยแล้ว');
			echo "<script> document.location.href='".base_url(PROJECTPATH.'/loan/loan_cancel')."' </script>";
		}
		$arr_data = array();
		
		$x=0;
		$join_arr = array();
		$join_arr[$x]['table'] = 'coop_mem_apply';
		$join_arr[$x]['condition'] = 'coop_mem_apply.member_id = coop_loan.member_id';
		$join_arr[$x]['type'] = 'left';
		$x++;
		$join_arr[$x]['table'] = 'coop_user';
		$join_arr[$x]['condition'] = 'coop_loan.admin_id = coop_user.user_id';
		$join_arr[$x]['type'] = 'left';
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_loan');
		$this->paginater_all->where("loan_status IN('2','3')");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('cancel_date DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		$i = $row['page_start'];


		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		
		$loan_type = array();
		$this->db->select('*');
		$this->db->from("coop_loan_type");
		$rs_type = $this->db->get()->result_array();
		foreach($rs_type as $key => $row_type){
			$loan_type[$row_type['id']] = $row_type['loan_type'];
		}
		$arr_data['loan_type'] = $loan_type; 
		$this->libraries->template('loan/loan_cancel',$arr_data);
	}
	public function loan_approve()
	{
		if (@$_GET['status_to']) {
			$data_insert = array();
			$data_insert['loan_status'] = @$_GET['status_to'];
			$this->db->where('id', @$_GET['loan_id']);
			$this->db->update('coop_loan', $data_insert);
			
			$this->center_function->toast('บันทึกข้อมูลเรียบร้อยแล้ว');
			echo "<script> document.location.href='".base_url(PROJECTPATH.'/loan/loan_approve')."' </script>";
		}
		$arr_data = array();

		$x=0;
		$join_arr = array();
		$join_arr[$x]['table'] = 'coop_mem_apply';
		$join_arr[$x]['condition'] = 'coop_mem_apply.member_id = coop_loan.member_id';
		$join_arr[$x]['type'] = 'left';
		$x++;
		$join_arr[$x]['table'] = 'coop_user';
		$join_arr[$x]['condition'] = 'coop_loan.admin_id = coop_user.user_id';
		$join_arr[$x]['type'] = 'left';
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('coop_loan.*, coop_mem_apply.firstname_th, coop_mem_apply.lastname_th, coop_user.user_name');
		$this->paginater_all->main_table('coop_loan');
		$this->paginater_all->where("loan_status IN('0','1')");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('createdatetime DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		$i = $row['page_start'];


		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		
		$loan_type = array();
		$this->db->select('*');
		$this->db->from("coop_loan_type");
		$rs_type = $this->db->get()->result_array();
		foreach($rs_type as $key => $row_type){
			$loan_type[$row_type['id']] = $row_type['loan_type'];
		}
		$arr_data['loan_type'] = $loan_type; 
		
		$this->libraries->template('loan/loan_approve',$arr_data);
	}
	function loan_transfer(){
		$arr_data = array();
		if(@$_GET['action'] == 'delete_transfer'){
			$data_insert = array();
			$data_insert['transfer_status'] = '1';
			$data_insert['cancel_date'] = date('Y-m-d H:i:s');
			$this->db->where('id', $_GET['transfer_id']);
			$this->db->update('coop_loan_transfer', $data_insert);
			
			$this->center_function->toast("ยื่นขอยกเลิกรายการแล้ว");
			echo "<script>document.location.href = '".base_url(PROJECTPATH.'/loan/loan_transfer?loan_id='.$_GET['loan_id'])."';</script>";
		}
		
		$this->db->select(array(
			'*', 
			'coop_loan.id', 
			'coop_loan.createdatetime as loan_date', 
			'coop_loan_type.loan_type', 
			'coop_loan_transfer.id as transfer_id', 
			'coop_maco_account.account_name',
			'transfer_user.user_name'
		));
		$this->db->from('coop_loan');
		$this->db->join('coop_mem_apply','coop_loan.member_id = coop_mem_apply.member_id','inner');
		$this->db->join('coop_loan_type','coop_loan.loan_type = coop_loan_type.id','inner');
		$this->db->join("(SELECT * FROM coop_loan_transfer WHERE transfer_status != '2') as coop_loan_transfer","coop_loan.id = coop_loan_transfer.loan_id",'left');
		$this->db->join('coop_maco_account','coop_loan_transfer.account_id = coop_maco_account.account_id','left');
		$this->db->join('coop_user as transfer_user','transfer_user.user_id = coop_loan_transfer.admin_id','left');
		$this->db->where("coop_loan.id = '".@$_GET['loan_id']."'");
		$row = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		$row = @$row[0];
		$arr_data['row'] = $row;
		//echo"<pre>";print_r($arr_data['row']);echo"</pre>";exit;
		if(@$row['member_id']!=''){
			$this->db->select(array('*'));
			$this->db->from('coop_maco_account');
			$this->db->where("mem_id = '".$row['member_id']."'");
			$rs_account = $this->db->get()->result_array();
			$arr_data['rs_account'] = @$rs_account;
		}
		
		
		$this->libraries->template('loan/loan_transfer',$arr_data);
	}
	
	function loan_transfer_save(){
		$this->db->select(array(
			'loan_amount',
			'loan_type'
		));
		$this->db->from('coop_loan');
		$this->db->where("id = '".$_POST['loan_id']."'");
		$row_loan = $this->db->get()->result_array();
		$row_loan = $row_loan[0];
		
		$date_arr = explode('/',$_POST['date_transfer']);
		$date_transfer = ($date_arr[2]-543)."-".$date_arr[1]."-".$date_arr[0]." ".$_POST['time_transfer'];
		
		$this->db->select(array(
			'transaction_balance'
		));
		$this->db->from('coop_account_transaction');
		$this->db->where("account_id = '".$_POST['account_id']."'");
		$this->db->order_by('transaction_id DESC');
		$this->db->limit(1);
		$row_prev_trans = $this->db->get()->result_array();
		$row_prev_trans = $row_prev_trans[0];
		
		$transaction_balance = $row_prev_trans['transaction_balance'] + $row_loan['loan_amount'];
		
		$data_insert = array();
		$data_insert['transaction_time'] = $date_transfer;
		$data_insert['transaction_list'] = 'XD';
		$data_insert['transaction_withdrawal'] = '0';
		$data_insert['transaction_deposit'] = $row_loan['loan_amount'];
		$data_insert['transaction_balance'] = $transaction_balance;
		$data_insert['user_id'] = $_SESSION['USER_ID'];
		$data_insert['account_id'] = $_POST['account_id'];
		$this->db->insert('coop_account_transaction', $data_insert);
		
		$data_insert = array();
		$data_insert['loan_id'] = $_POST['loan_id'];
		$data_insert['account_id'] = $_POST['account_id'];
		$data_insert['date_transfer'] = $date_transfer;
		$data_insert['createdatetime'] = date('Y-m-d H:i:s');
		$data_insert['admin_id'] = $_SESSION['USER_ID'];
		$data_insert['transfer_status'] = '0';
		$this->db->insert('coop_loan_transfer', $data_insert);
		
		$last_id = $this->db->insert_id();
		
		$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/loan_transfer_attach/";
		
		if($_FILES['file_attach']['name']!=''){
			$new_file_name = $this->center_function->create_file_name($output_dir,$_FILES['file_attach']['name']);
			@copy($_FILES["file_attach"]["tmp_name"],$output_dir.$new_file_name);
			
			$data_insert = array();
			$data_insert['file_name'] = $new_file_name;
			$this->db->where('id', $last_id);
			$this->db->update('coop_loan_transfer', $data_insert);
		}
		
		$this->db->select(array(
			't1.account_chart_id',
			't2.account_chart'
		));
		$this->db->from('coop_account_match as t1');
		$this->db->join('coop_account_chart as t2','t1.account_chart_id = t2.account_chart_id','left');
		$this->db->where("
			t1.match_type = 'loan'
			AND t1.match_id = '".$row_loan['loan_type']."'
		");
		$row_account_match = $this->db->get()->result_array();
		$row_account_match = $row_account_match[0];
		
		$data = array();
		$data['coop_account']['account_description'] = "โอนเงินให้".$row_account_match['account_chart'];
		$data['coop_account']['account_datetime'] = $date_transfer;
		
		$i=0;
		$data['coop_account_detail'][$i]['account_type'] = 'debit';
		$data['coop_account_detail'][$i]['account_amount'] = $row_loan['loan_amount'];
		$data['coop_account_detail'][$i]['account_chart_id'] = $row_account_match['account_chart_id'];
		$i++;
		$data['coop_account_detail'][$i]['account_type'] = 'credit';
		$data['coop_account_detail'][$i]['account_amount'] = $row_loan['loan_amount'];
		$data['coop_account_detail'][$i]['account_chart_id'] = '10100';
		$this->account_transaction->account_process($data);
		
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		echo "<script> document.location.href='".base_url(PROJECTPATH.'/loan/loan_transfer?loan_id='.$_POST['loan_id'])."' </script>";
		exit;
	}
	
	function loan_transfer_cancel(){
		if ($_GET) {
			$data_insert = array();
			$data_insert['transfer_status'] = $_GET['status_to'];
			$this->db->where('id', $_GET['transfer_id']);
			$this->db->update('coop_loan_transfer', $data_insert);
			
			$this->center_function->toast('บันทึกข้อมูลเรียบร้อยแล้ว');
			echo "<script> document.location.href='".base_url(PROJECTPATH.'/loan/loan_transfer_cancel')."' </script>";
		}
		$arr_data = array();

		$x=0;
		$join_arr = array();
		$join_arr[$x]['table'] = 'coop_loan';
		$join_arr[$x]['condition'] = 'coop_loan_transfer.loan_id = coop_loan.id';
		$join_arr[$x]['type'] = 'left';
		$x++;
		$join_arr[$x]['table'] = 'coop_mem_apply';
		$join_arr[$x]['condition'] = 'coop_mem_apply.member_id = coop_loan.member_id';
		$join_arr[$x]['type'] = 'left';
		$x++;
		$join_arr[$x]['table'] = 'coop_user';
		$join_arr[$x]['condition'] = 'coop_loan.admin_id = coop_user.user_id';
		$join_arr[$x]['type'] = 'left';
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('coop_loan_transfer.id as transfer_id,
				 coop_loan_transfer.cancel_date, 
				 coop_loan.contract_number,
				 coop_loan.loan_amount,
				 coop_loan_transfer.date_transfer,
				 coop_loan_transfer.admin_id,
				 coop_loan_transfer.transfer_status,
				 coop_user.user_name,
				 coop_mem_apply.firstname_th,
				 coop_mem_apply.lastname_th');
		$this->paginater_all->main_table('coop_loan_transfer');
		$this->paginater_all->where("transfer_status IN('1','2')");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('cancel_date DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		$i = $row['page_start'];


		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		
		$loan_type = array();
		$this->db->select('*');
		$this->db->from("coop_loan_type");
		$rs_type = $this->db->get()->result_array();
		foreach($rs_type as $key => $row_type){
			$loan_type[$row_type['id']] = $row_type['loan_type'];
		}
		$arr_data['loan_type'] = $loan_type;	
		
		$this->libraries->template('loan/loan_transfer_cancel',$arr_data);
	}
	
	function ajax_check_term_of_loan_before(){
		$arr_return = array();
		$loan_type = $_POST['loan_type'];
		$share_total = str_replace(',','',$_POST['share_total']);
		$member_id = $_POST['member_id'];
		
		$this->db->select('*');
		$this->db->from("coop_mem_apply");
		$this->db->where("member_id = '".$member_id."'");
		$row_member = $this->db->get()->result_array();
		$row_member = $row_member[0];
			
			if($row_member['apply_date']!='0000-00-00'){
				$birthday = $row_member['apply_date'];
			}else{
				$birthday = date("Y-m-d");
			}
			$today = date("Y-m-d");   //จุดต้องเปลี่ยน
			list($byear, $bmonth, $bday)= explode("-",$birthday);       //จุดต้องเปลี่ยน
			list($tyear, $tmonth, $tday)= explode("-",$today);                //จุดต้องเปลี่ยน
			$mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
			$mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
			$mage = ($mnow - $mbirthday);
			//echo "วันเกิด $birthday"."<br>\n";
			//echo "วันที่ปัจจุบัน $today"."<br>\n";
			//echo "รับค่า $mage"."<br>\n";
			$u_y=date("Y", $mage)-1970;
			$u_m=date("m",$mage)-1;
			$u_d=date("d",$mage)-1;
			
			$month_count = ($u_y*12)+$u_m;
		
			//echo $month_count;
		
		$this->db->select('*');
		$this->db->from("coop_share_setting");
		$this->db->where("setting_id = '1'");
		$row_share_setting = $this->db->get()->result_array();
		$row_share_setting = $row_share_setting[0];
		
		$this->db->select('*');
		$this->db->from("coop_loan");
		$this->db->where("member_id = '".$member_id."' AND loan_type = '".$loan_type."' AND loan_status IN('1','2')");
		$rs_prev_loan = $this->db->get()->result_array();
		$prev_loan_amount = 0;
		$prev_loan_balance = 0;
		//echo $sql_prev_loan;
		foreach($rs_prev_loan as $key => $row_prev_loan){
			$prev_loan_amount += $row_prev_loan['loan_amount'];
			$prev_loan_balance += $row_prev_loan['loan_amount_balance'];
		}
		if($prev_loan_amount > 0){
			$prev_loan_percent = ($prev_loan_balance*100)/$prev_loan_amount;
		}else{
			$prev_loan_percent = 0;
		}
		//echo $prev_loan_percent;
		$share_value_total = $share_total * $row_share_setting['setting_value'];
		
		$this->db->select('*');
		$this->db->from("coop_term_of_loan");
		$this->db->where("type_id = '".$loan_type."' AND start_date <= '".date('Y-m-d')."'");
		$this->db->order_by('start_date DESC');
		$this->db->limit(1);
		$row_term_of_loan = $this->db->get()->result_array();
		$row_term_of_loan = $row_term_of_loan[0];
		//echo"<pre>";print_r($row_term_of_loan);exit;
		$arr_return['share_guarantee'] = $row_term_of_loan['share_guarantee'];
		$arr_return['person_guarantee'] = $row_term_of_loan['person_guarantee'];
		$text_return = '';
		 if($share_total <= $row_term_of_loan['min_share_total'] && $row_term_of_loan['min_share_total'] != ''){
			 $text_return .= "ท่านต้องมีหุ้นสะสมไม่น้อยกว่า ".number_format($row_term_of_loan['min_share_total'])." หุ้นเพื่อใช้ในการกู้\n";
		 }
		 if($month_count < $row_term_of_loan['min_month_member'] && $row_term_of_loan['min_month_member'] != ''){
			 $text_return .= "ท่านเป็นสมาชิกสหกรณ์ไม่ถึง ".$row_term_of_loan['min_month_member']." เดือน\n";
		 }
		 //if($share_value_total < $row_term_of_loan['min_share_fund_money'] && $row_term_of_loan['min_share_fund_money'] != ''){
			 //$text_return .= "ท่านต้องมีหุ้นสะสมและกองทุนสำรองเลี้ยงชีพรวมมากกว่า ".$row_term_of_loan['min_share_fund_money']." บาท\n";
		 //}
		 if($prev_loan_percent > $row_term_of_loan['min_installment_percent'] && $row_term_of_loan['min_installment_percent'] != ''){
			 $text_return .= "ท่านต้องผ่อนชำระเงินกู้เดิมไม่ต่ำกว่า ".$row_term_of_loan['min_installment_percent']." % จึงจะสามารถกู้ใหม่ได้\n";
		 }
		 
		 if($text_return!=''){
			 $arr_return['result'] = 'error';
			 $arr_return['text_return'] = $text_return;
		 }else{
			 $arr_return['result'] = 'success';
		 }
		 
		echo json_encode($arr_return);	 
		
		exit;
	}
	function ajax_calculate_loan(){
		$arr_data = array();
		$this->load->view('loan/ajax_calculate_loan',$arr_data);
	}
	
	function ajax_check_term_of_loan(){
		$arr_return = array();

		$member_id = @$_POST['member_id'];
		$loan_type = @$_POST['loan_type'];
		$loan_amount = str_replace(',','',@$_POST['loan_amount']);
		$share_total = str_replace(',','',@$_POST['share_total']);
		$share_amount = str_replace(',','',@$_POST['share_amount']);
		$period_amount = @$_POST['period_amount'];
		$person_guarantee = @$_POST['person_guarantee'];
		$share_guarantee = @$_POST['share_guarantee'];
		$fund_total = str_replace(',','',@$_POST['fund_total']);
		
		$this->db->select('*');
		$this->db->from("coop_mem_apply");
		$this->db->where("member_id = '".$member_id."'");
		$rs_member = $this->db->get()->result_array();
		$row_member = @$rs_member[0];
		
		if(@$_POST['salary']!=''){
			$salary = str_replace(',','',@$_POST['salary']);
		}else{
			$salary = @$row_member['salary'];
		}
		
		if(@$row_member['apply_date']!='0000-00-00'){
			$birthday = @$row_member['apply_date'];
		}else{
			$birthday = date("Y-m-d");
		}
		$today = date("Y-m-d");   //จุดต้องเปลี่ยน
		list($byear, $bmonth, $bday)= explode("-",$birthday);       //จุดต้องเปลี่ยน
		list($tyear, $tmonth, $tday)= explode("-",$today);                //จุดต้องเปลี่ยน
		$mbirthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
		$mnow = mktime(0, 0, 0, $tmonth, $tday, $tyear );
		$mage = ($mnow - $mbirthday);
		//echo "วันเกิด $birthday"."<br>\n";
		//echo "วันที่ปัจจุบัน $today"."<br>\n";
		//echo "รับค่า $mage"."<br>\n";
		$u_y=date("Y", $mage)-1970;
		$u_m=date("m",$mage)-1;
		$u_d=date("d",$mage)-1;
		
		$month_count = ($u_y*12)+$u_m;
		
		$this->db->select('*');
		$this->db->from("coop_term_of_loan");
		$this->db->where("type_id = '".$loan_type."' AND start_date <= '".date('Y-m-d')."'");
		$this->db->order_by('start_date DESC');
		$this->db->limit(1);
		$rs_term_of_loan = $this->db->get()->result_array();
		$row_term_of_loan = @$rs_term_of_loan[0];
		$multiple_money_limit = $salary*@$row_term_of_loan['less_than_multiple_salary'];
		
		$this->db->select('*');
		$this->db->from("coop_share_setting");
		$this->db->where("setting_id = '1'");
		$rs_share_setting = $this->db->get()->result_array();
		$row_share_setting = @$rs_share_setting[0];
		$share_value_total = $share_total * @$row_share_setting['setting_value'];
		//print_r($this->db->last_query());exit;
		$credit_limit_arr = array();
		if(@$row_term_of_loan['credit_limit_share_percent']!='' && @$row_term_of_loan['credit_limit_share_percent'] > 0){
			$percent_share_value_total = ($share_value_total*@$row_term_of_loan['credit_limit_share_percent'])/100;
			$percent_fund_value_total = ($fund_total*@$row_term_of_loan['credit_limit_share_percent'])/100;
			$credit_limit_arr[] = $percent_share_value_total+$percent_fund_value_total;
		}
		if($multiple_money_limit > 0 && @$row_term_of_loan['less_than_multiple_salary']!=''){
			$credit_limit_arr[] = $multiple_money_limit;
		}
		if(@$row_term_of_loan['credit_limit'] > 0 && @$row_term_of_loan['credit_limit'] != ''){
			$credit_limit_arr[] = @$row_term_of_loan['credit_limit'];
		}
		if(@$row_term_of_loan['percent_share_guarantee']!='' && @$row_term_of_loan['percent_share_guarantee'] > 0){
			$share_use_limit = ($share_value_total * @$row_term_of_loan['percent_share_guarantee'])/100;
		}
		if(@$row_term_of_loan['percent_fund_quarantee']!='' && @$row_term_of_loan['percent_fund_quarantee'] > 0){
			$fund_use_limit = ($fund_total * @$row_term_of_loan['percent_fund_quarantee'])/100;
		}
		if($share_guarantee == '1' && (@$share_use_limit+@$fund_use_limit) > 0){
			$credit_limit_arr[] = $share_use_limit+$fund_use_limit;
		}
		$credit_limit = min($credit_limit_arr);
		
		if($person_guarantee=='1'){
			$least_share_for_loan = ($loan_amount * @$row_term_of_loan['least_share_percent_for_loan'])/100;
		}
		
		$this->db->select(array('profile_id','retire_age','retire_month'));
		$this->db->from("coop_profile");
		$row_retire_age = $this->db->get()->result_array();
		$row_retire_age = @$row_retire_age[0];
		
		$retire_date = date('Y-m-t',strtotime('+'.$row_retire_age['retire_age'].' year',strtotime($row_member['birthday'])));
		$retire_date_arr = explode('-',$retire_date);
		$retire_date = $retire_date_arr[0].'-'.sprintf('%02d',$row_retire_age['retire_month']).'-'.$retire_date_arr[2];
		
		$text_return = '';
		 if($month_count < @$row_term_of_loan['min_month_member'] && @$row_term_of_loan['min_month_member'] != ''){
			 $text_return .= "ท่านเป็นสมาชิกสหกรณ์ไม่ถึง ".@$row_term_of_loan['min_month_member']." เดือน\n";
		 }
		 if($loan_amount > $credit_limit){
			 $text_return .= "ท่านมีวงเงินที่สามารถกู้ได้เพียง ".number_format($credit_limit)." บาท\n";
		 }
		 if($period_amount > @$row_term_of_loan['max_period'] && @$row_term_of_loan['max_period'] != ''){
			 $text_return .= "ท่านสามารถผ่อนชำระได้สูงสุด ".$row_term_of_loan['max_period']." งวด\n";
		 }
		 if($person_guarantee=='1' && $least_share_for_loan > $share_value_total){
			 $text_return .= "ท่านต้องมีมูลค่าหุ้นสะสมไม่น้อยกว่า ".@$row_term_of_loan['least_share_percent_for_loan']." % ของวงเงินกู้ กรณีใช้บุคคลค้ำประกัน\n";
		 }
		 if(($share_value_total+$fund_total) < @$row_term_of_loan['min_share_fund_money'] && @$row_term_of_loan['min_share_fund_money'] != ''){
			 $text_return .= "ท่านต้องมีหุ้นสะสมและกองทุนสำรองเลี้ยงชีพรวมมากกว่า ".@$row_term_of_loan['min_share_fund_money']." บาท\n";
		 }
		 if($_POST['last_date_period'] > $retire_date){
			 $text_return .= "ท่านไม่สามารถผ่อนชำระจำนวน ".$_POST['period_amount']." งวดได้เนื่องจากเกินกำหนดเกษียณ\n";
		 }
		 
		 if($text_return!=''){
			 echo $text_return;
		 }else{
			 echo "success";
		 }	 
		
		exit;
	}
	
	function coop_loan_save(){	
		//echo '<pre>'; print_r($_POST); echo '</pre>';exit;
		if(@$_POST['loan_id']==''){
			$new_contact_number = '';
			$this->db->select('*');
			$this->db->from("coop_term_of_loan");
			$this->db->where("type_id = '".@$_POST['data']['coop_loan']['loan_type']."' AND start_date <= '".date('Y-m-d')."'");
			$this->db->order_by('start_date DESC');
			$this->db->limit(1);
			$rs_term_of_loan = $this->db->get()->result_array();
			$row_term_of_loan = @$rs_term_of_loan[0];
			$new_contact_number .= $row_term_of_loan['prefix_code'];
			
			$this->db->select('contract_number');
			$this->db->from("coop_loan");
			$this->db->where("loan_type = '".@$_POST['data']['coop_loan']['loan_type']."' AND contract_number LIKE '%".(date('Y')+543)."'");
			$this->db->order_by("id DESC");
			$this->db->limit(1);
			$rs_contact_number = $this->db->get()->result_array();
			$row_contact_number = @$rs_contact_number[0];
			if(@$row_contact_number['contract_number']==''){
				$contact_number_now = '001';
			}else{
				$contact_number_now = str_replace(@$row_term_of_loan['prefix_code'],'',@$row_contact_number['contract_number']);
				$contact_number_now = str_replace('/'.(date('Y')+543),'',@$contact_number_now);
				$contact_number_now ++;
			}
			$new_contact_number .= sprintf("% 03d",$contact_number_now);
			$new_contact_number = $new_contact_number."/".(date('Y')+543);

			$data_insert = array();
			$data_insert['admin_id'] = @$_SESSION['USER_ID'];
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');
			$data_insert['contract_number'] = @$new_contact_number;
		
			foreach(@$_POST['data']['coop_loan'] as $key => $value){
				if($key == 'date_period_1' || $key == 'date_period_2'){
					if(!empty($value)){
						$date_arr = explode('/',$value);
						$value = ($date_arr[2]-543)."-".$date_arr[1]."-".$date_arr[0];
					}
				}
				if($key == 'loan_amount' || $key == 'money_period_1' || $key == 'money_period_2' || $key == 'salary'){
					$value = str_replace(',','',@$value);						
				}
				if($key == 'petition_number'){
					$this->db->select('petition_number');
					$this->db->from("coop_loan");
					$this->db->order_by("id DESC");
					$this->db->limit(1);
					$rs_petition_number = $this->db->get()->result_array();
					$row_petition_number = @$rs_petition_number[0];
					$petition_number = (int)@$row_petition_number['petition_number']+1;
					$value = sprintf('% 06d',@$petition_number);
				}
				$data_insert[$key] = @$value;
				$data_insert['loan_status'] = '0';
				//echo '<pre>'; print_r($value); echo '<pre>';
			}
			//echo '<pre>'; print_r($data_insert); echo '<pre>';
			//exit;
			//add				
			$this->db->insert('coop_loan', $data_insert);
			
			$loan_id = $this->db->insert_id();
			
			foreach(@$_POST['data']['coop_loan_guarantee'] as $key => $value){
				if(@$value['guarantee_type']!=''){
					$data_insert = array();
					$data_insert['loan_id'] = @$loan_id;
					$data_insert['guarantee_type'] = @$value['guarantee_type'];
					if($value['guarantee_type']=='1'){						
						foreach($value['coop_loan_guarantee_person']['id'] as $key2 => $value2){
							$data_insert_person = array();	
							$data_insert_person['loan_id'] = @$loan_id;							
							$data_insert_person['guarantee_person_id'] = @$value2;							
							$data_insert_person['guarantee_person_contract_number'] = @$value['coop_loan_guarantee_person']['guarantee_person_contract_number'][@$key2];							
							$data_insert_person['guarantee_person_amount'] = str_replace(',','',@$value['coop_loan_guarantee_person']['guarantee_person_amount'][@$key2]);							
							$data_insert_person['guarantee_person_amount_balance'] = str_replace(',','',@$value['coop_loan_guarantee_person']['guarantee_person_amount'][@$key2]);							
							$this->db->insert('coop_loan_guarantee_person', $data_insert_person);
						//echo $sql_person."<br>";
						}
						//echo "_________________________<br>";
					}else{
						if(isset($value['amount'])){
							$data_insert['amount'] = str_replace(',','',@$value['amount']);
						}
						if(isset($value['price'])){
							$data_insert['price'] = str_replace(',','',@$value['price']);
						}
						if(isset($value['other_price'])){
							$data_insert['other_price'] = str_replace(',','',@$value['other_price']);
						}
					}
					//add coop_loan_guarantee
					$this->db->insert('coop_loan_guarantee', $data_insert);
				}
				//echo $sql."<br>";
			}
			//echo "_________________________<br>";
			foreach(@$_POST['data']['coop_loan_period'] as $key => $value){
				$data_insert = array();
				$data_insert['loan_id'] = @$loan_id;					
				foreach($value as $key2 => $value2){
					//$sql .= " ".$key2." = '".$value2."',";
					$data_insert[$key2] = @$value2;
				}
				//add coop_loan_period
				$this->db->insert('coop_loan_period', $data_insert);
			}
			
		}else{
			$data_insert = array();
			$data_insert['admin_id'] = @$_SESSION['USER_ID'];
			$data_insert['createdatetime'] = date('Y-m-d');
			foreach(@$_POST['data']['coop_loan'] as $key => $value){
				if($key == 'date_period_1' || $key == 'date_period_2'){					
					if(!empty($value)){
						$date_arr = explode('/',$value);
						$value = ($date_arr[2]-543)."-".$date_arr[1]."-".$date_arr[0];
					}
				}
				if($key == 'loan_amount' || $key == 'money_period_1' || $key == 'money_period_2' || $key == 'salary'){
					$value = str_replace(',','',@$value);						
				}
				$data_insert[$key] = @$value;
			}

			//edit coop_loan
			$this->db->where('id', @$_POST['loan_id']);		
			$this->db->update('coop_loan', $data_insert);
			$loan_id = @$_POST['loan_id'];

			$this->db->where("loan_id", $loan_id );
			$this->db->delete("coop_loan_guarantee");	
			
			$this->db->where("loan_id", $loan_id );
			$this->db->delete("coop_loan_guarantee_person");	
			
			foreach(@$_POST['data']['coop_loan_guarantee'] as $key => $value){
				if(@$value['guarantee_type']!=''){
					$data_insert = array();
					$data_insert['loan_id'] = @$loan_id;
					$data_insert['guarantee_type'] = @$value['guarantee_type'];

					if(@$value['guarantee_type']=='1'){
						foreach(@$value['coop_loan_guarantee_person']['id'] as $key2 => $value2){
							$data_insert_person = array();
							$data_insert_person['loan_id'] = @$loan_id;
							$data_insert_person['guarantee_person_id'] = @$value2;
							$data_insert_person['guarantee_person_contract_number'] = @$value['coop_loan_guarantee_person']['guarantee_person_contract_number'][$key2];
							$data_insert_person['guarantee_person_amount'] = str_replace(',','',@$value['coop_loan_guarantee_person']['guarantee_person_amount'][$key2]);
							
							//add coop_loan_guarantee_person
							$this->db->insert('coop_loan_guarantee_person', $data_insert_person);

						}
						//echo "_________________________<br>";
					}else{
						if(isset($value['amount'])){
							$data_insert['amount'] = str_replace(',','',@$value['amount']);
						}
						if(isset($value['price'])){
							$data_insert['price'] = str_replace(',','',@$value['price']);
						}
						if(isset($value['other_price'])){
							$data_insert['other_price'] = str_replace(',','',@$value['other_price']);
						}
					}
					//add coop_loan_guarantee
					$this->db->insert('coop_loan_guarantee', $data_insert);
				}
			}

			$this->db->where("loan_id", $loan_id );
			$this->db->delete("coop_loan_period");
			foreach(@$_POST['data']['coop_loan_period'] as $key => $value){
				$data_insert = array();
				$data_insert['loan_id'] = @$loan_id;
				foreach($value as $key2 => $value2){
					$data_insert[$key2] = @$value2;
				}
				//add coop_loan_period
				$this->db->insert('coop_loan_period', $data_insert);
			}
			
		}
		
		$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/loan_attach/";
		//echo $output_dir;
		if(!@mkdir($output_dir,0,true)){
		   chmod($output_dir, 0777);
		}else{
		   chmod($output_dir, 0777);
		}
		if($_FILES['file_attach']['name'][0]!=''){
			foreach($_FILES['file_attach']['name'] as $key_file => $value_file ){
				$fileName=array();
				$list_dir = array(); 
					$cdir = scandir($output_dir); 
					foreach ($cdir as $key => $value) { 
					   if (!in_array($value,array(".",".."))) { 
						  if (@is_dir(@$dir . DIRECTORY_SEPARATOR . @$value)){ 
							$list_dir[$value] = dirToArray(@$dir . DIRECTORY_SEPARATOR . $value); 
						  }else{
							if(substr($value,0,8) == date('Ymd')){
							$list_dir[] = $value;
							}
						  } 
					   } 
					}
					$explode_arr=array();
					foreach($list_dir as $key => $value){
						$task = explode('.',$value);
						$task2 = explode('_',$task[0]);
						$explode_arr[] = $task2[1];
					}
					$max_run_num = sprintf("%04d",count($explode_arr)+1);
					$explode_old_file = explode('.',$_FILES["file_attach"]["name"][$key_file]);
					$new_file_name = date('Ymd')."_".$max_run_num.".".$explode_old_file[(count($explode_old_file)-1)];
				if(!is_array($_FILES["file_attach"]["name"][$key_file]))
				{
						$fileName['file_name'] = $new_file_name;
						$fileName['file_type'] = $_FILES["file_attach"]["type"][$key_file];
						$fileName['file_old_name'] = $_FILES["file_attach"]["name"][$key_file];
						$fileName['file_path'] = $output_dir.$fileName['file_name'];
						move_uploaded_file($_FILES["file_attach"]["tmp_name"][$key_file],$output_dir.$fileName['file_name']);
						
						$data_insert = array();
						$data_insert['loan_id'] = @$loan_id;
						$data_insert['file_name'] = @$fileName['file_name'];
						$data_insert['file_type'] = @$fileName['file_type'];
						$data_insert['file_old_name'] = @$fileName['file_old_name'];
						$data_insert['file_path'] = @$fileName['file_path'];
						//add coop_loan_file_attach
						$this->db->insert('coop_loan_file_attach', $data_insert);
				}
			}
		}
		
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		echo "<script> document.location.href='".base_url(PROJECTPATH.'/loan?member_id='.@$_GET['member'])."' </script>";
		exit;
	}
	
	function coop_loan_delete(){	
		$loan_id = @$_GET['loan_id'];
		
		$data_insert = array();
		$data_insert['loan_status'] = @$_GET['status_to'];
		$data_insert['cancel_date'] =date('Y-m-d');
		
		$this->db->where('id', $loan_id);		
		$this->db->update('coop_loan', $data_insert);

		$this->center_function->toast("ลบข้อมูลเรียบร้อยแล้ว");
		echo true;
		
	}
	
	function ajax_coop_loan_period_table(){
		$html = '';
		$html .= '
				<div id="period_table_space">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th class="text-center" style="width: 8%;">งวดที่</th>
								<th class="text-right"  style="width: 12%;">เงินต้นคงเหลือ</th>
								<th class="text-right"  style="width: 15%;">วันที่หัก</th>
								<th class="text-right"  style="width: 14%;">จำนวนวัน</th>
								<th class="text-right"  style="width: 9%;">ดอกเบี้ย</th>
								<th class="text-right"  style="width: 14%;">เงินต้นชำระ</th>
								<th class="text-right"  style="width: 15%;">รวมชำระต่อเดือน</th>
							</tr>
						</thead>
						<tbody>
			';	
				$this->db->select('*');
				$this->db->from("coop_loan_period");
				$this->db->where("loan_id = '".@$_POST['loan_id']."'");
				$this->db->order_by("period_count ASC");
				$rs = $this->db->get()->result_array();
				//print_r($this->db->last_query());
				$total_loan_int = 0;
				$total_loan_pri = 0;
				$total_loan_pay = 0;
				if(!empty($rs)){
					foreach(@$rs AS $key=>$row){
						$html .= '	
							<tr>
								<td class="text-center">'.@$row['period_count'].'</td>
								<td class="text-right">'.number_format(@$row['outstanding_balance'] , 2).'</td>
								<td class="text-right">'.$this->center_function->mydate2date(@$row['date_period']).'</td>
								<th class="text-right">'.@$row['date_count'].'</th>
								<td class="text-right">'.number_format(@$row['interest'], 2).'</td>
								<td class="text-right">'.number_format(@$row['principal_payment'], 2).'</td>
								<td class="text-right">'.number_format(@$row['total_paid_per_month'], 2).'</td>
							</tr>
							';
				$total_loan_int += @$row['interest'];
				$total_loan_pri += @$row['principal_payment'];
				$total_loan_pay += @$row['total_paid_per_month'];
					if(date('m',strtotime(@$row['date_period']))=='12'){
				$html .= '	
						<tr style="font-weight: bold;">
							<td class="text-center"></td>
							<td class="text-center"></td>
							<td class="text-right"></td>
							<td class="text-right"> รวมปี </td>
							<td class="text-right">'.number_format(@$total_loan_int, 2).'</td>
							<td class="text-right">'.number_format(@$total_loan_pri, 2).'</td>
							<td class="text-right">'.number_format(@$total_loan_pay, 2).'</td>
						</tr>
						';
					}
				} 
				}
				$html .= '
					<tr style="font-weight: bold;">
						<td class="text-center"></td>
						<td class="text-center"></td>
						<td class="text-right"></td>
						<td class="text-right"> รวม </td>
						<td class="text-right">'.number_format(@$total_loan_int, 2).'</td>
						<td class="text-right">'.number_format(@$total_loan_pri, 2).'</td>
						<td class="text-right">'.number_format(@$total_loan_pay, 2).'</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="text-center p-v-xxl hidden-print">
			<button type="button" class="btn btn-primary btn-calculate" onclick="printElem(\'period_table_space\');">พิมพ์</button>
		</div>
		';
		echo $html;
		exit;
	}	
	
	function ajax_get_loan_data(){
		$member_id = isset($_POST['member_id']) ? trim(@$_POST['member_id']) : "";
		$member_id = sprintf("%06d",$member_id);
		$where = ' 1=1 ';
		if(@$_POST['loan_id']!=''){
			$where .= " AND coop_loan.id = '".@$_POST['loan_id']."' ";
		}
		if(@$_POST['contract_number']!=''){
			$where .= " AND contract_number = '".@$_POST['contract_number']."' ";
		}
		//echo '<pre>'; print_r($_POST); echo '/<pre>'; exit;
		$this->db->select(array('*', 
					'coop_loan.id', 
					'coop_loan.createdatetime',
					'coop_loan_type.loan_type', 
					'coop_loan_transfer.id as transfer_id',
					'coop_loan_transfer.account_id',
					'coop_loan_transfer.file_name',
					'coop_maco_account.account_name',
					'transfer_user.user_name'));
		$this->db->from("coop_loan");
		$this->db->join("coop_loan_type", "coop_loan.loan_type = coop_loan_type.id", "inner");
		$this->db->join("coop_loan_transfer", "coop_loan.id = coop_loan_transfer.loan_id AND transfer_status <> '2'", "left");
		$this->db->join("coop_maco_account", "coop_loan_transfer.account_id = coop_maco_account.account_id", "left");
		$this->db->join("coop_user as transfer_user", "transfer_user.user_id = coop_loan_transfer.admin_id", "left");
		$this->db->where($where);
		$rs1 = $this->db->get()->result_array();		
		$row1 = @$rs1[0];	
		if(@$row1['id']!=''){
		foreach(@$row1 as $key => $value){
			if($key == 'date_period_1' || $key == 'date_period_2' || $key == 'createdatetime' || $key == 'date_transfer'){
			@$value = date('d/m/Y H:i น.',strtotime('+543 year',strtotime(@$value)));
			}
			if($key == 'loan_amount' || $key == 'salary'){
				@$value = number_format(@$value);
			}
			@$data['coop_loan'][$key] = @$value;
		}
		
		$loan_id = @$row1['id'];
		//echo '<pre>'; print_r($loan_id); echo '/<pre>'; exit;
		$this->db->select(array('*'));
		$this->db->from("coop_loan_guarantee");
		$this->db->where("loan_id = '".$loan_id."'");
		$rs2 = $this->db->get()->result_array();
		
		$i=0;
		if(!empty($rs2)){
			foreach(@$rs2 as $key => $row2){
				foreach(@$row2 as $key => $value){
					if($key == 'amount' || $key == 'price' || $key == 'other_price'){
						@$value = number_format(@$value);
					}
					@$data['coop_loan_guarantee'][$i][$key] = @$value;
				}
				$i++;
			}
		}
		
		$this->db->select(array('*','coop_mem_group.mem_group_name'));
		$this->db->from("coop_loan_guarantee_person");
		$this->db->join("coop_mem_apply", "coop_loan_guarantee_person.guarantee_person_id = coop_mem_apply.member_id", "inner");
		$this->db->join("coop_mem_group", "coop_mem_apply.level = coop_mem_group.id", "left");
		$this->db->where("loan_id = '".$loan_id."'");
		$rs3 = $this->db->get()->result_array();
		$a = 0;
		if(!empty($rs3)){
			foreach(@$rs3 as $key => $row3){
				@$data['coop_loan_guarantee_person'][$a] = @$row3;
				$this->db->select(array('*'));
				$this->db->from("coop_loan_guarantee_person as t1");
				$this->db->join("coop_loan as t2", "t1.loan_id = t2.id", "inner");
				$this->db->where("t1.guarantee_person_id = '".$row3['member_id']."' AND t2.loan_status = '1'");
				$rs_count_guarantee = $this->db->get()->result_array();
				$count_guarantee=0;
				if(!empty($rs_count_guarantee)){
					foreach(@$rs_count_guarantee as $key => $row_count_guarantee){
						$count_guarantee++;
					}
				}
				@$data['coop_loan_guarantee_person'][$a]['count_guarantee'] = @$count_guarantee;
				$a++;
			}
		}
		if(!empty($data['coop_loan_guarantee_person'])){
			foreach(@$data['coop_loan_guarantee_person'] as $key => $value){
					@$data['coop_loan_guarantee_person'][$key]['guarantee_person_amount'] = number_format(@$data['coop_loan_guarantee_person'][$key]['guarantee_person_amount']);
			}
		}
		
		$this->db->select(array('*'));
		$this->db->from("coop_loan_period");
		$this->db->where("loan_id = '".$loan_id."'");
		$rs4 = $this->db->get()->result_array();
		
		if(!empty($rs4)){
			foreach(@$rs4 as $key => $row4){
				@$data['coop_loan_period'][] = @$row4;
			}
		}
		
		$this->db->select(array('*'));
		$this->db->from("coop_loan_file_attach");
		$this->db->where("loan_id = '".$loan_id."'");
		$rs5 = $this->db->get()->result_array();
		if(!empty($rs5)){
			foreach(@$rs5 as $key => $row5){
				@$data['coop_loan_file_attach'][] = @$row5;
			}
		}
		
		$this->db->select(array('*'));
		$this->db->from("coop_mem_apply");
		$this->db->where("member_id = '".$row1['member_id']."'");
		$rs6 = $this->db->get()->result_array();
		$row6 = @$rs6[0];
		@$data['coop_mem_apply'] = @$row6;
		//print_r($this->db->last_query());exit;
		//echo"<pre>";print_r($data);echo"</pre>";
		echo json_encode($data);
		}else{
			echo 'not_found';
		}
		exit;
	}	

	function ajax_delete_loan_file_attach(){
		$this->db->select(array('*'));
		$this->db->from("coop_loan_file_attach");
		$this->db->where("id = '".@$_POST['id']."'");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0];

		//$attach_path = "../uploads/loan_attach/";
		$attach_path = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/loan_attach/";
		$file = @$attach_path.@$row['file_name'];
		unlink($file);

		$this->db->where("id", @$_POST['id'] );
		$this->db->delete("coop_loan_file_attach");	
		if(@$rs){
			echo "success";
		}else{
			echo "error";
		}
		exit;
	}	
	
}
