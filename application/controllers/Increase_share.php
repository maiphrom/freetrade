<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Increase_share extends CI_Controller {
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
		$arr_data['member_id'] = $member_id;
		
		$arr_data['count_share'] = 0;
		$arr_data['cal_share'] = 0;
		
		if($member_id != '') {
			$this->db->select('*');
			$this->db->from('coop_mem_apply');
			$this->db->where("member_id = '" . $member_id . "'");
			$row = $this->db->get()->result_array();
			$arr_data['row_member'] = $row[0];

			$x=0;
			$join_arr = array();
			$join_arr[$x]['table'] = 'coop_user';
			$join_arr[$x]['condition'] = 'coop_change_share.admin_id = coop_user.user_id';
			$join_arr[$x]['type'] = 'left';
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('*');
			$this->paginater_all->main_table('coop_change_share');
			$this->paginater_all->where("member_id = '".$member_id."'");
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('change_share_id DESC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			$i = $row['page_start'];


			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['data'] = $row['data'];
			$arr_data['i'] = $i;
			
			$this->db->select('*');
			$this->db->from('coop_mem_share');
			$this->db->where("member_id = '" . $member_id . "' AND share_status IN('1','2')");
			$row = $this->db->get()->result_array();
			foreach ($row as $key => $value) {
				$arr_data['count_share'] += $value['share_early'];
				$arr_data['cal_share'] += $value['share_early'] * $value['share_value'];
			}
			
			$this->db->select('*');
			$this->db->from('coop_change_share');
			$this->db->where("member_id = '".$member_id."' AND change_share_status IN('1','2')");
			$this->db->order_by('change_share_id DESC');
			$this->db->limit(1);
			$row = $this->db->get()->result_array();
			
			if(!empty($row)){
				$share_per_month = $row[0]['change_value'];
			}else{
				$this->db->select(array('share_salary','salary_rule'));
				$this->db->from('coop_share_rule');
				$this->db->where("salary_rule <= '".$arr_data['row_member']['salary']."'");
				$this->db->order_by('salary_rule DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				
				$share_per_month = $row[0]['share_salary'];
			}
			$arr_data['share_per_month'] = $share_per_month;
		}else{
			$arr_data['data'] = array();
			$arr_data['paging'] = '';
			$arr_data['share_per_month'] = 0;
		}

		$this->db->select('*');
		$this->db->from('coop_share_setting');
		$this->db->order_by('setting_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['share_value'] = $row[0]['setting_value'];
		
		$this->libraries->template('increase_share/index',$arr_data);
	}
	
	function save_increase_share(){
		if ($_POST) {
			//echo"<pre>";print_r($_POST);echo"</pre>";exit;
			$data = $this->input->post();
			if(isset($data['cancel_change_share']) && $data['cancel_change_share']=='1'){
				$data_insert = array();
				$data_insert['change_share_status'] = '2';
				$data_insert['cancel_date'] = date('Y-m-d H:i:s');

				$this->db->where('change_share_id', $data['change_share_id']);
				$this->db->update('coop_change_share', $data_insert);
				
				echo "success";exit;
			}else{
				$this->db->select('*');
				$this->db->from('coop_change_share');
				$this->db->where("member_id = '".$data['member_id']."' AND change_share_status IN('1','2')");
				$this->db->order_by('change_share_id DESC');
				$this->db->limit(1);
				$row_prev_share = $this->db->get()->result_array();
				
				if(@$row_prev_share[0]['change_value']==''){
					$change_type = 'increase';
				}else if($data['change_value'] > @$row_prev_share[0]['change_value']){
					$change_type = 'increase';
				}else{
					$change_type = 'decrease';
				}
				
				$this->db->select('*');
				$this->db->from('coop_approval_cycle');
				$this->db->where("id='1'");
				$row_approval = $this->db->get()->result_array();
				
				if(date('d')>$row_approval[0]['approval_date']){
					$active_date = date('Y-m-d 00:00:00',strtotime('+1 month',strtotime(date('Y-m-'.$row_approval[0]['approval_date'].' 00:00:00'))));
					$active_date = date('Y-m-d 00:00:00',strtotime('+1 day',strtotime($active_date)));
				}else{
					$active_date = date('Y-m-d 00:00:00',strtotime('+1 day',strtotime(date('Y-m-'.$row_approval[0]['approval_date'].' 00:00:00'))));
				}
				
				$data_insert = array();
				$data_insert['member_id'] = $data['member_id'];
				$data_insert['admin_id'] = $_SESSION['USER_ID'];
				$data_insert['change_type'] = $change_type;
				$data_insert['share_value'] = $data['share_value'];
				$data_insert['change_value'] = $data['change_value'];
				$data_insert['change_value_price'] = $data['change_value_price'];
				$data_insert['create_date'] = date('Y-m-d H:i:s');
				$data_insert['active_date'] = $active_date;
				$data_insert['change_share_status'] = '1';
				$this->db->insert('coop_change_share', $data_insert);
				
				$this->center_function->toast('บันทึกข้อมูลเรียบร้อยแล้ว');
				echo "<script>window.location.href = '".PROJECTPATH."/increase_share?member_id=".$data['member_id']."';</script>";
		
			}
		}
	}
	
	public function check_increase_share(){
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
			
			$this->db->select(array('start_date','end_date'));
			$this->db->from('coop_account_year');
			$this->db->where("account_year = '".(date('Y')+543)."' AND is_close='0'");
			$row_year = $this->db->get()->result_array();
			
			$this->db->select(array('create_date'));
			$this->db->from('coop_change_share');
			$this->db->where("member_id = '".$this->input->post('member_id')."' 
				AND create_date BETWEEN '".$row_year[0]['start_date']."' AND '".$row_year[0]['end_date']."'
				AND change_share_status <> '3'");
			$this->db->order_by('create_date DESC');
			$row = $this->db->get()->result_array();
			
			if(!empty($row)){
				$create_date = $this->center_function->mydate2date(@$row[0]['create_date']);
				echo $create_date;
			}else{
				echo 'NOT FOUND';
			}
			exit;
	}
	
	public function check_decrease_share(){
		
		$this->db->select(array('salary'));
		$this->db->from('coop_mem_apply');
		$this->db->where("member_id = '".$this->input->post('member_id')."'");
		$row_member = $this->db->get()->result_array();
		
		$this->db->select(array('share_salary','salary_rule'));
		$this->db->from('coop_share_rule');
		$this->db->where("salary_rule <= '".$row_member[0]['salary']."'");
		$this->db->order_by('salary_rule DESC');
		$this->db->limit(1);
		$row_rule = $this->db->get()->result_array();
		
		if( $this->input->post('change_value') < $row_rule[0]['share_salary']){
			echo "เงินเดือนไม่น้อยกว่า ".$row_rule[0]['salary_rule']." บาท ต้องถือหุ้นรายเดือนอย่างน้อย ".$row_rule[0]['share_salary']." หุ้น";
		}else{
			echo "pass";
		}
		
		exit;
	}
	
	function cancel_increase_share(){
		if ($this->input->post()) {
			$data = $this->input->post();
		  if($data['cancel_change_share']=='1'){
				
				$data_insert = array();
				$data_insert['change_share_status'] = $data['status_to'];

				$this->db->where('change_share_id', $data['change_share_id']);
				$this->db->update('coop_change_share', $data_insert);
					
				echo "success";exit;
			}
		}
		$arr_data = array();
		
		$this->db->select('*');
		$this->db->from('coop_change_share');
		$this->db->where("change_share_status IN('2','3')");
		$this->db->order_by('cancel_date DESC');
		$row = $this->db->get()->result_array();

		$num_rows = count($row);
		$per_page = 20 ;
		$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
		$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

		$page_start = (($per_page * $page) - $per_page);
		if($page_start==0){ $page_start = 1;}

		$this->db->select('*');
		$this->db->from("( SELECT 
			coop_change_share.*,
			coop_user.user_name,
			coop_mem_apply.firstname_th,
			coop_mem_apply.lastname_th,
			coop_prename.prename_short,
			ROW_NUMBER() OVER (ORDER BY cancel_date DESC) as row 
		FROM coop_change_share 
		LEFT JOIN coop_user ON coop_change_share.admin_id = coop_user.user_id
		LEFT JOIN coop_mem_apply ON coop_change_share.member_id = coop_mem_apply.member_id
		LEFT JOIN coop_prename ON coop_mem_apply.prename_id = coop_prename.prename_id
		WHERE change_share_status IN('2','3') ) a");
		$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
		$this->db->order_by('cancel_date DESC');
		$row = $this->db->get()->result_array();

		$i = $page_start;


		$arr_data['num_rows'] = $num_rows;
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row;
		$arr_data['i'] = $i;
		
		$this->libraries->template('increase_share/cancel_increase_share',$arr_data);
	}
	
}
