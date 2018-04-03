<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_basic_data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function coop_detail()
	{
		$arr_data = array();
		$data_get = $this->input->get();
		$arr_data['data_get'] = $data_get;

		$this->db->select(array('*'));
		$this->db->from('coop_profile');
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		$arr_data['row'] = @$row[0]; 
		
		$this->libraries->template('setting_basic_data/coop_detail',$arr_data);
	}
	
	public function coop_detail_save()
	{
		$data_insert = array();
		$profile_id = @$_POST["profile_id"];
		
		$this->db->select(array('*'));
		$this->db->from('coop_profile');
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		$row = @$row[0]; 
			
		if($_FILES["coop_img"]["name"]){			
			@unlink( PATH . "/images/coop_profile/{$row['coop_img']}");	
			
			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/images/coop_profile/";
			$new_file_name = $this->center_function->create_file_name($output_dir,$_FILES["coop_img"]['name']);
			copy($_FILES["coop_img"]['tmp_name'], $output_dir.$new_file_name);
			$data_insert['coop_img'] = @$new_file_name;
			
		}
		/*if(!empty($_FILES["signature_1"]["tmp_name"])){
			@unlink( PATH . "/images/coop_profile/{$row['signature_1']}");	
			
			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/images/coop_profile/";
			$new_file_name = $this->center_function->create_file_name($output_dir,$_FILES["signature_1"]['name']);
			copy($_FILES["signature_1"]['tmp_name'], $output_dir.$new_file_name);
			$data_insert['signature_1'] = @$new_file_name;
		}

		if(!empty($_FILES["signature_2"]["tmp_name"])){
			@unlink( PATH . "/images/coop_profile/{$row['signature_2']}");	
			
			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/images/coop_profile/";
			$new_file_name = $this->center_function->create_file_name($output_dir,$_FILES["signature_2"]['name']);
			copy($_FILES["signature_2"]['tmp_name'], $output_dir.$new_file_name);
			$data_insert['signature_2'] = @$new_file_name;			
		}
		*/
		
		$data_insert['coop_name_th'] = @$_POST["coop_name_th"];
		$data_insert['coop_name_en'] = @$_POST["coop_name_en"];
		$data_insert['address1'] = @$_POST["address1"];
		$data_insert['address2'] = @$_POST["address2"];
		$data_insert['tel'] = @$_POST["tel"];
		$data_insert['fax'] = @$_POST["fax"];
		$data_insert['email'] = @$_POST["email"];
		//$data_insert['president_name'] = @$_POST["president_name"];
		//$data_insert['manager_name'] = @$_POST["manager_name"];
		//$data_insert['auditor_name'] = @$_POST["auditor_name"];
		$data_insert['updatedate']= date("Y-m-d H:i:s");
		
		$this->db->where('profile_id', $profile_id);
		$this->db->update('coop_profile', $data_insert);
		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_detail' </script>";            
	}
	
	public function coop_address()
	{
		$arr_data = array();

		$this->db->select('COUNT(coop_province.province_id) as _c');
		$this->db->from('coop_province');
		$this->db->join('coop_amphur', 'coop_province.province_id = coop_amphur.province_id', 'inner');
		$this->db->join('coop_district', 'coop_amphur.amphur_id = coop_district.amphur_id', 'inner');
		$this->db->join('coop_zipcode', 'coop_district.district_code = coop_zipcode.district_code', 'left');
		$count = $this->db->get()->result_array();

		$num_rows = $count[0]["_c"] ;
		$per_page = 10 ;
		$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
		$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

		$page_start = (($per_page * $page) - $per_page);
		if($page_start==0){ $page_start = 1;}

		$this->db->select('*');
		$this->db->from('( SELECT d1.*,d2.amphur_id,d2.amphur_name,d3.district_id,d3.district_name,d4.id,d4.zipcode, ROW_NUMBER() OVER (ORDER BY d1.province_id DESC) as row FROM coop_province AS d1 INNER JOIN coop_amphur  AS d2
             ON  (d1.province_id = d2.province_id) 
             INNER JOIN coop_district  AS d3
             ON  (d2.amphur_id = d3.amphur_id)
             LEFT JOIN coop_zipcode  AS d4
             ON  (d3.district_code = d4.district_code) ) a');
	 
		$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
		$this->db->order_by('a.province_id DESC');
		$rs = $this->db->get()->result_array();
		//print_r($this->db->last_query());exit;

		$i = $page_start;


		$arr_data['num_rows'] = $num_rows;
		$arr_data['paging'] = $paging;
		$arr_data['rs'] = $rs;
		$arr_data['i'] = $i;
		
		
		$this->db->select(array('*'));
		$this->db->from('coop_province');
		$this->db->order_by('province_name ASC');
		$rs_province = $this->db->get()->result_array();
		$arr_data['rs_province'] = $rs_province;
		
		if(@$_GET['district_id']){
			$this->db->select(array('*'));
			$this->db->from('coop_district');
			$this->db->where("district_id = '{$_GET['district_id']}'");
			$this->db->limit(1);
			$row_district = $this->db->get()->result_array();
			$arr_data['row_district'] = @$row_district[0]; 
			//print_r($this->db->last_query());exit;
		}
		
		$this->db->select(array('*'));
		$this->db->from('coop_amphur');
		$rs_amphur = $this->db->get()->result_array();
		$arr_data['rs_amphur'] = @$rs_amphur; 		
		
		if(@$_GET['zipcode_id']){
			$this->db->select(array('*'));
			$this->db->from('coop_zipcode');
			$this->db->where("id = '{$_GET['zipcode_id']}'");
			$this->db->limit(1);
			$row_zipcode = $this->db->get()->result_array();
			$arr_data['row_zipcode'] = @$row_zipcode[0]; 
		}
		
		$this->libraries->template('setting_basic_data/coop_address',$arr_data);
	}
	
	function search_coop_address(){
			$this->db->select(array('*'));
			$this->db->from('coop_province');
			$this->db->join('coop_amphur', 'coop_province.province_id = coop_amphur.province_id', 'inner');
			$this->db->join('coop_district', 'coop_amphur.amphur_id = coop_district.amphur_id', 'inner');
			$this->db->join('coop_zipcode', 'coop_district.district_code = coop_zipcode.district_code', 'left');
		
			$this->db->where("
				(
					coop_province.province_name LIKE '%".$this->input->post("search")."%'
					OR coop_province.province_name LIKE '".$this->input->post("search")."%'
					OR coop_province.province_name LIKE '%".$this->input->post("search")."'
				 )

			  OR 
				 (
					coop_amphur.amphur_name LIKE '%".$this->input->post("search")."%'
					OR coop_amphur.amphur_name LIKE '".$this->input->post("search")."%'
					OR coop_amphur.amphur_name LIKE '%".$this->input->post("search")."'
				 )
			  OR 
				 (
					coop_district.district_name LIKE '%".$_POST["search"]."%'
					OR coop_district.district_name LIKE '".$_POST["search"]."%'
					OR coop_district.district_name LIKE '%".$_POST["search"]."'
				 )
			");
			$this->db->order_by('coop_province.province_id DESC');
			//$this->db->limit(5);
			$rs = $this->db->get()->result_array();
			$output = '';
           if(!empty($rs)){  
				$i= 1; 
				foreach($rs as $key => $row){
                     $output .= '  

							<tr>  
								 <td scope="row">'.$i.'</td>  
								 <td>'.$row["province_name"].'</td>  
								 <td>'.$row['amphur_name'].'</td>  
								 <td>'.$row['district_name'].'</td>  
								 <td>'.$row['zipcode'].'</td>
								 <td><a href="?act=add&province_id='.$row["province_id"].'&amphur_id='.$row['amphur_id'].'&district_id='.$row["district_id"].'&zipcode_id='.$row["id"].'">แก้ไข</a> | <span id="'.$row['id'].'" class="text-del del">ลบ</span></td>
							</tr>  
					   '; 
               $i++; 
			   }
                echo $output;  
           }else{
                $output .= '  

                          <tr>  
								 <td align="center" colspan="6"><h1>ไม่มีพบผลการค้นหา!</h1></td>  
							</tr>  
                     ';
                echo $output;  
           }  
		   exit;
	}
	
	function del_coop_address(){	
		$table = @$_POST['table'];
		$table_sub = @$_POST['table_sub'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];

		// del จังหวัด
		$id_zipcode = @$_POST['id_zipcode'];
		// del จังหวัด
        
        if (!empty($id_zipcode)) {	
			$this->db->select(array('*'));
			$this->db->from('coop_zipcode');
			$this->db->where("id = '{$id_zipcode}'");
			$rs_zipcode = $this->db->get()->result_array();
			$district_code = @$rs_zipcode[0]['district_code'];
			
			$this->db->select(array('*'));
			$this->db->from('coop_district');
			$this->db->where("district_code = '{$district_code}'");
			$rs_district = $this->db->get()->result_array();
			$amphur_id = @$rs_district[0]['amphur_id'];
			
			$this->db->select('COUNT(amphur_id) as _c');
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '{$amphur_id}'");
			$count = $this->db->get()->result_array();
			$count_amphur = @$count[0]["_c"];			

			$this->db->select(array('*'));
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '{$amphur_id}'");
			$rs_amphur = $this->db->get()->result_array();
			$province_id = @$rs_amphur[0]['province_id'];
			
			$this->db->select('COUNT(province_id) as _c');
			$this->db->from('coop_district');
			$this->db->where("province_id = '{$province_id}'");
			$count = $this->db->get()->result_array();
			$count_province = @$count[0]["_c"];
			
			//DELETE
			$this->db->where('id', $id_zipcode );
			$this->db->delete('coop_zipcode');
			
			$this->db->where('district_code', $district_code );
			$this->db->delete('coop_district');					
			
			if($count_amphur <= 1){
				$this->db->where('amphur_id', $amphur_id );
				$this->db->delete('coop_amphur');
			}		
			
			if($count_province <= 1){
				$this->db->where('province_id', $province_id );
				$this->db->delete('coop_province');
			}
			
			$this->center_function->toast("ลบเรียบร้อยแล้ว");
			echo true;
        }else{
			echo false;
		}	
		
	}
	
	function select_coop_address(){
		
		// จังหวัด
		$province_id = $_POST['province_id'];

		if ($province_id) {
			$this->db->select(array('*'));
			$this->db->from('coop_amphur');
			$this->db->where("province_id = '{$province_id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['rs'] = @$rs; 	
		
	        echo "<option value=''> - เลือกอำเภอ - </option>";
	        if(!empty($rs)){
				foreach(@$rs as $key => $row){
					echo "<option value=".$row['amphur_id'].">".$row['amphur_name']."</option>";
				}
			}
		}
		// จังหวัด


         // อำเภอ
		$amphur_id = $_POST['amphur_id'];
		if ($amphur_id) {
			$this->db->select(array('*'));
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '{$amphur_id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['rs'] = @$rs; 
			
			echo "<option value=''> - เลือกตำบล - </option>";
			if(!empty($rs)){
				foreach(@$rs as $key => $row){
					echo "<option  value=".$row['district_id'].">".$row['district_name']."</option>";
				}
			}
		}
         // อำเภอ

		// ตำบล
		$district_id = $_POST['district_id'];
		if ($district_id) {
			$this->db->select(array('*'));
			$this->db->from('coop_district');
			$this->db->where("district_id = '{$district_id}'");
			$rs = $this->db->get()->result_array();
			$district_code = @$rs[0]['district_code']; 
			
			
			$this->db->select(array('*'));
			$this->db->from('coop_zipcode');
			$this->db->where("district_code = '{$district_code}'");
			$rs = $this->db->get()->result_array();
			$arr_data['rs'] = @$rs; 
			
			if(!empty($rs)){
				foreach(@$rs as $key => $row){
					 echo $row['zipcode'];
				}
			}
		}
		// ตำบล
			
	}
	
	public function coop_address_save()
	{
		$type = @$_POST["type"] ;
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;
		
		$data_insert_province = array();
		$data_insert_district = array();
		$data_insert_zipcode = array();
		// post จังหวัด
		$data_insert_province['province_name'] = @$_POST["province_name"];
		// post จังหวัด

		// post อำเภอ
		$data_insert_amphur['province_id'] = @$_POST["province_id"];
		$data_insert_amphur['amphur_name'] = @$_POST["amphur_name"];
		// post อำเภอ

		// post ตำบล
		$data_insert_district['amphur_id'] = @$_POST["amphur_id"];
		$data_insert_district['district_name'] = @$_POST["district_name"];
		$data_insert_zipcode['zipcode'] = @$_POST["zipcode"];
		// post ตำบล


		// edit
		$district_id = @$_POST["district_id"];
		$zipcode_id = @$_POST["zipcode_id"];

		$type = @$_POST["type"] ;
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;
		
		if($type_add == 'add'){
			if ($type == "province") {			
				$this->db->select('MAX(province_code) as _max');
				$this->db->from('coop_province');
				$max = $this->db->get()->result_array();
				$province_code = @$max[0]["_max"] + 1 ;
			
			  
				$data_insert_province['province_code'] = @$province_code;				
				$this->db->insert('coop_province', $data_insert_province);
				//print_r($this->db->last_query());exit;			
			}elseif ($type == "amphur") {

				$this->db->select('MAX(amphur_code) as _max');
				$this->db->from('coop_amphur');
				$max = $this->db->get()->result_array();
				$amphur_code = @$max[0]["_max"] + 1 ;
				
				$data_insert_amphur['amphur_code'] = @$amphur_code;				
				$this->db->insert('coop_amphur', $data_insert_amphur);
			}else{

				$this->db->select('MAX(district_code) as _max');
				$this->db->from('coop_district');
				$max = $this->db->get()->result_array();
				$district_code = @$max[0]["_max"] + 1 ;
				
				$data_insert_district['district_code'] = @$district_code;
				$data_insert_district['province_id'] = @$_POST["province_id"];					
				$this->db->insert('coop_district', $data_insert_district);
				
				$district_id = $this->db->insert_id();
			}
			

			if (!empty($data_insert_zipcode['zipcode'])) {
				
				$this->db->select('district_code');
				$this->db->from('coop_district');
				$this->db->where("district_id = '{$district_id}'");
				$rs = $this->db->get()->result_array();
				$district_code = @$rs[0]['district_code'];
				
				$data_insert_zipcode['district_code'] = @$district_code;				
				$this->db->insert('coop_zipcode', $data_insert_zipcode);
				
			}
		}else{
			
			$this->db->where('district_id', $district_id);
			$this->db->update('coop_district', $data_insert_district);	
		
			$this->db->where('id', $zipcode_id);
			$this->db->update('coop_zipcode', $data_insert_zipcode);
			//print_r($this->db->last_query());exit;	
			
		}

		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_address' </script>";      
	}
	
	public function coop_bank()
	{
		$arr_data = array();
		$id = @$_GET['id'];
		if(!empty($id)){
			$this->db->select(array('*'));
			$this->db->from('coop_bank');
			$this->db->where("bank_id = '{$id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0]; 	
		}else{	
			
			$x=0;
			$join_arr = array();
			$join_arr[$x]['table'] = '(SELECT coop_bank_branch.bank_id,count(coop_bank_branch.bank_id) as total from coop_bank_branch 
								GROUP BY coop_bank_branch.bank_id) AS coop_bank_branch';
			$join_arr[$x]['condition'] = 'coop_bank.bank_id = coop_bank_branch.bank_id';
			$join_arr[$x]['type'] = 'left';
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('coop_bank.*,coop_bank_branch.total');
			$this->paginater_all->main_table('coop_bank');
			$this->paginater_all->where("");
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('coop_bank.bank_id DESC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo $this->db->last_query();exit;
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit'], $_GET);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			
			$i = $row['page_start'];

			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $row['data'];
			$arr_data['i'] = $i;
		}
		$this->libraries->template('setting_basic_data/coop_bank',$arr_data);
	}
	
	public function coop_bank_save()
	{
		$data_insert = array();
		$bank_id      = @$_POST["bank_id"];
		
		$data_insert['bank_id']      = @$_POST["bank_id"];		
		$data_insert['bank_name']    = @$_POST["bank_name"];
		$data_insert['bank_code']    = @$_POST["bank_code"];

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;

		// เช็คซ้ำ		
		$this->db->select('*');
		$this->db->from('coop_bank');
		$this->db->where("bank_id = '{$bank_id}' AND bank_id != '{$id_edit}'");
		$rs = $this->db->get()->result_array();
		$obj = @$rs[0];
		
		//print_r($this->db->last_query());exit;
		// เช็คซ้ำ

		if ($obj) {
			  //toastDanger("รหัสสาขานี้มีอยู่ในระบบอยู่แล้วกรุณาเปลี่ยนใหม่");
			  $this->center_function->toastDanger("รหัสสาขานี้มีอยู่ในระบบอยู่แล้วกรุณาเปลี่ยนใหม่");
			  echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_bank' </script>";  
			  exit();
		}else{
			// add

			$table = "coop_bank";

			if ($type_add == 'add') {			
				$this->db->insert($table, $data_insert);
				$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

			// add
			}else{
			// edit
				$this->db->where('bank_id', $id_edit);
				$this->db->update($table, $data_insert);	
				$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

			// edit
			}

		}
		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_bank' </script>"; 

	}
	
	function del_coop_basic_data(){	
		$table = @$_POST['table'];
		$table_sub = @$_POST['table_sub'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];


		if (!empty($table_sub)) {
			$this->db->where($field, $id );
			$this->db->delete($table_sub);	
        }

		$this->db->where($field, $id );
		$this->db->delete($table);
		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		echo true;
		
	}
	
	public function coop_bank_branch()
	{
		$arr_data = array();
		$bank_id = @$_GET['bank_id'];
		$id = @$_GET['id'];
		if(!empty($bank_id)){	
			$this->db->select('*');
			$this->db->from('coop_bank');
			$this->db->where("bank_id  = '{$bank_id}' ");
			$rs_name = $this->db->get()->result_array();
			$bank_name = $rs_name[0]['bank_name'];
			
			$x=0;
			$join_arr = array();
			$join_arr[$x]['table'] = 'coop_amphur';
			$join_arr[$x]['condition'] = 'coop_bank_branch.amphur_id = coop_amphur.amphur_id';
			$join_arr[$x]['type'] = 'left';
			$x++;
			$join_arr[$x]['table'] = 'coop_province';
			$join_arr[$x]['condition'] = 'coop_bank_branch.province_id = coop_province.province_id';
			$join_arr[$x]['type'] = 'left';
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('coop_bank_branch.*,coop_amphur.amphur_name,coop_province.province_name');
			$this->paginater_all->main_table('coop_bank_branch');
			$this->paginater_all->where("bank_id  = '{$bank_id}'");
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('branch_id DESC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo $this->db->last_query();exit;
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit'], $_GET);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			
			$i = $row['page_start'];

			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $row['data'];
			$arr_data['i'] = $i;
			
			
			if (!empty($id)) {				
				$this->db->select(array('*'));
				$this->db->from('coop_bank_branch');
				$this->db->where("branch_id  = '{$id}' ");
				$rs = $this->db->get()->result_array();
				$arr_data['row'] = @$rs[0];
			}	
			$this->db->select(array('*'));
			$this->db->from('coop_province');
			$this->db->order_by('province_name ASC');
			$rs_province = $this->db->get()->result_array();
			$arr_data['rs_province'] = @$rs_province;

			$this->db->select(array('*'));
			$this->db->from('coop_amphur');
			$rs_amphur = $this->db->get()->result_array();
			$arr_data['rs_amphur'] = @$rs_amphur; 
			
		}
		$this->libraries->template('setting_basic_data/coop_bank_branch',$arr_data);
	}
	
	public function coop_bank_branch_save()
	{
		$data_insert = array();

		$bank_id =  @$_POST["bank_id"];
		$data_insert['bank_id'] =  @$_POST["bank_id"];
		$data_insert['branch_name'] = @$_POST["branch_name"];
		$data_insert['branch_code'] =  @$_POST["branch_code"];
		$data_insert['province_id'] = @$_POST["province_id"];
		$data_insert['amphur_id'] = @$_POST["amphur_id"];

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"];

		$table = "coop_bank_branch";

		if ($type_add == 'add') {			
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('branch_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

		// edit
		}
		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_bank_branch?bank_id=$bank_id' </script>";      
	}
	
	function search_bank(){
		$this->db->select(array('*'));
		$this->db->from('coop_bank_branch');
	
		$this->db->where("
				(  bank_id = '".$this->input->post("bank_id")."'
							  AND branch_name LIKE '%".$this->input->post("search")."%'
					)
			  OR
					(
							  bank_id = '".$this->input->post("bank_id")."'
							  AND branch_name LIKE '%".$this->input->post("search")."%'
					)
			  OR
					(
							bank_id = '".$this->input->post("bank_id")."'
							AND branch_name LIKE '%".$this->input->post("search")."%'
					
					)	
			");
		$rs = $this->db->get()->result_array();
		$output = '';
		if(!empty($rs)){  
			$i= 1; 
			foreach($rs as $key => $row){
				$this->db->select('*');
				$this->db->from('coop_amphur');
				$this->db->where("amphur_id  = '{$row["amphur_id"]}' ");
				$rs_amphur = $this->db->get()->result_array();
				$row_amphur = @$rs_amphur[0];
				
				$this->db->select('*');
				$this->db->from('coop_province');
				$this->db->where("province_id  = '{$row["province_id"]}' ");
				$rs_province = $this->db->get()->result_array();
				$row_province = @$rs_province[0];
				
			   $output .= '  

					 <tr> 
					  <th scope="row">'.@$row['branch_code'].'</th>
					  <td>'.@$row['branch_name'].'</td> 
					  <td>'.@$row_amphur['amphur_name'].'</td>
					  <td>'.@$row_province['province_name'].'</td> 
					  <td>
					  <a href="?act=add&id="'.@$row["branch_id"].'&bank_id='.@$row['bank_id'].'">แก้ไข</a> | 
					  <span id="'.@$row['branch_id'].'" class="text-del del">ลบ</span>
					  </td> 

					  </tr>
			   '; 
		   $i++; 
		   }
			echo $output;  
		}else{
			$output .= '  

					  <tr>  
							 <td align="center" colspan="6"><h1>ไม่มีพบผลการค้นหา!</h1></td>  
						</tr>  
				 ';
			echo $output;  
		}  
		exit;
	}
	
	public function coop_user()
	{
		$arr_data = array();
		
		$user_id = @$_GET["id"] ; 
		if($user_id){
			$this->db->select(array('*'));
			$this->db->from('coop_user');
			$this->db->where("user_id  = '{$user_id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
			
			
			$this->db->select(array('*'));
			$this->db->from('coop_user_permission');
			$this->db->where("user_id  = '{$user_id}' ");
			$rs2 = $this->db->get()->result_array();
			if(!empty($rs2)){
				foreach(@$rs2 as $key => $row2){
					$admin_permissions[$row2["menu_id"]] = TRUE;
				}
			}
			
			$arr_data['admin_permissions'] = @$admin_permissions;
		}else{
			$x=0;
			$join_arr = array();
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('*');
			$this->paginater_all->main_table('coop_user');
			$this->paginater_all->where("");
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('user_type_id, user_id DESC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo $this->db->last_query();exit;
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit'], $_GET);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			
			$i = $row['page_start'];

			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $row['data'];
			$arr_data['i'] = $i;	
		}
				
		
		$this->libraries->template('setting_basic_data/coop_user',$arr_data);
	}
	
	public function coop_user_save()
	{	
		$data_insert = array();

		$user_id = @$_POST["user_id"] ; 
		
		$data_insert['username'] = @$_POST["username"] ;
		$data_insert['password'] = @$_POST["password"];
		$data_insert['user_name'] = @$_POST["user_name"];
		$data_insert['user_department'] = @$_POST["user_department"];
		$data_insert['user_status'] = @$_POST["user_status"] ;
		$data_insert['employee_id'] =  @$_POST["employee_id"] ;
		$data_insert['user_email'] =  @$_POST["user_email"] ;
		$data_insert['user_tel'] =  @$_POST["user_tel"] ;
		$data_insert['updatedate'] =  date("Y-m-d H:i:s");
		
		if(empty($user_id))
		{
			$data_insert['user_type_id'] =  '2';
			$data_insert['createdate'] =  date("Y-m-d H:i:s");
			$this->db->insert('coop_user', $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			
			$user_id = $this->db->insert_id();
			
			/*$sql = "SELECT * FROM coop_user WHERE user_id = '{$user_id}'";
			$rs = $mysqli->query($sql);
			$rowNew = $rs->fetch_assoc();
			$accesslog->add($_SESSION["USER_ID"], "insert", $_SERVER["PHP_SELF"], "coop_user", "", $rowNew, $user_id);
			*/
		}
		else
		{
			$this->db->where('user_id', $user_id);
			$this->db->update('coop_user', $data_insert);	
						
		
			$this->db->where('user_id', $user_id );
			$this->db->delete('coop_user_permission');

			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
			
			/*
			$sql = "SELECT * FROM coop_user WHERE user_id = '{$user_id}'";
			$rs = $mysqli->query($sql);
			$rowOld = $rs->fetch_assoc();
			
			$sql = "SELECT * FROM coop_user WHERE user_id = '{$user_id}'";
			$rs = $mysqli->query($sql);
			$rowNew = $rs->fetch_assoc();
			$accesslog->add($_SESSION["USER_ID"], "update", $_SERVER["PHP_SELF"], "coop_user", $rowOld, $rowNew, $user_id);
			*/
		}
	
		if(!empty($_POST["user_permissions"])) {
			$data_insert_permission = array();
			foreach($_POST["user_permissions"] as $key => $value) {
				$data_insert_permission = array();
				$data_insert_permission['user_id'] = @$user_id;				
				$data_insert_permission['menu_id'] = @$key;
				$this->db->insert('coop_user_permission', $data_insert_permission);
			}
		}
		
		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_user' </script>";      
	}
	
	public function search_employee()
	{
		$employee_id = @$_POST['employee_id'];
		$this->db->select('*');
		$this->db->from('coop_mem_apply');
		$this->db->where("employee_id  = '{$employee_id}' ");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0];
		//print_r($this->db->last_query());
		if(!empty($row)){
			echo @$row['firstname_th']." ".@$row['lastname_th'];
		}else{
			echo "error";
		}
		exit;
	}

	function del_coop_user(){	
	
		$id = @$_POST["id"];
			
		$this->db->where('user_id', $id );
		$this->db->delete('coop_user');	
        

		$this->db->where('user_id', $id );
		$this->db->delete('coop_user_permission');
		$this->center_function->toast("ลบข้อมูลเรียบร้อยแล้ว");
		echo true;
		
	}
	
	public function coop_transactions_new()
	{
		$arr_data = array();
		$this->db->select(array('*'));
		$this->db->from('coop_money_type');
		$this->db->order_by('id ASC');
		$rs = $this->db->get()->result_array();
		$arr_data['rs']= @$rs;
			
		$this->libraries->template('setting_basic_data/coop_transactions_new',$arr_data);
	}

	public function coop_transactions_new_save()
	{
		$data_insert = array();	
		
		foreach(@$_POST['data'] as $key => $value){
			$data_insert['money_type_name_short'] = @$value['money_type_name_short'];
			$data_insert['money_type_name_eng'] = @$value['money_type_name_eng'];
			$data_insert['money_type_name_th'] = @$value['money_type_name_th'];
				
			$this->db->where('id', $key);
			$this->db->update('coop_money_type', $data_insert);	
		}
			
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");	
			
		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_transactions_new' </script>";  
	}		
	
	public function chk_user(){
		if(@$_GET["username"] !=@$_GET["old_username"]) {
			$this->db->select('*');
			$this->db->from('coop_user');
			$this->db->where("username = '".@$_GET["username"]."'");
			$rs = $this->db->get()->result_array();
			$row = @$rs[0];

			if(@$row != '') {
				echo json_encode("Username ซ้ำ");
			}
			else {
				echo json_encode(TRUE);
			}
		}
		else {
			echo json_encode(TRUE);
		}
	}	
	
	public function coop_signature()
	{
		$arr_data = array();
		$id = @$_GET['id'];
		if(!empty($id)){
			$this->db->select(array('*'));
			$this->db->from('coop_signature');
			$this->db->where("signature_id = '{$id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0]; 	
		}else{	
			$x=0;
			$join_arr = array();
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('*');
			$this->paginater_all->main_table('coop_signature');
			$this->paginater_all->where("");
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('signature_id DESC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo $this->db->last_query();exit;
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit'], $_GET);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			
			$i = $row['page_start'];

			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $row['data'];
			$arr_data['i'] = $i;
		}
		$this->libraries->template('setting_basic_data/coop_signature',$arr_data);
	}
	
	public function coop_signature_save()
	{
		$data_insert = array();
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"];

		$this->db->select(array('*'));
		$this->db->from('coop_signature');
		$this->db->where("signature_id = '{$id_edit}'");
		$row = $this->db->get()->result_array();
		$row = @$row[0]; 
		
		
		if(!empty($_FILES["signature_1"]["tmp_name"])){
			if(@$row['signature_1']){
				@unlink( PATH . "/images/coop_signature/{$row['signature_1']}");	
			}
			
			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/images/coop_signature/";
			$new_file_name = $this->center_function->create_file_name($output_dir,$_FILES["signature_1"]['name']);
			copy($_FILES["signature_1"]['tmp_name'], $output_dir.$new_file_name);
			$data_insert['signature_1'] = @$new_file_name;
		}

		if(!empty($_FILES["signature_2"]["tmp_name"])){
			if(@$row['signature_2']){
				@unlink( PATH . "/images/coop_signature/{$row['signature_2']}");
			}			
			
			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/images/coop_signature/";
			$new_file_name = $this->center_function->create_file_name($output_dir,$_FILES["signature_2"]['name']);
			copy($_FILES["signature_2"]['tmp_name'], $output_dir.$new_file_name);
			$data_insert['signature_2'] = @$new_file_name;			
		}
		
		$data_insert['start_date']    = $this->center_function->ConvertToSQLDate(@$_POST["start_date"]);
		$data_insert['finance_name']    = @$_POST["finance_name"];
		$data_insert['receive_name']    = @$_POST["receive_name"];		
		$data_insert['updatetime']    = date('Y-m-d H:i:s');

		$table = "coop_signature";

		if ($type_add == 'add') {	
		// add
			$data_insert['createdatetime']  = date('Y-m-d H:i:s');
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('signature_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");
			//print_r($this->db->last_query());exit;
		// edit
		}
		
		echo"<script> document.location.href='".PROJECTPATH."/setting_basic_data/coop_signature' </script>"; 

	}
	
	function check_date_signature(){
		$start_date = $this->center_function->ConvertToSQLDate(@$_POST["start_date"]);
		$id = @$_POST["id"];
		if(@$id){
			$where = " AND signature_id <> {$id}";
		}else{
			$where = "";
		}
		
		$this->db->select(array('*'));
		$this->db->from('coop_signature');
		$this->db->where("start_date = '{$start_date}' {$where}");
		$row = $this->db->get()->result_array();
		$row = @$row[0]; 
		if(@$row['start_date']){
			echo false;
		}else{
			echo true;
		}
		exit;
	}
	
}
