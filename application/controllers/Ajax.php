<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
	function __construct()
	{
		parent::__construct();

	}
	public function index()
	{
		exit;
	}
	public function search_member()
	{
		$where = "
			(member_id LIKE '%".$this->input->post('search')."%'
			OR firstname_th LIKE '%".$this->input->post('search')."%'
			OR lastname_th LIKE '%".$this->input->post('search')."%')
			AND member_status = '1'
		";
		$this->db->select('*');
		$this->db->from('coop_mem_apply');
		$this->db->where($where);
		$row = $this->db->get()->result_array();
		$arr_data['data'] = $row;

		$this->load->view('ajax/search_member',$arr_data);
	}
	public function search_member_jquery()
	{
		$where = "
			(member_id LIKE '%".$this->input->post('search')."%'
			OR firstname_th LIKE '%".$this->input->post('search')."%'
			OR lastname_th LIKE '%".$this->input->post('search')."%')
			AND member_status = '1'
		";
		if(@$_POST['member_id_not_allow']!=''){
			$where .= " AND member_id != '".$_POST['member_id_not_allow']."' ";
		}
		$this->db->select('*');
		$this->db->from('coop_mem_apply');
		$this->db->where($where);
		$row = $this->db->get()->result_array();
		$arr_data['data'] = $row;

		$this->load->view('ajax/search_member_jquery',$arr_data);
	}
	public function get_loan_data(){
		$member_id = isset($_POST['member_id']) ? trim($_POST['member_id']) : "";
		$member_id = sprintf("%06d",$member_id);
		$where = ' 1=1 ';
		if(@$_POST['loan_id']!=''){
			$where .= " AND coop_loan.id = '".$_POST['loan_id']."' ";
		}
		if(@$_POST['contract_number']!=''){
			$where .= " AND contract_number = '".$_POST['contract_number']."' ";
		}
				$this->db->select(array(
					'*', 
					'coop_loan.id', 
					'coop_loan.createdatetime',
					'coop_loan_type.loan_type', 
					'coop_loan_transfer.id as transfer_id',
					'coop_loan_transfer.account_id',
					'coop_loan_transfer.file_name',
					'coop_maco_account.account_name',
					'transfer_user.user_name'
				));
				$this->db->from('coop_loan');
				$this->db->join('coop_loan_type','coop_loan.loan_type = coop_loan_type.id','inner');
				$this->db->join('coop_loan_transfer',"coop_loan.id = coop_loan_transfer.loan_id AND transfer_status != '2'",'left');
				$this->db->join('coop_maco_account','coop_loan_transfer.account_id = coop_maco_account.account_id','left');
				$this->db->join('coop_user as transfer_user','transfer_user.user_id = coop_loan_transfer.admin_id','left');
				$this->db->where($where);
				$row = $this->db->get()->result_array();
				$row1 = @$row[0];
				
				if($row1['id']!=''){
				foreach($row1 as $key => $value){
					if($key == 'date_period_1' || $key == 'date_period_2' || $key == 'createdatetime' || $key == 'date_transfer'){
					$value = $this->center_function->mydate2date($value,true);
					}
					if($key == 'loan_amount' || $key == 'salary'){
						$value = number_format($value);
					}
					$data['coop_loan'][$key] = $value;
				}
				
				$loan_id = $row1['id'];
				$this->db->select(array(
					'*'
				));
				$this->db->from('coop_loan_guarantee');
				$this->db->where("loan_id = '".$loan_id."'");
				$rs2 = $this->db->get()->result_array();
				$i=0;
				foreach($rs2 as $key2 => $row2){
					foreach($row2 as $key => $value){
						if($key == 'amount' || $key == 'price' || $key == 'other_price'){
							$value = number_format($value);
						}
						$data['coop_loan_guarantee'][$i][$key] = $value;
					}
					$i++;
				}
				
				$this->db->select(array(
					'*',
					'coop_mem_group.mem_group_name'
				));
				$this->db->from('coop_loan_guarantee_person');
				$this->db->join('coop_mem_apply','coop_loan_guarantee_person.guarantee_person_id = coop_mem_apply.member_id','inner');
				$this->db->join('coop_mem_group','coop_mem_apply.level = coop_mem_group.id','left');
				$this->db->where("loan_id = '".$loan_id."'");
				$rs3 = $this->db->get()->result_array();
				$a = 0;
				foreach($rs3 as $key => $row3){
					$data['coop_loan_guarantee_person'][$a] = $row3;
					$this->db->select(array(
						'*'
					));
					$this->db->from('coop_loan_guarantee_person as t1');
					$this->db->join('coop_loan as t2','t1.loan_id = t2.id ','inner');
					$this->db->where("
						t1.guarantee_person_id = '".$row3['member_id']."' 
						AND t2.loan_status = '1'
					");
					$rs_count_guarantee = $this->db->get()->result_array();
					$count_guarantee=0;
					foreach($rs_count_guarantee as $key2 => $row_count_guarantee){
						$count_guarantee++;
					}
					$data['coop_loan_guarantee_person'][$a]['count_guarantee'] = $count_guarantee;
					$a++;
				}
				if(!empty($data['coop_loan_guarantee_person'])){
					foreach($data['coop_loan_guarantee_person'] as $key => $value){
							$data['coop_loan_guarantee_person'][$key]['guarantee_person_amount'] = number_format($data['coop_loan_guarantee_person'][$key]['guarantee_person_amount']);
					}
				}
				
				$this->db->select(array(
					'*'
				));
				$this->db->from('coop_loan_period');
				$this->db->where("
					loan_id = '".$loan_id."'
				");
				$rs4 = $this->db->get()->result_array();
				foreach($rs4 as $key => $row4){
					$data['coop_loan_period'][] = $row4;
				}
				
				$this->db->select(array(
					'*'
				));
				$this->db->from('coop_loan_file_attach');
				$this->db->where("
					loan_id = '".$loan_id."'
				");
				$rs5 = $this->db->get()->result_array();
				foreach($rs5 as $key => $row5){
					$data['coop_loan_file_attach'][] = $row5;
				}
				
				$this->db->select(array(
					'*'
				));
				$this->db->from('coop_mem_apply');
				$this->db->where("
					member_id = '".$row1['member_id']."'
				");
				$row6 = $this->db->get()->result_array();
				$data['coop_mem_apply'] = $row6[0];
				//echo"<pre>";print_r($data);echo"</pre>";
				echo json_encode($data);
				}else{
					echo 'not_found';
				}
		exit;
	}
	function get_account_list(){
		$arr_data = array();
		
		$this->db->select(array(
			'*'
		));
		$this->db->from('coop_maco_account');
		$this->db->where("
			mem_id = '".$_POST['member_id']."'
		");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = $rs;
				
		$this->load->view('ajax/get_account_list',$arr_data);
	}
	
	function get_member(){
		$member_id = isset($_POST['member_id']) ? trim($_POST['member_id']) : "";
		$member_id = sprintf("%06d",$member_id);
		
		$this->db->select(array(
			'coop_mem_apply.member_id',
			'coop_mem_apply.firstname_th',
			'coop_mem_apply.lastname_th',
			'coop_mem_group.mem_group_name'
		));
		$this->db->from('coop_mem_apply');
		$this->db->join('coop_mem_group','coop_mem_apply.mem_group_id = coop_mem_group.mem_group_id','left');
		$this->db->where("
			member_id = '".$member_id."'
		");
		$row = $this->db->get()->result_array();
		$row = @$row[0];
		
		$data = array();

		$data['member_id'] = $row['member_id'];
		$data['member_name'] = $row['firstname_th']." ".$row['lastname_th'];
		$data['member_group_name'] = $row['mem_group_name']; 

		if($_POST['for_loan']=='1'){
			
			$this->db->select(array(
				'num_guarantee'
			));
			$this->db->from('coop_term_of_loan');
			$this->db->where("
				type_id = '".$_POST['loan_type']."'
				AND start_date <= '".date('Y-m-d')."'
			");
			$this->db->order_by('start_date DESC');
			$this->db->limit(1);
			$row_term_of_loan = $this->db->get()->result_array();
			$row_term_of_loan = $row_term_of_loan[0];
			
			$this->db->select(array(
				'*'
			));
			$this->db->from('coop_loan_guarantee_person as t1');
			$this->db->join('coop_loan as t2','t1.loan_id = t2.id ','inner');
			$this->db->where("
				t1.guarantee_person_id = '".$row['member_id']."' 
				AND t2.loan_status IN('1','2')
			");
			$rs_count_guarantee = $this->db->get()->result_array();
			$i=0;
			foreach($rs_count_guarantee as $key => $row_count_guarantee){
				$i++;
			}
			if($i>=$row_term_of_loan['num_guarantee']){
				echo 'over_guarantee';
				exit;
			}else{
				echo $i;
				exit;
			}
		}
		echo json_encode($data);
		exit;
	}
	
	function ajax_get_guarantee_person_data(){
		$this->db->select(array(
			't2.contract_number',
			't2.member_id',
			't4.prename_short',
			't3.firstname_th',
			't3.lastname_th',
			't2.loan_amount',
			't1.guarantee_person_amount_balance'
		));
		$this->db->from('coop_loan_guarantee_person as t1');
		$this->db->join('coop_loan as t2','t1.loan_id = t2.id','inner');
		$this->db->join('coop_mem_apply as t3','t2.member_id = t3.member_id','inner');		
		$this->db->join('coop_prename as t4','t3.prename_id = t4.prename_id','left');
		$this->db->where("t1.guarantee_person_id = '".$member_id."' AND t2.loan_status = '1'");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = @$rs;

		$this->load->view('ajax/ajax_get_guarantee_person_data',$arr_data);
	}	
	
	public function search_account()
	{
		$search_text = @$_POST["search_text"];
		
		$this->db->select(array('*'));
		$this->db->from('coop_maco_account');
		$this->db->where("account_id LIKE '%".$search_text."%' OR mem_id LIKE '%".$search_text."%' OR account_name LIKE '%".$search_text."%'");
		$this->db->order_by("account_id DESC");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = @$rs;

		$this->load->view('ajax/search_account',$arr_data);
	}
}
