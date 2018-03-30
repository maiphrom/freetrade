<?php

class Center_function
{
	function toast($message) { setcookie("toast", $message , time() + 1); }

	function toastDanger($message) { setcookie("toast_e", $message , time() + 1); }

	function mydate2date($date, $time = false, $lang = "th") {
		if($date!='') {
			if ($lang == "th") {
				$tmp = explode(" ", $date);
				if ($tmp[0] != "" && $tmp[0] != "0000-00-00") {
					$d = explode("-", $tmp[0]);
					$str = $d[2] . "/" . $d[1] . "/" . ($d[0] > 2500 ? $d[0] : $d[0] + 543);
					if ($time) {
						$t = strtotime($date);
						$str .= " " . date("H:i", $t);
					}
				}
			} else {
				$str = empty($date) || $date == "0000-00-00 00:00:00" || $date == "0000-00-00" ? "" : date("d/m/Y" . ($time ? " H:i" : ""), strtotime($date));
			}

			return $str;
		}else{
			return '';
		}
	}

	function ConvertToSQLDate($date) {
		if(!empty($date)) {
			if(strpos($date, "/")!==false) {
				$x = explode("/", $date);
				$x[2] = ($x[2] > 2500 ? $x[2] - 543 : $x[2]);
				$x[1] = sprintf("%02d", (int)$x[1]);
				$return = "{$x[2]}-{$x[1]}-{$x[0]}";
			} elseif(strpos($date, "-")!==false) {
				$x = explode("-", $date);
				$x[0] = ($x[0] > 2500 ? $x[0] - 543 : $x[0]);
				$x[1] = sprintf("%02d", (int)$x[1]);
				$return = "{$x[0]}-{$x[1]}-{$x[2]}";
			} else $return = "0000-00-00";
		} else $return = "";
		return $return;
	}

	function ConvertToThaiDate($value,$short='1',$need_time='1') {
		$date_arr = explode(' ', $value);
		$date = $date_arr[0];
		if(isset($date_arr[1])){
			$time = $date_arr[1];
		}else{
			$time = '';
		}

		$value = $date;
		if($value!="0000-00-00") {
			$x=explode("-",$value);
			if($short==false)
				$arrMM=array(1=>"มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
			else
				$arrMM=array(1=>"ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			// return $x[2]." ".$arrMM[(int)$x[1]]." ".($x[0]>2500?$x[0]:$x[0]+543);
			if($need_time=='1'){
				$time_format = $time!=''?date('H:i น.',strtotime($time)):'';
			}else{
				$time_format = '';
			}

			return (int)$x[2]." ".$arrMM[(int)$x[1]]." ".($x[0]>2500?$x[0]:$x[0]+543)." ".$time_format;
		} else
			return "<font color=\"#FF0000\">ไม่ระบุ</font>";
	}
	function convert($number) {
		$txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
		$txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
		$number = str_replace(",","",$number);
		$number = str_replace(" ","",$number);
		$number = str_replace("บาท","",$number);
		$number = explode(".",$number);
		if(sizeof($number) > 2) {
			return 'ทศนิยมหลายตัวนะจ๊ะ';
			exit;
		}
		$strlen = strlen($number[0]);
		$convert = '';
		for($i=0;$i<$strlen;$i++){
			$n = substr($number[0], $i,1);
			if($n!=0){
				if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; }
				elseif($i==($strlen-2) AND $n==2){ $convert .= 'ยี่'; }
				elseif($i==($strlen-2) AND $n==1){ $convert .= ''; }
				else{ $convert .= $txtnum1[$n]; }
				$convert .= $txtnum2[$strlen-$i-1];
			}
		}
		if(!isset($number[1])) $number[1] = 0;
		$convert .= 'บาท';
		if($number[1]=='0' || $number[1]=='00' || $number[1]==''){
			$convert .= 'ถ้วน';
		}else{
			$strlen = strlen($number[1]);
			for($i=0;$i<$strlen;$i++){
				$n = substr($number[1], $i,1);
				if($n!=0){
					if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';}
					elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';}
					elseif($i==($strlen-2) AND $n==1){$convert .= '';}
					else{ $convert .= $txtnum1[$n];}
					$convert .= $txtnum2[$strlen-$i-1];
				}
			}
			$convert .= 'สตางค์';
		}
		return $convert;
	}
	function create_file_name($output_dir,$file_name){
		$list_dir = array();
		$cdir = scandir($output_dir);
		foreach ($cdir as $key => $value) {
			if (!in_array($value,array(".",".."))) {
				if (@is_dir(@$dir . DIRECTORY_SEPARATOR . $value)){
					$list_dir[$value] = dirToArray(@$dir . DIRECTORY_SEPARATOR . $value);
				}else{
					if(substr($value,0,8) == date('Ymd')){
						$list_dir[] = $value;
					}
				}
			}
		}
		$explode_arr=array();
		foreach($list_dir as $key => $value){
			$task = explode('.',$value);
			$task2 = explode('_',$task[0]);
			$explode_arr[] = $task2[1];
		}
		$max_run_num = sprintf("%04d",count($explode_arr)+1);
		$explode_old_file = explode('.',$file_name);
		$new_file_name = date('Ymd')."_".$max_run_num.".".$explode_old_file[(count($explode_old_file)-1)];
		return $new_file_name;
	}

	
	function send_mj_mail($subject, $mail_detail, $to) {
		$key_api = "0a4aaa3552724ba4956e5c8c4a2e6a1f";
		$key_secret = "6d36928aec0420193222d7ecadded7d7";

		require('Mailjet/php-mailjet-v3-simple.class.php');

		$mj = new Mailjet($key_api, $key_secret);


		$x = array();
		$x = explode(",", $to);
		foreach($x as $key => $val) {
			$params = array(
					"method" => "POST",
					"from" => "freetradecoop <noreply@upbean.co.th>",
					"to" => $val,
					"subject" => $subject,
					"html" => $mail_detail
			);

		$result = $mj->sendEmail($params);
		}
		return $result;
	}
}
?>