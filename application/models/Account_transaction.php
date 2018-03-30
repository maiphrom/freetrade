<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_transaction extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		# Load libraries
		//$this->load->library('parser');
		$this->load->helper(array('html', 'url'));
	}

	public function account_process($data){
		$data_insert = array();
		$data_insert['account_description'] = $data['coop_account']['account_description'];
		$data_insert['account_datetime'] = $data['coop_account']['account_datetime'];
		if(isset($data['coop_account']['account_status']) && $data['coop_account']['account_status']=='1'){
			$data_insert['account_status'] = '1';
		}
		$this->db->insert('coop_account', $data_insert);

		$this->db->select('account_id');
		$this->db->from("coop_account");
		$this->db->order_by("account_id DESC");
		$this->db->limit(1);
		$row = $this->db->get()->result_array();

		$account_id = $row[0]['account_id'];

		foreach($data['coop_account_detail'] as $key => $value){
			if($value['account_amount'] > 0){
				$data_insert = array();
				$data_insert['account_id'] = $account_id;
				$data_insert['account_type'] = $value['account_type'];
				$data_insert['account_amount'] = $value['account_amount'];
				$data_insert['account_chart_id'] = $value['account_chart_id'];
				$this->db->insert('coop_account_detail', $data_insert);
			}
		}
		return $account_id;
	}
}
