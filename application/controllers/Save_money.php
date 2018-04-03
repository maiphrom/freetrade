<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Save_money extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();

		$x=0;
		$join_arr = array();
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_maco_account');
		$this->paginater_all->where("");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('account_id DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		$i = $row['page_start'];


		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		
		$this->libraries->template('save_money/index',$arr_data);
	}
	
	function add_save_money(){
		$arr_data = array();
		$data = $this->input->post();
		if($data['account_id']!=''){
			$account_id = @$data['account_id'] ;
			$arr_data['account_id'] = $account_id;
			
			$this->db->select('*');
			$this->db->from('coop_maco_account');
			$this->db->where("account_id = '".$account_id."'");
			$row = $this->db->get()->result_array();
			$arr_data['auto_account_id'] = '';
			$btitle = "แก้ไขบัญชีเงินฝาก";
			$arr_data['row'] = $row[0];
		}else{
			$this->db->select('account_id');
			$this->db->from('coop_maco_account');
			$this->db->order_by("account_id DESC");
			$this->db->limit(1);
			$row = $this->db->get()->result_array();
			if(!empty($row)){
				$auto_account_id = $row[0]['account_id'] + 1;
			}else{
				$auto_account_id = 1;
			}
			$arr_data['auto_account_id'] = $auto_account_id;
			$btitle = "เพิ่มบัญชีเงินฝาก";
			$arr_data['row'] = array();
			$arr_data['account_id'] = '';
		}
			$this->db->select(array('t1.type_id','t1.type_name'));
			$this->db->from('coop_deposit_type_setting as t1');
			$row = $this->db->get()->result_array();
			$arr_data['type_id'] = $row;
		
		$arr_data['btitle'] = $btitle;
		$this->load->view('save_money/add_save_money',$arr_data);
	}
	
	function save_add_save_money(){
		//echo"<pre>";print_r($this->input->post());echo"</pre>";exit;
		$data = $this->input->post();
		if($data['action_type']=='add'){
			$data_insert = array();
			$data_insert['account_id'] = $data['acc_id'];
			$data_insert['mem_id'] = $data['mem_id'];
			$data_insert['member_name'] = $data['member_name'];
			$data_insert['account_name'] = $data['acc_name'];
			$data_insert['created'] = date('Y-m-d H:i:s');
			$data_insert['account_amount'] = '0';
			$data_insert['book_number'] = '1';
			$data_insert['type_id'] = $data['type_id'];
			$this->db->insert('coop_maco_account', $data_insert);
		}else{
			$data_insert = array();
			$data_insert['account_name'] = $data['acc_name'];
			$data_insert['type_id'] = $data['type_id'];
			$this->db->where('account_id', $data['acc_id']);
			$this->db->update('coop_maco_account', $data_insert);
		}
		$this->center_function->toast('บันทึกข้อมูลเรียบร้อยแล้ว');
		echo "<script> document.location.href = '".PROJECTPATH."/save_money' </script>";
		exit;
	}
	
	function check_account_delete(){
		$data = $this->input->post();
		$this->db->select('*');
		$this->db->from('coop_maco_account');
		$this->db->where("account_id = '".$data['account_id']."'");
		$row = $this->db->get()->result_array();
		
		if($row[0]['account_amount'] > 0 ){
			echo 'error';
		}else{
			echo'success';
		}
		exit;
	}
	
	function delete_account($account_id){
		$this->db->where('account_id', $account_id);
		$this->db->delete('coop_maco_account');
		$this->center_function->toast('ลบข้อมูลเรียบร้อยแล้ว');
		echo "<script> document.location.href = '".PROJECTPATH."/save_money' </script>";
	}
	
	public function account_detail()
	{
		$arr_data = array();
		
		$account_id = $this->input->get('account_id');
		$arr_data['account_id'] = $account_id;
		
		$this->db->select(array('t1.*','t3.type_name'));
		$this->db->from('coop_maco_account as t1');
		$this->db->join('coop_deposit_type_setting as t3','t1.type_id = t3.type_id','left');
		$this->db->where("account_id = '".$account_id."'");
		$row = $this->db->get()->result_array();
		$arr_data['row_memberall'] = @$row[0];
		
		$this->db->select('*');
		$this->db->from('coop_mem_apply');
		$this->db->where("member_id = '".$arr_data['row_memberall']['mem_id']."'");
		$row = $this->db->get()->result_array();
		$arr_data['row_member'] = @$row[0];
		
		$x=0;
		$join_arr = array();
		$join_arr[$x]['table'] = 'coop_user';
		$join_arr[$x]['condition'] = 'coop_account_transaction.user_id = coop_user.user_id';
		$join_arr[$x]['type'] = 'left';
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('coop_account_transaction.*, coop_user.user_name');
		$this->paginater_all->main_table('coop_account_transaction');
		$this->paginater_all->where("account_id = '".$account_id."'");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('transaction_id DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo $this->db->last_query();exit;
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit'], $_GET);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		
		$i = $row['page_start'];

		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		
		$this->db->select('*');
		$this->db->from('coop_account_transaction');
		$this->db->where("account_id = '".$account_id."'");
		$row = $this->db->get()->result_array();
		$num_arr = array();
		$i = 1;
		foreach($row as $key => $value){
			$num_arr[$value['transaction_id']] = $i++;
		}
		
		$arr_data['num_arr'] = $num_arr;
		
		$this->db->select('min_first_deposit');
		$this->db->from('coop_deposit_setting');
		$this->db->order_by('deposit_setting_id DESC');
		$row = $this->db->get()->result_array();
		
		$arr_data['min_first_deposit'] = $row[0]['min_first_deposit'];
		
		$this->db->select('money_type_name_short');
		$this->db->from('coop_money_type');
		$this->db->where("id='1'");
		$row = $this->db->get()->result_array();
		$arr_data['row_deposit'] = $row[0];
		
		$this->db->select('money_type_name_short');
		$this->db->from('coop_money_type');
		$this->db->where("id='2'");
		$row = $this->db->get()->result_array();
		$arr_data['row_with'] = $row[0];
		
		$this->libraries->template('save_money/account_detail',$arr_data);
	}
	
	public function save_transaction(){
		//echo"<pre>";print_r($this->input->post());echo"</pre>";exit;
		$data = $this->input->post();
		$this->db->select('*');
		$this->db->from('coop_account_transaction');
		$this->db->where("account_id = '".$data['account_id']."'");
		$this->db->order_by('transaction_time DESC');
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		if(!empty($row)){
			$balance = $row[0]['transaction_balance'];
		}else{
			$balance = 0;
		}
		
		if($data["do"] == "deposit") {
			$sum = $balance + $data['money'];
			
			$data_insert = array();
			$data_insert['transaction_time'] = date('Y-m-d H:i:s');
			$data_insert['transaction_list'] = $data['transaction_list'];
			$data_insert['transaction_withdrawal'] = '';
			$data_insert['transaction_deposit'] = $data['money'];
			$data_insert['transaction_balance'] = $sum;
			$data_insert['user_id'] = $_SESSION['USER_ID'];
			$data_insert['account_id'] = $data['account_id'];
			
			if ($this->db->insert('coop_account_transaction', $data_insert)) {
				$this->center_function->toast("ทำการฝากเงินเรียบร้อยแล้ว");
				
				$data_acc['coop_account']['account_description'] = "สมาชิกฝากเงินเข้าบัญชี";
				$data_acc['coop_account']['account_datetime'] = date('Y-m-d H:i:s');
				
				$i=0;
				$data_acc['coop_account_detail'][$i]['account_type'] = 'debit';
				$data_acc['coop_account_detail'][$i]['account_amount'] = $data['money'];
				$data_acc['coop_account_detail'][$i]['account_chart_id'] = '10100';
				$i++;
				$data_acc['coop_account_detail'][$i]['account_type'] = 'credit';
				$data_acc['coop_account_detail'][$i]['account_amount'] = $data['money'];
				$data_acc['coop_account_detail'][$i]['account_chart_id'] = '20100';
				$this->account_transaction->account_process($data_acc);
			}
			echo "<script> window.location.href = '".base_url(PROJECTPATH.'/save_money/account_detail?account_id='.$data['account_id'])."'</script>"; 
			exit();

		} else if($data["do"] == "withdrawal") {
			$sum = $balance - $data['money'];
			if($sum < 0) {
				$this->center_function->toastDanger("ไม่สามารถถอนเงินได้เนื่องจากจำนวนเงินคงเหลือไม่พอ");
			} else {
				
				$data_insert = array();
				$data_insert['transaction_time'] = date('Y-m-d H:i:s');
				$data_insert['transaction_list'] = $data['transaction_list'];
				$data_insert['transaction_withdrawal'] = $data['money'];
				$data_insert['transaction_deposit'] = '';
				$data_insert['transaction_balance'] = $sum;
				$data_insert['user_id'] = $_SESSION['USER_ID'];
				$data_insert['account_id'] = $data['account_id'];
				
				$this->db->insert('coop_account_transaction', $data_insert);

				$this->center_function->toast("ทำการถอนเงินเรียบร้อยแล้ว");
				$data_acc['coop_account']['account_description'] = "สมาชิกถอนเงินจากบัญชี";
				$data_acc['coop_account']['account_datetime'] = date('Y-m-d H:i:s');
				
				$i=0;
				$data_acc['coop_account_detail'][$i]['account_type'] = 'debit';
				$data_acc['coop_account_detail'][$i]['account_amount'] = $data['money'];
				$data_acc['coop_account_detail'][$i]['account_chart_id'] = '20100';
				$i++;
				$data_acc['coop_account_detail'][$i]['account_type'] = 'credit';
				$data_acc['coop_account_detail'][$i]['account_amount'] = $data['money'];
				$data_acc['coop_account_detail'][$i]['account_chart_id'] = '10100';
				$this->account_transaction->account_process($data_acc);				
				
			}

			echo "<script> window.location.href = '".base_url(PROJECTPATH.'/save_money/account_detail?account_id='.$data['account_id'])."'</script>"; 
			exit();
		}else if($data["do"] == "update_cover") {
			
			$this->db->select('*');
			$this->db->from('coop_maco_account');
			$this->db->where("account_id = '".$data['account_id']."'");
			$row = $this->db->get()->result_array();
			if($row[0]['book_number'] == $data['book_number']){
				$this->center_function->toastDanger("เล่มบัญชีของท่านเป็นเล่มที่ ".$data['book_number']." แล้ว");
			}else{
				$data_insert = array();
				$data_insert['book_number'] = $data['book_number'];
				$data_insert['print_number_point_now'] = '1';
				$this->db->where('account_id', $data['account_id']);
				$this->db->update('coop_maco_account', $data_insert);
				$this->center_function->toast("เพิ่มเล่มบัญชีเรียบร้อยแล้ว");
			}
			echo "<script> window.location.href = '".base_url(PROJECTPATH.'/save_money/account_detail?account_id='.$data['account_id'])."'</script>"; 
			exit();
		}
		
	}
	
	function book_bank_cover_pdf(){
		$arr_data = array();
		$account_id = $this->input->get('account_id');
		$arr_data['account_id'] = $account_id;
		$this->db->select(array('account_name','mem_id','book_number'));
		$this->db->from('coop_maco_account');
		$this->db->where("account_id = '".$account_id."'");
		$row = $this->db->get()->result_array();
		$arr_data['row'] = $row[0];
		
		$this->db->select(array('mem_group_id'));
		$this->db->from('coop_mem_apply');
		$this->db->where("member_id = '".$row[0]['mem_id']."'");
		$row_group = $this->db->get()->result_array();
		$arr_data['row_group'] = $row_group[0];
		
		$this->db->select(array('mem_group_name'));
		$this->db->from('coop_mem_group');
		$this->db->where("mem_group_id = '".$row_group[0]['mem_group_id']."'");
		$row_gname = $this->db->get()->result_array();
		if(!empty($row_gname)){
			$arr_data['row_gname'] = $row_gname[0];
		}else{
			$arr_data['row_gname']['mem_group_name'] = '';
		}
		
		
		$this->load->view('save_money/book_bank_cover_pdf',$arr_data);
	}
	
	function book_bank_page_pdf(){
		$arr_data = array();
		
		
		$this->load->view('save_money/book_bank_page_pdf',$arr_data);
	}
	
	function change_status($transaction_id, $account_id){
		$data_insert = array();
		$data_insert['print_status'] = '';
		$data_insert['print_number_point'] = '';
		$data_insert['book_number'] = '';
		$this->db->where(array('transaction_id >=' => $transaction_id, 'account_id'=>$account_id));
		$this->db->update('coop_account_transaction', $data_insert);
		
		$data_insert = array();
		$data_insert['print_number_point_now'] = '';
		$this->db->where('account_id', $account_id);
		$this->db->update('coop_maco_account', $data_insert);
		
		$this->center_function->toast("ยกเลืกพิมพ์รายการเรียบร้อยแล้ว");
		echo "<script> document.location.href = '".base_url(PROJECTPATH.'/save_money/account_detail?account_id='.$account_id)."'</script>";
		exit();
	}
}
