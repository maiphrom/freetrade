<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beneficiary extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		if($this->input->post()){

			$data = $this->input->post();
			$data['admin_id'] = $_SESSION['USER_ID'];
			if($data['gain_detail_id']!=''){
				$this->db->where('gain_detail_id', $data['gain_detail_id']);
				unset($data['gain_detail_id']);
				$this->db->update('coop_mem_gain_detail', $data);
			}else{
				unset($data['gain_detail_id']);
				$data['g_create'] = date('Y-m-d H:i:s');
				$this->db->insert('coop_mem_gain_detail', $data);
			}
			//echo"<pre>";print_r($data);
			echo "<script> document.location.href = '".PROJECTPATH."/beneficiary?member_id=".$data['member_id']."' </script>";
			exit;
		}
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

			$this->db->select(
				array(
					'coop_mem_gain_detail.*',
					'coop_prename.prename_short',
					'coop_district.district_name',
					'coop_amphur.amphur_name',
					'coop_province.province_name',
					'coop_mem_relation.relation_name',
					'coop_user.user_name'
				)
			);
			$this->db->from('coop_mem_gain_detail');
			$this->db->where("member_id = '".$member_id."'");
			$this->db->join('coop_prename', 'coop_prename.prename_id = coop_mem_gain_detail.g_prename_id', 'left');
			$this->db->join('coop_district', 'coop_district.district_id = coop_mem_gain_detail.g_district_id', 'left');
			$this->db->join('coop_amphur', 'coop_amphur.amphur_id = coop_mem_gain_detail.g_amphur_id', 'left');
			$this->db->join('coop_province', 'coop_province.province_id = coop_mem_gain_detail.g_province_id', 'left');
			$this->db->join('coop_mem_relation', 'coop_mem_relation.relation_id = coop_mem_gain_detail.g_relation_id', 'left');
			$this->db->join('coop_user', 'coop_user.user_id = coop_mem_gain_detail.admin_id', 'left');
			$row = $this->db->get()->result_array();
			$arr_data['data'] = $row;
		}else{
			$arr_data['row_member'] = array();
			$arr_data['data'] = array();
		}
		//echo"<pre>";print_r($arr_data['data']);exit;
		$this->libraries->template('beneficiary/index',$arr_data);
	}

	function add_beneficiary(){
		$arr_data = array();
		$arr_data['gain_detail_id'] = $this->input->post('gain_detail_id');
		$arr_data['member_id'] = $this->input->post('member_id');

		if($arr_data['gain_detail_id']!=''){
			$this->db->select('*');
			$this->db->from('coop_mem_gain_detail');
			$this->db->where("gain_detail_id = '".$arr_data['gain_detail_id']."'");
			$row = $this->db->get()->result_array();
			$arr_data['data'] = $row[0];
		}else{
			$arr_data['data'] = array();
		}

		$this->db->select('relation_id, relation_name');
		$this->db->from('coop_mem_relation');
		$row = $this->db->get()->result_array();
		$arr_data['relation'] = $row;

		$this->db->select('prename_id, prename_full');
		$this->db->from('coop_prename');
		$row = $this->db->get()->result_array();
		$arr_data['prename'] = $row;

		$this->db->select('province_id, province_name');
		$this->db->from('coop_province');
		$this->db->order_by('province_name');
		$row = $this->db->get()->result_array();
		$arr_data['province'] = $row;

		if(@$arr_data['data']["g_province_id"]!=''){
			$this->db->select('amphur_id, amphur_name');
			$this->db->from('coop_amphur');
			$this->db->where("province_id = '".$arr_data['data']["g_province_id"]."'");
			$this->db->order_by('amphur_name');
			$row = $this->db->get()->result_array();
			$arr_data['amphur'] = $row;
		}else{
			$arr_data['amphur'] = array();
		}

		if(@$arr_data['data']["g_amphur_id"]!=''){
			$this->db->select('district_id, district_name');
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '".@$arr_data['data']["g_amphur_id"]."'");
			$this->db->order_by('district_name');
			$row = $this->db->get()->result_array();
			$arr_data['district'] = $row;
		}else{
			$arr_data['district'] = array();
		}

		$this->load->view('beneficiary/add_beneficiary',$arr_data);
	}

	function show_beneficiary(){
		$arr_data = array();

		$this->db->select(
			array(
				'coop_mem_gain_detail.*',
				'coop_prename.prename_short',
				'coop_district.district_name',
				'coop_amphur.amphur_name',
				'coop_province.province_name',
				'coop_mem_relation.relation_name',
				'coop_user.user_name'
			)
		);
		$this->db->from('coop_mem_gain_detail');
		$this->db->where("gain_detail_id = '".$this->input->post('gain_detail_id')."'");
		$this->db->join('coop_prename', 'coop_prename.prename_id = coop_mem_gain_detail.g_prename_id', 'left');
		$this->db->join('coop_district', 'coop_district.district_id = coop_mem_gain_detail.g_district_id', 'left');
		$this->db->join('coop_amphur', 'coop_amphur.amphur_id = coop_mem_gain_detail.g_amphur_id', 'left');
		$this->db->join('coop_province', 'coop_province.province_id = coop_mem_gain_detail.g_province_id', 'left');
		$this->db->join('coop_mem_relation', 'coop_mem_relation.relation_id = coop_mem_gain_detail.g_relation_id', 'left');
		$this->db->join('coop_user', 'coop_user.user_id = coop_mem_gain_detail.admin_id', 'left');
		$row = $this->db->get()->result_array();
		$arr_data['data'] = $row[0];

		$this->load->view('beneficiary/show_beneficiary',$arr_data);
	}

	function delete_beneficiary($gain_detail_id,$member_id){
		$this->db->where('gain_detail_id', $gain_detail_id);
		$this->db->delete('coop_mem_gain_detail');

		echo "<script> document.location.href = '".PROJECTPATH."/beneficiary?member_id=".$member_id."' </script>";
		exit;
	}
}
