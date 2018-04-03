<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_libraries extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		# Load libraries
		//$this->load->library('parser');
		$this->load->helper(array('html', 'url'));

		$this->menu_path_stack = array();
		$this->is_menu_path_found = FALSE;
	}

	public function template($bodyFile='', $arr_data=array()){
		$file = basename($_SERVER['PHP_SELF']);

		$this->db->select(array('coop_img','coop_name_en','coop_name_th'));
		$this->db->from('coop_profile');
		$this->db->where("profile_id = '1'");
		$profile = $this->db->get()->result_array();

		$name_coop = array(
			"title_name" => $profile[0]["coop_name_th"],
			"title_admin_manage" => "ผู้ดูแลระบบ",
			"file" => $file
		);
		$arr_title['name_coop'] = $name_coop;

		if($bodyFile == 'login'){
            $arr_title['title'] = 'เข้าสู่ระบบ';
			$arr_title['body_class'] = 'login-page';
            $this->load->view('template/template_login_header', $arr_title);
            $this->load->view($bodyFile, $arr_data);//file view show body
			$this->load->view('template/template_login_footer');
		}else{
			$this->db->select('*');
			$this->db->from('coop_user');
			$this->db->where("user_id = '".@$_SESSION["USER_ID"]."' AND user_status = 1");
			$user = $this->db->get()->result_array();
			$user = $user[0];

			$permissions = array();
			$this->db->select('*');
			$this->db->from('coop_user_permission');
			$this->db->where("user_id = '".@$_SESSION["USER_ID"]."'");
			$permission_arr = $this->db->get()->result_array();
			foreach($permission_arr as $key => $value) {
				$permissions[$value["menu_id"]] = TRUE;
			}

			if($user) {
				$arr_title['user'] = $user;
				$arr_title['permissions'] = $permissions;
			}else {
				header("location: ".PROJECTPATH."/auth?return_url=".urlencode($_SERVER["REQUEST_URI"]));
				exit();
			}
			$menus = $this->get_menu_arr();

			$self_path = explode("?",$_SERVER["REQUEST_URI"]);
			$self_path = $self_path[0];
				
			$current_path = ($self_path == PROJECTPATH."/main_menu" ? (empty($_GET["section"]) ? "" : PROJECTPATH."/main_menu?section=".$_GET["section"]) : str_replace('/index.php','',$self_path));
			
			$current_path_arr = explode('/',$current_path);
			
			if(count($current_path_arr)>1){
				$current_path = '/'.$current_path_arr[1].'/'.$current_path_arr[2];
				$current_path .= (@$current_path_arr[3])?'/'.$current_path_arr[3]:'';
			}
			//echo $current_path;
			
			$menu_id = $this->get_menu_id($menus, $current_path);
			//echo"<pre>";print_r($menu_id);echo"</pre>";//exit;
			$menu_paths = $this->get_menu_path($menus, $menu_id);
			
			if($menu_id == -1){					
				if(count($current_path_arr)>1){
					$current_path = '/'.$current_path_arr[1].'/'.$current_path_arr[2];
				}
				$menu_id = $this->get_menu_id($menus, $current_path);
				$menu_paths = $this->get_menu_path($menus, $menu_id);
			}
			
			$arr_title['current_path'] = $current_path;
			$arr_title['menu_id'] = $menu_id;
			$arr_title['menu_paths'] = $menu_paths;
			$arr_data['menu_paths'] = $menu_paths;
				
			$arr_title['menus'] = $menus;
			$arr_data['menus'] = $menus;

			$arr_title['side_menus'] = $menus;
			$arr_data['side_menus'] = $menus;

			$arr_title['title'] = 'Free Trade';
			$arr_title['body_class'] = 'hold-transition skin-blue fixed sidebar-mini layout layout-header-fixed';
            $this->load->view('template/template_header', $arr_title);
			$this->load->view('template/template_header_body', $arr_title);
            $this->load->view($bodyFile, $arr_data);//file view show body
            $this->load->view('template/template_footer');
		}
	}

	function get_menu_arr(){
		$menus = array(
			array("id" => 1, "name" =>"ระบบสมาชิก", "url" => PROJECTPATH."/main_menu?section=member", "icon" => "icon-briefcase", "img" => "member.png", "submenus" =>
				array(
					array("id" => 36, "name" => "ข้อมูลสมาชิก", "url" => PROJECTPATH."/manage_member_share", "icon" => "", "img" => "member.png"),
					array("id" => 41, "name" => "ผู้รับผลประโยชน์", "url" => PROJECTPATH."/beneficiary", "icon" => "", "img" => "gain.png"),
					array("id" => 39, "name" => "ลาออก", "url" => PROJECTPATH."/resignation", "icon" => "", "img" => "close.png"),
					array("id" => 39, "name" => "อนุมัติการลาออก", "url" => PROJECTPATH."/resignation/resignation_approve", "icon" => "", "img" => "retire.png"),
					array("id" => 83, "name" => "มองเห็นเงินเดือนและเงินอื่นๆ", "url" => "", "icon" => "", "img" => "document_cancel.png", "hidden"=>"hidden")
				)),
			array("id" => 87, "name" =>"ระบบหุ้น", "url" => PROJECTPATH."/main_menu?section=share", "icon" => "icon-briefcase", "img" => "coop_detail.png", "submenus" =>
					array(
						array("id" => 48, "name" => "ซื้อหุ้นเพิ่มพิเศษ", "url" => PROJECTPATH."/buy_share", "icon" => "", "img" => "register_type.png"),
						array("id" => 49, "name" => "ยกเลิกการซื้อหุ้นพิเศษ", "url" => PROJECTPATH."/buy_share/cancel_receipt", "icon" => "", "img" => "document_cancel.png"),
						array("id" => 53, "name" => "เพิ่ม/ลดหุ้น", "url" => PROJECTPATH."/increase_share", "icon" => "", "img" => "credits.png"),
						array("id" => 54, "name" => "ยกเลิกการเพิ่ม/ลดหุ้น", "url" => PROJECTPATH."/increase_share/cancel_increase_share", "icon" => "", "img" => "document_cancel.png")
					)
			),
			array("id" => 2, "name" => "ระบบเงินฝาก", "url" => PROJECTPATH."/main_menu?section=deposit", "icon" => "icon-edit", "img" => "money.png" , "submenus" =>
				array(
					array("id" => 47, "name" => "บัญชีเงินฝาก", "url" => PROJECTPATH."/save_money", "icon" => "", "img" => "rate of salary.png")
				)),
			array("id" => 57, "name" => "ระบบสินเชื่อ", "url" => PROJECTPATH."/main_menu?section=loan", "icon" => "icon-list", "img" => "credits.png","submenus" =>
				array(
					array("id" => 58, "name" => "การกู้เงิน", "url" => PROJECTPATH."/loan", "icon" => "", "img" => "credits.png"),
					array("id" => 59, "name" => "ยกเลิกการกู้เงิน", "url" => PROJECTPATH."/loan/loan_cancel", "icon" => "", "img" => "document_cancel.png"),
					array("id" => 58, "name" => "อนุมัติการกู้เงิน", "url" => PROJECTPATH."/loan/loan_approve", "icon" => "", "img" => "register_type.png"),
					array("id" => 60, "name" => "โอนเงินกู้ ", "url" => PROJECTPATH."/loan/loan_transfer", "icon" => "", "img" => "money_type.png"),
					array("id" => 61, "name" => "ยกเลิกการโอนเงินกู้ ", "url" => PROJECTPATH."/loan/loan_transfer_cancel", "icon" => "", "img" => "retire.png")
				)),
			array("id" => 6, "name" => "ระบบบัญชีและการเงิน", "url" => PROJECTPATH."/main_menu?section=admin.account", "icon" => "icon-bar-chart", "img" => "account.png","submenus" =>
				array(
					array("id" => 63, "name" => "เรียกเก็บประจำเดือน", "url" => PROJECTPATH."/finance/finance_month", "icon" => "", "img" => "calendar_work.png"),
					array("id" => 46, "name" => "รายการชำระอื่นๆ ", "url" => PROJECTPATH."/cashier", "icon" => "", "img" => "finance.png"),
					array("id" => 65, "name" => "รายการชำระรายเดือน ", "url" => PROJECTPATH."/cashier/cashier_month", "icon" => "", "img" => "list_code.png"),
					array("id" => 86, "name" => "รายการซื้อ ", "url" => PROJECTPATH."/coop_buy", "icon" => "", "img" => "list_code.png"),
					array("id" => 88, "name" => "ยกเลิกรายการซื้อ", "url" => PROJECTPATH."/coop_buy/coop_buy_cancel", "icon" => "", "img" => "retire.png"),
					array("id" => 89, "name" => "บันทึกรายการบัญชี", "url" => PROJECTPATH."/account", "icon" => "", "img" => "coop_year.png"),
					array("id" => 90, "name" => "สมุดรายวันทั่วไป", "url" => PROJECTPATH."/account/account_day_book", "icon" => "", "img" => "report.png"),
					array("id" => 91, "name" => "รายงานบัญชีแยกประเภท", "url" => PROJECTPATH."/account/account_chart_report", "icon" => "", "img" => "report.png"),
					array("id" => 92, "name" => "รายงานงบทดลอง", "url" => PROJECTPATH."/account/coop_account_experimental_budget", "icon" => "", "img" => "report.png"),
					array("id" => 93, "name" => "รายงานงบดุล", "url" => PROJECTPATH."/account/coop_account_balance_sheet", "icon" => "", "img" => "report.png"),
					array("id" => 94, "name" => "รายงานงบกำไรขาดทุน", "url" => PROJECTPATH."/account/coop_account_profit_lost_statement", "icon" => "", "img" => "report.png")
				)),
			array("id" => 7, "name" => "ระบบปันผลเฉลี่ยคืน", "url" => PROJECTPATH."/main_menu?section=report.dividend", "icon" => "icon-folder-open", "img" => "dividend.png", "submenus" => array(
				array("id" => 84, "name" => "ระบบปันผลเฉลี่ยคืน", "url" => PROJECTPATH."/average_dividend", "icon" => "", "img" => "dividend.png"),
				array("id" => 85, "name" => "อนุมัติการปันผลเฉลี่ยคืน", "url" => PROJECTPATH."/average_dividend/approve", "icon" => "", "img" => "register_type.png")
			)),
			array("id" => 71, "name" => "รายงาน", "url" => PROJECTPATH."/main_menu?section=report", "icon" => "icon-folder-open", "img" => "report.png", "submenus" => array(
				array("id" => 72, "name" => "สมาชิก", "url" => PROJECTPATH."/main_menu?section=report.member", "icon" => "icon-folder-open", "img" => "member.png", "submenus" => array(
					array("id" => 76, "name" => "รายงานสมาชิกลาออก", "url" => PROJECTPATH."/report_member_data/coop_report_member_retire", "icon" => "icon-folder-open", "img" => "retire.png"),
					array("id" => 81, "name" => "รายงานสรุปเข้าออก", "url" =>  PROJECTPATH."/report_member_data/coop_report_member_in_out", "icon" => "icon-folder-open", "img" => "member.png")
				)),
				array("id" => 73, "name" => "สินเชื่อ", "url" => PROJECTPATH."/main_menu?section=report.loan", "icon" => "icon-folder-open", "img" => "credits.png", "submenus" => array(
					array("id" => 77, "name" => "รายงานสินเชื่อแยกประเภท", "url" => PROJECTPATH."/report_loan_data/coop_report_loan", "icon" => "icon-folder-open", "img" => "coop_detail.png"),
					array("id" => 64, "name" => "รายละเอียดยอดลูกหนี้คงเหลือ", "url" => PROJECTPATH."/report_loan_data/coop_finance_year", "icon" => "", "img" => "calendar_work.png")
				)),
				array("id" => 75, "name" => "ทุนเรือนหุ้น", "url" => PROJECTPATH."/main_menu?section=report.share", "icon" => "icon-folder-open", "img" => "register_type.png", "submenus" => array(
					array("id" => 82, "name" => "รายงานสมาชิกเปลี่ยนแปลงค่าหุ้น", "url" => PROJECTPATH."/report_share_data/coop_report_change_share", "icon" => "icon-folder-open", "img" => "register_type.png")
				))
			)),
			array("id" => 103, "name" => "ระบบงานพัสดุ", "url" => PROJECTPATH."/main_menu?section=facility", "icon" => "icon-archive", "img" => "bank.png", "submenus" => array(
				array("id" => 119, "name" => "ครุภัณฑ์", "url" => PROJECTPATH."/main_menu?section=admin.facility_store", "icon" => "icon-briefcase", "img" => "data.png", "submenus" => array(
					array("id" => 120, "name" => "ลงทะเบียนครุภัณฑ์", "url" => PROJECTPATH."/facility", "icon" => "", "img" => "calendar_work.png"),
					array("id" => 121, "name" => "เบิกครุภัณฑ์", "url" => PROJECTPATH."/facility/take_facility", "icon" => "", "img" => "list_code.png")
				)),
				array("id" => 122, "name" => "จัดซื้อจัดจ้าง", "url" => PROJECTPATH."/main_menu?section=admin.facility_purchase", "icon" => "icon-briefcase", "img" => "data.png", "submenus" => array(

				)),
			)),	
			array("id" => 8, "name" => "เว็บไซต์และแอพพลิเคชั่น 	", "url" => "", "icon" => "icon-globe", "img" => "web.png"),
			array("id" => 10, "name" => "ตั้งค่าระบบ", "url" => PROJECTPATH."/main_menu?section=admin", "icon" => "icon-cog", "img" => "admin.png", "submenus" => array(
				array("id" => 11, "name" => "ข้อมูลพื้นฐาน", "url" => PROJECTPATH."/main_menu?section=admin.data", "icon" => "icon-briefcase", "img" => "data.png", "submenus" => array(
					array("id" => 12, "name" => "ข้อมูลสหกรณ์", "url" => PROJECTPATH."/setting_basic_data/coop_detail", "icon" => "", "img" => "coop_detail.png"),
					array("id" => 99, "name" => "ข้อมูลลายเซ็นต์", "url" => PROJECTPATH."/setting_basic_data/coop_signature", "icon" => "", "img" => "list_code.png"),
					array("id" => 19, "name" => "ข้อมูลที่อยู่", "url" => PROJECTPATH."/setting_basic_data/coop_address", "icon" => "", "img" => "adddress.png"),
					array("id" => 15, "name" => "ธนาคาร", "url" => PROJECTPATH."/setting_basic_data/coop_bank", "icon" => "", "img" => "bank.png"),
					array("id" => 20, "name" => "กำหนดผู้ใช้งาน", "url" => PROJECTPATH."/setting_basic_data/coop_user", "icon" => "", "img" => "set_user.png"),
					array("id" => 17, "name" => "ประเภทเงินทำรายการ", "url" => PROJECTPATH."/setting_basic_data/coop_transactions_new", "icon" => "", "img" => "money_type.png")
				)),
				array("id" => 22, "name" => "ระบบสมาชิก", "url" => PROJECTPATH."/main_menu?section=admin.member", "icon" => "icon-user", "img" => "member.png", "submenus" => array(
					array("id" => 23, "name" => "คำนำหน้าชื่อ", "url" => PROJECTPATH."/setting_member_data/coop_prename", "icon" => "", "img" => "prename.png"),
					array("id" => 24, "name" => "สังกัดสมาชิก", "url" => PROJECTPATH."/setting_member_data/coop_group", "icon" => "", "img" => "belong_member.png"),
					array("id" => 26, "name" => "ประเภทการสมัคร", "url" => PROJECTPATH."/setting_member_data/coop_register_type", "icon" => "", "img" => "register_type.png"),
					array("id" => 28, "name" => "ความสัมพันธ์ผู้รับโอน", "url" => PROJECTPATH."/setting_member_data/coop_mem_relation", "icon" => "", "img" => "relationship.png"),
					array("id" => 29, "name" => "สาเหตุการลาออก", "url" => PROJECTPATH."/setting_member_data/coop_cause_quite", "icon" => "", "img" => "cause_quite.png"),
					array("id" => 29, "name" => "การลาออก", "url" => PROJECTPATH."/setting_member_data/coop_quite", "icon" => "", "img" => "cause_quite.png"),
					array("id" => 30, "name" => "ประเภทสมาชิก", "url" => PROJECTPATH."/setting_member_data/coop_member", "icon" => "", "img" => "member_type.png"),
					array("id" => 34, "name" => "อายุเกษียณ", "url" => PROJECTPATH."/setting_member_data/coop_retire", "icon" => "", "img" => "retire.png"),
					array("id" => 52, "name" => "รอบการอนุมัติ", "url" => PROJECTPATH."/setting_member_data/coop_approval_cycle", "icon" => "", "img" => "calendar_work.png")
				)),
				array("id" => 22, "name" => "ระบบหุ้น", "url" => PROJECTPATH."/main_menu?section=admin.share", "icon" => "icon-folder-open", "img" => "coop_detail.png", "submenus" => array(					
					array("id" => 35, "name" => "เกณฑ์การถือหุ้นแรกเข้า", "url" => PROJECTPATH."/setting_share_data/coop_share_rule", "icon" => "", "img" => "share.png")
				)),
				array("id" => 62, "name" => "ระบบเงินฝาก", "url" => PROJECTPATH."/main_menu?section=admin.coop_deposit", "icon" => "icon-usd", "img" => "money.png", "submenus" => array(
					array("id" => 100, "name" => "ตั้งค่าเงินฝาก", "url" => PROJECTPATH."/setting_deposit_data/coop_deposit_setting", "icon" => "", "img" => "money.png"),
					array("id" => 101, "name" => "ประเภทเงินฝาก", "url" => PROJECTPATH."/setting_deposit_data/coop_deposit_type_setting", "icon" => "", "img" => "money.png")
				)),
				array("id" => 50, "name" => "ระบบบัญชี", "url" => PROJECTPATH."/main_menu?section=admin.account_setting", "icon" => "icon-credit-card", "img" => "account.png", "submenus" => array(
					array("id" => 95, "name" => "ผังบัญชี", "url" => PROJECTPATH."/setting_account_data/coop_account_chart", "icon" => "", "img" => "list_code.png"),
					array("id" => 51, "name" => "รายการชำระ", "url" => PROJECTPATH."/setting_account_data/coop_account_receipt", "icon" => "", "img" => "finance.png"),
					array("id" => 96, "name" => "รายการซื้อ", "url" => PROJECTPATH."/setting_account_data/coop_account_buy", "icon" => "", "img" => "finance.png")
				)),
				array("id" => 55, "name" => "ระบบสินเชื่อ", "url" => PROJECTPATH."/main_menu?section=admin.credit_setting", "icon" => "icon-pencil-square-o", "img" => "coop_year.png", "submenus" => array(
					array("id" => 56, "name" => "เงื่อนไขการกู้เงิน", "url" => PROJECTPATH."/setting_credit_data/coop_term_of_loan", "icon" => "", "img" => "finance.png"),
					array("id" => 80, "name" => "ตั้งค่าเหตุผลการกู้เงิน", "url" => PROJECTPATH."/setting_credit_data/coop_loan_reason", "icon" => "", "img" => "finance6.png")
				)),
				array("id" => 104, "name" => "ระบบงานพัสดุ", "url" => PROJECTPATH."/main_menu?section=admin.facility_setting", "icon" => "icon-briefcase", "img" => "bank.png", "submenus" => array(
					array("id" => 105, "name" => "ปีงบประมาณ", "url" => PROJECTPATH."/setting_facility_data/budget_year", "icon" => "", "img" => "calendar_work.png"),
					array("id" => 106, "name" => "ประเภทการขอ", "url" => PROJECTPATH."/setting_facility_data/request_type", "icon" => "", "img" => "list_code.png"),
					array("id" => 107, "name" => "กลุ่มพัสดุ", "url" => PROJECTPATH."/setting_facility_data/facility_group", "icon" => "", "img" => "belong_member.png"),
					array("id" => 108, "name" => "ประเภทพัสดุ", "url" => PROJECTPATH."/setting_facility_data/facility_type", "icon" => "", "img" => "data.png"),
					array("id" => 109, "name" => "พัสดุหลัก", "url" => PROJECTPATH."/setting_facility_data/facility_main", "icon" => "", "img" => "document.png"),
					array("id" => 110, "name" => "เหตุผลการใช้งาน", "url" => PROJECTPATH."/setting_facility_data/usage_reason", "icon" => "", "img" => "member_type.png"),
					array("id" => 111, "name" => "วิธีการจัดซื้อ", "url" => PROJECTPATH."/setting_facility_data/means_purchase", "icon" => "", "img" => "list_code.png"),
					array("id" => 112, "name" => "ประเภทเงิน", "url" => PROJECTPATH."/setting_facility_data/type_money", "icon" => "", "img" => "money.png"),
					array("id" => 113, "name" => "หน่วยงานภายใน", "url" => PROJECTPATH."/setting_facility_data/department", "icon" => "", "img" => "position.png"),
					array("id" => 114, "name" => "บุคลากร", "url" => PROJECTPATH."/setting_facility_data/personnel", "icon" => "", "img" => "prename.png"),
					array("id" => 115, "name" => "ร้านค้า", "url" => PROJECTPATH."/setting_facility_data/seller", "icon" => "", "img" => "set_device.png"),
					array("id" => 116, "name" => "ประเภทหลักฐาน", "url" => PROJECTPATH."/setting_facility_data/type_evidence", "icon" => "", "img" => "report.png"),
					array("id" => 117, "name" => "ประเภทหลักประกัน", "url" => PROJECTPATH."/setting_facility_data/type_guarantee", "icon" => "", "img" => "register_type.png"),
					array("id" => 118, "name" => "ค่าเสื่อมราคา", "url" => PROJECTPATH."/setting_facility_data/depreciation", "icon" => "", "img" => "money_type.png"),
				))
			)),
			array("id" => 98, "name" => "แจ้งปัญหาและข้อเสนอแนะ", "url" => PROJECTPATH."/report_problem_data/report_problem", "icon" => "icon-comments", "img" => "app.png")
		);
		return $menus;
	}

	function get_menu_id($menus, $url) {
		$id = -1;		
		foreach($menus as $menu) {			
			if($menu["url"] == $url && $url != '') {	
				return $menu["id"];				
			}
			else if(!empty($menu["submenus"]) && $id == -1) {
				$id = $this->get_menu_id($menu["submenus"], $url);						
			}
		}

		return $id;
	}

	function get_menu($menus, $url) {
		$_menu = array();
		foreach ($menus as $menu) {
			if ($menu["url"] == $url) {
				return $menu;
			} else if (!empty($menu["submenus"]) && empty($_menu)) {
				$_menu = $this->get_menu($menu["submenus"], $url);
			}
		}
		return $_menu;
	}

	function get_menu_path($menus, $id, $is_first = TRUE) {

		if($is_first) {
			$this->menu_path_stack = array();
			$this->is_menu_path_found = FALSE;
		}

		foreach($menus as $menu) {
			if($this->is_menu_path_found == FALSE) {
				array_push($this->menu_path_stack, $menu);
				if($menu["id"] == $id) {
					$this->is_menu_path_found = TRUE;
					return $this->menu_path_stack;
				}
				else {
					if(empty($menu["submenus"])) {
						array_pop($this->menu_path_stack);
					}
					else {
						$result = $this->get_menu_path($menu["submenus"], $id, FALSE);
						if($this->is_menu_path_found != TRUE) {
							array_pop($this->menu_path_stack);
						}
					}
				}
			}
		}

		return $this->menu_path_stack;
	}
}
