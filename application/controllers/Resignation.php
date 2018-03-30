<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resignation extends CI_Controller {
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
		if($member_id != ''){
			$this->db->select('*');
			$this->db->from('coop_mem_apply');
			$this->db->where("member_id = '".$member_id."'");
			$row = $this->db->get()->result_array();
			$arr_data['row_member'] = $row[0];

			$share_type = array('SPA'=>'ซื้อหุ้นเพิ่มพิเศษ','SPM'=>'หุ้นรายเดือน');
			foreach($share_type as $key => $value){
				$this->db->select('*');
				$this->db->from('coop_mem_share');
				$this->db->where("member_id = '".$member_id."' AND share_type = '".$key."' AND share_status = '1'");
				$this->db->order_by('share_id DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				if(!empty($row)) {
					$arr_data['row_share'][$key] = $row[0];
				}
			}

			$this->db->select('*');
			$this->db->from('coop_loan');
			$this->db->where("member_id = '".$member_id."' AND loan_status = '1'");
			$row = $this->db->get()->result_array();
			$arr_data['row_loan'] = $row;
			foreach($arr_data['row_loan'] as $key => $value){
				$this->db->select('*');
				$this->db->from('coop_finance_transaction');
				$this->db->where("loan_id = '".$value['id']."'");
				$this->db->order_by('finance_transaction_id DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				if(!empty($row)) {
					$arr_data['row_loan'][$key]['payment_date'] = $row[0]['payment_date'];
				}else{
					$arr_data['row_loan'][$key]['payment_date'] = '';
				}
			}

			$this->db->select(
				array(
					'coop_loan.*',
					'coop_mem_apply.*'
				)
			);
			$this->db->from('coop_loan_guarantee_person');
			$this->db->join('coop_loan', 'coop_loan_guarantee_person.loan_id = coop_loan.id', 'inner');
			$this->db->join('coop_mem_apply', 'coop_loan.member_id = coop_mem_apply.member_id', 'inner');
			$this->db->where("coop_loan_guarantee_person.guarantee_person_id = '".$member_id."' AND coop_loan_guarantee_person.guarantee_person_id <>'' AND coop_loan.loan_status='1'");
			$row = $this->db->get()->result_array();
			$arr_data['row_guarantee'] = $row;

			$this->db->select('*');
			$this->db->from('coop_maco_account');
			$this->db->where("mem_id = '".$member_id."'");
			$row = $this->db->get()->result_array();
			$arr_data['row_account'] = $row;

			foreach($arr_data['row_account'] as $key => $value){
				$this->db->select('*');
				$this->db->from('coop_account_transaction');
				$this->db->where("account_id = '".$value['account_id']."'");
				$this->db->order_by('transaction_id DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				$arr_data['row_account'][$key]['transaction_balance'] = @$row[0]['transaction_balance'];
			}

			$this->db->select('*');
			$this->db->from('coop_mem_req_resign');
			$this->db->where("member_id = '".$member_id."'");
			$this->db->order_by('req_resign_id DESC');
			$row = $this->db->get()->result_array();
			if(!empty($row)) {
				$arr_data['data'] = $row[0];
			}

			$this->db->select(array('resign_cause_id','resign_cause_name'));
			$this->db->from('coop_mem_resign_cause');
			$row = $this->db->get()->result_array();
			$arr_data['resign_cause'] = $row;

		}else{
			$arr_data['row_member'] = array();
			$arr_data['row_share'] = array();
			$arr_data['row_loan'] = array();
			$arr_data['row_guarantee'] = array();
			$arr_data['row_account'] = array();
			$arr_data['data'] = array();
		}

		$this->libraries->template('resignation/index',$arr_data);
	}

	public function save_resignation(){
		$data = $this->input->post();
		//echo"<pre>";print_r($data);echo"</pre>";exit;

		$data['user_id'] = $_SESSION['USER_ID'];

		if($data['delete_resign']=='1'){
			$this->db->where('req_resign_id', $data['req_resign_id']);
			$this->db->delete('coop_mem_req_resign');
			$this->center_function->toast("ยกเลิกรายการเรียบร้อยแล้ว");
		}else{
			unset($data['delete_resign']);
			$req_resign_date_arr = explode('/',$data['req_resign_date']);
			$data['req_resign_date'] = ($req_resign_date_arr[2]-543)."-".$req_resign_date_arr[1]."-".$req_resign_date_arr[0];
			$resign_date_arr = explode('/',$data['resign_date']);
			$data['resign_date'] = ($resign_date_arr[2]-543)."-".$resign_date_arr[1]."-".$resign_date_arr[0];
			if($data['req_resign_id']=='' || $data['req_resign_status']=='2'){
				$this->db->select('req_resign_no');
				$this->db->from('coop_mem_req_resign');
				$this->db->order_by('req_resign_id DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				if(!empty($row)){
					$req_resign_no = (int)$row[0]['req_resign_no']+1;
				}else{
					$req_resign_no = 1;
				}

				$data['req_resign_no'] = sprintf('% 06d',$req_resign_no);
				$data['req_resign_status'] = '0';

				unset($data['req_resign_id']);

				$this->db->insert('coop_mem_req_resign', $data);
			}else{
				$this->db->where('req_resign_id', $data['req_resign_id']);
				unset($data['req_resign_id']);
				$this->db->update('coop_mem_req_resign', $data);
			}
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		}

		echo "<script> document.location.href = '".PROJECTPATH."/resignation?member_id=".$data['member_id']."' </script>";
		exit;
	}
	public function resignation_approve(){
		if($this->input->post()){

			$data = $this->input->post();
			$data['approve_date'] = date('Y-m-d H:i:s');
			$data['approve_user_id'] = $_SESSION['USER_ID'];

			$this->db->where('req_resign_id', $data['req_resign_id']);
			unset($data['req_resign_id']);
			$this->db->update('coop_mem_req_resign', $data);

			if($data['req_resign_status'] == '1'){
				$this->db->select('member_id');
				$this->db->from('coop_mem_req_resign');
				$this->db->where("req_resign_id = '".$this->input->post('req_resign_id')."'");
				$row = $this->db->get()->result_array();

				$data_member = array();
				$data_member['member_status'] = '2';
				$data_member['mem_type'] = '2';

				$this->db->where('member_id', $row[0]['member_id']);
				$this->db->update('coop_mem_apply', $data_member);
			}

			echo "<script> document.location.href = '".PROJECTPATH."/resignation/resignation_approve' </script>";
		}
		$arr_data = array();

		$this->db->select(
			array(
				'coop_mem_req_resign.*'
			)
		);
		$this->db->from('coop_mem_req_resign');
		$this->db->join('coop_mem_apply', 'coop_mem_req_resign.member_id = coop_mem_apply.member_id', 'inner');
		$this->db->join('coop_mem_resign_cause', 'coop_mem_req_resign.resign_cause_id = coop_mem_resign_cause.resign_cause_id', 'inner');
		$this->db->join('coop_user', 'coop_mem_req_resign.user_id = coop_user.user_id', 'inner');
		$row = $this->db->get()->result_array();

		$num_rows = count($row) ;
		$per_page = 10 ;
		$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
		$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

		$page_start = (($per_page * $page) - $per_page);
		if($page_start==0){ $page_start = 1;}

		$this->db->select('*');
		$this->db->from('( SELECT
								coop_mem_req_resign.*,
								coop_mem_apply.apply_date,
								coop_mem_apply.firstname_th,
								coop_mem_apply.lastname_th,
								coop_mem_resign_cause.resign_cause_name,
								coop_user.user_name,
								ROW_NUMBER () OVER (ORDER BY req_resign_id DESC) AS row
							FROM
								coop_mem_req_resign
							INNER JOIN coop_mem_apply ON coop_mem_req_resign.member_id = coop_mem_apply.member_id
							INNER JOIN coop_mem_resign_cause ON coop_mem_req_resign.resign_cause_id = coop_mem_resign_cause.resign_cause_id
						 	INNER JOIN coop_user ON coop_mem_req_resign.user_id = coop_user.user_id
							 ) a');
		$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
		$this->db->order_by('a.req_resign_id DESC');
		$row = $this->db->get()->result_array();

		$i = $page_start;

		$arr_data['num_rows'] = $num_rows;
		$arr_data['paging'] = $paging;
		$arr_data['row'] = $row;
		$arr_data['i'] = $i;


		$this->libraries->template('resignation/resignation_approve',$arr_data);
	}
}
