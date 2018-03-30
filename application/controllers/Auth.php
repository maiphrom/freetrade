<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	function __construct()
	{
		parent::__construct();

	}
	public function index()
	{
		if(@$_SESSION['USER_ID']!=''){
			header("location: main_menu");
		}else {
			if ($this->input->post()) {

				$this->db->select(array('user_id', 'user_name'));
				$this->db->from('coop_user');
				$this->db->where("username = '" . $this->input->post('username') . "'");
				$this->db->where("password = '" . $this->input->post('password') . "'");
				$this->db->where("user_status = '1'");
				$user = $this->db->get()->result_array();

				if (!empty($user)) {
					/*@$token = @date("YmdHis") . $user[0]["user_id"] .  @random_char(10);
                    echo"<pre>";print_r($user);exit;

                    $data = array(
                        'user_token' => $token
                    );

                    $this->db->where('user_id', $user_id);
                    $this->db->update('coop_user', $data);*/
					$user_id = (int)@$user[0]["user_id"];
					$user_name = @$user[0]["user_name"];
					$_SESSION['USER_ID'] = $user_id;
					$_SESSION['USER_NAME'] = $user_name;
					
					$this->db->select(array('*'));
					$this->db->from('coop_profile');
					$this->db->where("profile_id = '1'");
					$profile = $this->db->get()->result_array();
					
					$_SESSION['COOP_NAME'] = $profile[0]['coop_name_th'];
					$_SESSION['COOP_NAME_EN'] = $profile[0]['coop_name_en'];
					$_SESSION['COOP_IMG'] = $profile[0]['coop_img'];
					
					//$this->session->USER_ID = $user_id;

					header("location: " . (empty($_GET["return_url"]) ? "main_menu" : $_GET["return_url"]));
					exit();
				}

				$err_msg = "ชื่อผู้ใช้/รหัสผ่าน ไม่ถูกต้อง";
			}
			$this->session->sess_destroy();
			$arr_data = array();
			$this->db->select(array('coop_img', 'coop_name_en', 'coop_name_th'));
			$this->db->from('coop_profile');
			$this->db->where("profile_id = '1'");
			$profile = $this->db->get()->result_array();
			//echo"<pre>";print_r($profile);echo"</pre>";
			$arr_data['profile']['coop_img'] = $profile[0]['coop_img'];
			$arr_data['profile']['coop_name_en'] = $profile[0]['coop_name_en'];
			$arr_data['profile']['coop_name_th'] = $profile[0]['coop_name_th'];
			$this->libraries->template('login', $arr_data);
		}
	}
}
