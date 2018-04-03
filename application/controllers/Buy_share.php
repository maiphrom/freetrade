<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buy_share extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{

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

		$arr_data['count_share'] = 0;
		$arr_data['cal_share'] = 0;

		if($member_id != '') {
			$this->db->select('*');
			$this->db->from('coop_mem_apply');
			$this->db->where("member_id = '" . $member_id . "'");
			$row = $this->db->get()->result_array();
			$arr_data['row_member'] = $row[0];

			$this->db->select('*');
			$this->db->from('coop_mem_share');
			$this->db->where("member_id = '" . $member_id . "' AND share_status IN('1','2')");
			$row = $this->db->get()->result_array();
			foreach ($row as $key => $value) {
				$arr_data['count_share'] += $value['share_early'];
				$arr_data['cal_share'] += $value['share_early'] * $value['share_value'];
			}

			$x=0;
			$join_arr = array();
			$join_arr[$x]['table'] = 'coop_user';
			$join_arr[$x]['condition'] = 'coop_mem_share.admin_id = coop_user.user_id';
			$join_arr[$x]['type'] = 'left';
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('*');
			$this->paginater_all->main_table('coop_mem_share');
			$this->paginater_all->where("member_id = '".$member_id."' AND share_type = 'SPA'");
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('share_date DESC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			$i = $row['page_start'];


			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['data'] = $row['data'];
			$arr_data['i'] = $i;
		}else{
			$arr_data['data'] = array();
			$arr_data['paging'] = '';
		}

		$this->libraries->template('buy_share/index',$arr_data);
	}
	function save_share(){
		if($this->input->post()){
			$data = $this->input->post();
			if(@$data['delete']=='1'){
				$this->db->where('share_id', @$data['share_id']);
				$this->db->delete('coop_mem_share');
				$this->center_function->toast("ลบข้อมูลเรียบร้อยแล้ว");
			}else if(@$data['cancel_receipt']=='1'){
				$this->db->select('*');
				$this->db->from("coop_mem_share");
				$this->db->where("share_id = '".@$data['share_id']."'");
				$row = $this->db->get()->result_array();

				$data_insert = array();
				$data_insert['receipt_status'] = '1';
				$data_insert['admin_id'] = $_SESSION['USER_ID'];
				$data_insert['cancel_date'] = date('Y-m-d H:i:s');

				$this->db->where('receipt_id', $row[0]['share_bill']);
				$this->db->update('coop_receipt', $data_insert);

				$data_insert = array();
				$data_insert['share_status'] = '2';

				$this->db->where('share_id', @$data['share_id']);
				$this->db->update('coop_mem_share', $data_insert);

				echo "success";exit;
			}else{
				unset($data['delete']);
				unset($data['share_id']);
				$data['admin_id'] = $_SESSION['USER_ID'];
				$data['share_type'] = 'SPA';
				$data['share_date'] = date('Y-m-d H:i:s');
				$data['share_collect'] = @$data['share_early']+@$data['share_payable'];
				$data['share_collect_value'] = @$data['share_early_value']+@$data['share_payable_value'];
				$data['share_status'] = '0';

				$this->db->insert('coop_mem_share', $data);
				$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			}
			echo "<script> document.location.href = '".PROJECTPATH."/buy_share?member_id=".$data['member_id']."' </script>";
			//echo"<pre>";print_r($data);
			exit;
		}
	}

	function receipt_buy_share_temp(){
		$arr_data = array();

		$arr_data['member_id'] = $this->input->get('member_id');

		$this->db->select('*');
		$this->db->from("coop_mem_apply");
		$this->db->where("member_id = '".$arr_data['member_id']."'");
		$row = $this->db->get()->result_array();

		$arr_data['name'] = $row[0]['firstname_th'].' '.$row[0]['lastname_th'];

		$arr_data['receipt_id'] = "";
		$arr_data['num_share'] = $this->input->get('num_share');
		$arr_data['value'] = $this->input->get('value');
		
		//ลายเซ็นต์
		$date_signature = date('Y-m-d');
		$this->db->select(array('*'));
		$this->db->from('coop_signature');
		$this->db->where("start_date <= '{$date_signature}'");
		$this->db->order_by('start_date DESC');
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		$arr_data['signature'] = @$row[0];

		$this->load->view('buy_share/receipt_buy_share_temp',$arr_data);
	}

	function receipt_process(){
		$data = $this->input->post();

		$this->db->select('*');
		$this->db->from("coop_mem_share");
		$this->db->where("share_id = '".$data['share_id']."'");
		$row = $this->db->get()->result_array();
		$mem_share = $row[0];

		if($mem_share['share_bill'] == ''){
			$this->db->select('*');
			$this->db->from("coop_receipt");
			$this->db->where("receipt_id LIKE '".date("Ym")."%'");
			$this->db->order_by("receipt_id DESC");
			$this->db->limit(1);
			$row = $this->db->get()->result_array();

			if(!empty($row)) {
				$id = (int) substr($row[0]["receipt_id"], 6);
				$receipt_number = date("Ym").sprintf("%06d", $id + 1);
			}else {
				$receipt_number = date("Ym")."000001";
			}

			$data_insert = array();
			$data_insert['receipt_id'] = $receipt_number;
			$data_insert['member_id'] = $mem_share['member_id'];
			$data_insert['sumcount'] = $mem_share['share_early_value'];
			$data_insert['admin_id'] = $_SESSION['USER_ID'];
			$data_insert['receipt_datetime'] = date('Y-m-d H:i:s');
			$data_insert['receipt_status'] = '0';

			$this->db->insert('coop_receipt', $data_insert);

			$data_insert = array();
			$data_insert['receipt_id'] = $receipt_number;
			$data_insert['receipt_list'] = '14';
			$data_insert['receipt_count'] = $mem_share['share_early_value'];
			$data_insert['receipt_count_item'] = $mem_share['share_early'];

			$this->db->insert('coop_receipt_detail', $data_insert);

			$data_insert = array();
			$data_insert['member_id'] = $mem_share['member_id'];
			$data_insert['receipt_id'] = $receipt_number;
			$data_insert['account_list_id'] = '14';
			$data_insert['principal_payment'] = number_format($mem_share['share_early_value'],2,'.','');
			$data_insert['interest'] = '0';
			$data_insert['total_amount'] = number_format($mem_share['share_early_value'],2,'.','');
			$data_insert['payment_date'] = date('Y-m-d H:i:s');
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');

			$this->db->insert('coop_finance_transaction', $data_insert);

			$data_insert = array();
			$data_insert['share_status'] = '1';
			$data_insert['share_bill'] = $receipt_number;
			$data_insert['share_bill_date'] = date('Y-m-d H:i:s');

			$this->db->where('share_id', $data['share_id']);
			$this->db->update('coop_mem_share', $data_insert);

			$data['coop_account']['account_description'] = "สมาชิกซื้อหุ้นเพิ่มพิเศษ";
			$data['coop_account']['account_datetime'] = date('Y-m-d H:i:s');

			$i=0;
			$data['coop_account_detail'][$i]['account_type'] = 'debit';
			$data['coop_account_detail'][$i]['account_amount'] = $mem_share['share_early_value'];
			$data['coop_account_detail'][$i]['account_chart_id'] = '10100';
			$i++;
			$data['coop_account_detail'][$i]['account_type'] = 'credit';
			$data['coop_account_detail'][$i]['account_amount'] = $mem_share['share_early_value'];
			$data['coop_account_detail'][$i]['account_chart_id'] = '30100';
			$this->account_transaction->account_process($data);
		}else{
			$receipt_number = $mem_share['share_bill'];
		}
		echo $receipt_number;exit;
	}
	
	function cancel_receipt(){
		
		if ($this->input->post()) {
		  if($this->input->post('cancel_receipt')=='1'){
				if($this->input->post('status_to')=='2'){
					$receipt_status = '2';
					$share_status = '3';
				}else{
					$receipt_status = '1';
					$share_status = '2';
				}
					
					$data_insert = array();
					$data_insert['receipt_status'] = $receipt_status;

					$this->db->where('receipt_id', $this->input->post('receipt_id'));
					$this->db->update('coop_receipt', $data_insert);
					
					$this->db->select('*');
					$this->db->from("coop_receipt_detail");
					$this->db->where("receipt_id = '".$this->input->post('receipt_id')."'");
					$row = $this->db->get()->result_array();
					
					foreach($row as $key => $value){
						if($value['receipt_list']=='14'){
							$data_insert = array();
							$data_insert['share_status'] = $share_status;

							$this->db->where('share_bill', $this->input->post('receipt_id'));
							$this->db->update('coop_mem_share', $data_insert);
						}
					}
					
				
				echo "success";exit;
			}
		}
		$arr_data = array();

		$x=0;
		$join_arr = array();
		$join_arr[$x]['table'] = 'coop_user';
		$join_arr[$x]['condition'] = 'coop_receipt.admin_id = coop_user.user_id';
		$join_arr[$x]['type'] = 'left';
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_receipt');
		$this->paginater_all->where("receipt_status = '1' OR receipt_status = '2'");
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
		foreach($arr_data['data'] as $key => $value){
			$this->db->select('*');
			$this->db->from('coop_receipt_detail');
			$this->db->where("receipt_id = '".$value['receipt_id']."'");
			$row = $this->db->get()->result_array();
			$arr_data['data'][$key]['receipt_detail'] = $row;
		}
		
		
		$account_list = array();
		
		$this->db->select('*');
		$this->db->from('coop_account_list');
		$row = $this->db->get()->result_array();
		foreach($row as $key => $value){
			$account_list[$value['account_id']] = $value['account_list'];
		}
		$arr_data['account_list'] = $account_list;

		$this->libraries->template('buy_share/cancel_receipt',$arr_data);
	}
}
