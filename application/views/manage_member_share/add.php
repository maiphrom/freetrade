<div class="layout-content">
    <div class="layout-content-body">
<style>
    .form-group { margin-bottom: 0; }
    .border1 { border: solid 1px #ccc; padding: 0 15px; }
    .mem_pic { float: right; width: 150px; }
    .mem_pic img { width: 100%; border: solid 1px #ccc; }
    .mem_pic button { display: block; width: 100%; }

    .hide_error{color : inherit;border-color : inherit;}

    .has-error{color : #d50000;border-color : #d50000;}

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .alert-danger {
        background-color: #F2DEDE;
        border-color: #e0b1b8;
        color: #B94A48;
    }
    .modal-backdrop.in{
        opacity: 0;
    }
    .modal-backdrop {
        position: relative;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        background-color: #000;
    }
</style>
<?php
    function birthday($bithdayDate) {
        $date = new DateTime($bithdayDate);
        $now  = new DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }
?>
<h1 style="margin-bottom: 0">ข้อมูลสมาชิก</h1>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
        <?php $this->load->view('breadcrumb'); ?>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
        <a class="btn btn-primary btn-lg bt-add" href="<?php echo base_url(PROJECTPATH.'/manage_member_share/add'); ?>">
            <span class="icon icon-plus-circle"></span>
            สมัครสมาชิกใหม่
        </a>
    </div>

</div>
<div class="row gutter-xs">
    <div class="col-xs-12 col-md-12">
        <div class="panel panel-body">
            <form data-toggle="validator" method="post" action="<?php echo base_url(PROJECTPATH.'/manage_member_share/save_add'); ?>" class="g24 form form-horizontal" enctype="multipart/form-data" autocomplete="off" id="myForm">
                <input type="hidden" name="mem_apply_id" value="<?php echo (!empty($data))?$data['mem_apply_id']:''; ?>"/>

                <div class="m-t-1">

                    <div class="g24-col-sm-20">

                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label">รหัสสมาชิก <span id="naja"></span> </label>
                            <div class="g24-col-sm-8">
                                <div class="form-group">
                                    <input id="member_id" name="member_id" class="form-control" style="text-align:left;" type="number" value="<?php echo empty($data) ? sprintf("%06d",$auto_member_id) : $data['member_id']; ?>" readonly="readonly" required title="กรุณาป้อน เลขสมาชิก" />

                                    <div class="checkbox">
                                        <label style="padding-left:20px;">

                                            <?php 	if(empty($data)){ ?>
                                                <input type="checkbox" id="is_fix_member_id" data-mem="<?php echo sprintf('%06d',$auto_member_id)?>" name="is_fix_member_id" value="1"<?php if(@$data["is_fix_member_id"]) { ?> checked="checked"<?php } ?> /> กำหนดเลขสมาชิกเอง 		<span id="message"></span>
                                            <?php } ?>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="g24-col-sm-1">
                                <a data-toggle="modal" data-target="#myModal" id="test" class="fancybox_share fancybox.iframe" href="#">
                                    <button style="padding: 6px 11px 4px;" id="" type="button" class="btn btn-info"><span class="icon icon-search"></span>
                                    </button>
                                </a>
                            </div>

                            <label class="g24-col-sm-3 control-label datepicker1" for="apply_date">วันที่สมัคร</label>
                            <div class="input-with-icon g24-col-sm-9">
                                <div class="form-group">
                                    <input id="apply_date" name="apply_date" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(empty($data) ? date("Y-m-d") : @$data['apply_date']); ?>" data-date-language="th-th" required title="กรุณาป้อน วันที่สมัคร">
                                    <span class="icon icon-calendar input-icon m-f-1"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label" for="apply_type_id">ประเภทสมัคร</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <select id="apply_type_id" name="apply_type_id" class="form-control m-b-1" required title="กรุณาเลือก ประเภทสมัคร">
                                        <?php foreach($mem_apply_type as $key => $value) { ?>
                                            <option value="<?php echo $value["apply_type_id"]; ?>"<?php if($value["apply_type_id"] == @$data["apply_type_id"]) { ?> selected="selected"<?php } ?>><?php echo $value["apply_type_name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="mem_type">ประเภทสมาชิก</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <select id="mem_type" name="mem_type" class="form-control m-b-1" <?php if(@$data["mem_type"] == '1' || empty($data)) { ?> disabled="true" <?php } ?> required title="กรุณาเลือก ประเภทสมาชิก" onchange="change_mem_type('<?php echo $id; ?>')">
                                        <option value="1"<?php if(@$data["mem_type"] == '1') { ?> selected="selected"<?php } ?>>ปกติ</option>
                                        <option value="2"<?php if(@$data["mem_type"] == '2') { ?> selected="selected"<?php } ?>>ลาออก</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label">รหัสพนักงาน </label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input type="text" class="form-control m-b-1" name="employee_id" id="employee_id" value="<?php echo @$data['employee_id']?>">
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="member_time">รอบสมัคร</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <select id="member_time_select" disabled="true" class="form-control m-b-1" required>
                                        <option value="1"<?php if(@$data["member_time"] == '1') { ?> selected="selected"<?php } ?>>รอบที่1</option>
                                        <option value="2"<?php if(@$data["member_time"] == '2') { ?> selected="selected"<?php } ?>>รอบที่2</option>
                                    </select>
                                    <input type="hidden" id="member_time" name="member_time" value="<?php echo @$data["member_time"]; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label" for="prename_id">คำนำหน้า</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <select id="prename_id" name="prename_id" class="form-control m-b-1" required title="กรุณาเลือก คำนำหน้า">
                                        <?php foreach($prename as $key => $value) { ?>
                                            <option value="<?php echo $value["prename_id"]; ?>"<?php if($value["prename_id"] == @$data["prename_id"]) { ?> selected="selected"<?php } ?>><?php echo $value["prename_full"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="sex">เพศ</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <select id="sex" name="sex" class="form-control m-b-1" required title="กรุณาเลือก เพศ">
                                        <option value="M"<?php if(@$data["sex"] == "M") { ?> selected="selected"<?php } ?>>ชาย</option>
                                        <option value="F"<?php if(@$data["sex"] == "F") { ?> selected="selected"<?php } ?>>หญิง</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">

                            <label class="g24-col-sm-3 control-label" for="firstname_th">ชื่อ (ภาษาไทย)</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="firstname_th" name="firstname_th" class="form-control m-b-1" type="text" value="<?php echo @$data['firstname_th']; ?>" required title="กรุณาป้อน ชื่อ (ภาษาไทย)">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="lastname_th">สกุล (ภาษาไทย)</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="lastname_th" name="lastname_th" class="form-control m-b-1" type="text" value="<?php echo @$data['lastname_th']; ?>" required title="กรุณาป้อน สกุล (ภาษาไทย)">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label" for="firstname_en">ชื่อ (English)</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="firstname_en" name="firstname_en" class="form-control m-b-1" type="text" value="<?php echo @$data['firstname_en']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="lastname_en">สกุล (English)</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="lastname_en" name="lastname_en" class="form-control m-b-1" type="text" value="<?php echo @$data['lastname_en']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label" for="email">E-mail</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="email" name="email" class="form-control m-b-1" type="text" value="<?php echo @$data['email']; ?>">
                                </div>
                            </div>



                            <label class="g24-col-sm-3 g24-col-xs-12 control-label" for="tel">เบอร์บ้าน</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="tel" name="tel" class="form-control m-b-1" type="text" value="<?php echo @$data['tel']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="g24-col-sm-3 control-label" for="office_tel">เบอร์ที่ทำงาน</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="office_tel" name="office_tel" class="form-control m-b-1" type="text" value="<?php echo @$data['office_tel']; ?>">
                                </div>
                            </div>


                            <label class="g24-col-sm-3 control-label" for="mobile">เบอร์มือถือ</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="mobile" name="mobile" class="form-control m-b-1" type="number" value="<?php echo @$data['mobile']; ?>" required title="กรุณาป้อน เบอร์มือถือ"  maxlength="10">
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="g24-col-sm-4">

                        <div class="g24-col-sm-24 m-b-1 text-right">
                            <div class="mem_pic" style="margin-bottom:20px;display: block;margin: 0 auto;">
                                <img id="member_pic" src="<?php echo base_url(); ?>ci_project/assets/uploads/members/<?php echo empty($data['member_pic']) ? "default.png" : $data['member_pic']; ?>" alt="" />
                                <button type="button" id="btn_member_pic" class="btn btn-info">รูปภาพสมาชิก</button>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="g24-col-sm-20">

                            <h3 class="m-t-1">ที่อยู่ตามทะเบียนบ้าน</h3><br>

                            <label class="g24-col-sm-3 control-label" for="address_no">เลขที่</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="address_no" name="address_no" class="form-control m-b-1" type="text" value="<?php echo @$data['address_no']; ?>" required title="กรุณาป้อน เลขที่อยู่ตามทะเบียนบ้าน">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="address_moo" >หมู่</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="address_moo" name="address_moo" class="form-control m-b-1" type="text" value="<?php echo @$data['address_moo']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="address_village">หมู่บ้าน</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="address_village" name="address_village" class="form-control m-b-1" type="text" value="<?php echo @$data['address_village']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="address_soi">ซอย</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="address_soi" name="address_soi" class="form-control m-b-1" type="text" value="<?php echo @$data['address_soi']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="address_road">ถนน</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="address_road" name="address_road" class="form-control m-b-1" type="text" value="<?php echo @$data['address_road']; ?>">
                                </div>
                            </div>


                            <label class="g24-col-sm-3 control-label" for="province_id">จังหวัด</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <select name="province_id" id="province_id" class="form-control m-b-1" onchange="change_province('province_id','amphure','amphur_id','district','district_id')">
                                        <option value="">เลือกจังหวัด</option>
                                        <?php foreach($province as $key => $value){ ?>
                                                <option value="<?php echo $value['province_id']; ?>"<?php echo $value['province_id']==@$data['province_id']?'selected':''; ?>><?php echo $value['province_name']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="amphur_id">อำเภอ</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <span id="amphure">
                                         <select name="amphur_id" id="amphur_id" class="form-control m-b-1" onchange="change_amphur('amphur_id','district','district_id')">
                                             <option value="">เลือกอำเภอ</option>
                                             <?php foreach($amphur as $key => $value){ ?>
                                                 <option value="<?php echo $value['amphur_id']; ?>"<?php echo $value['amphur_id']==@$data['amphur_id']?'selected':''; ?>><?php echo $value['amphur_name']; ?></option>
                                             <?php }?>
                                         </select>
                                    </span>
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="district_id">ตำบล</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <span id="district">
                                        <select name="district_id" id="district_id" class="form-control m-b-1">
                                            <option value="">เลือกตำบล</option>
                                            <?php foreach($district as $key => $value){ ?>
                                                <option value="<?php echo $value['district_id']; ?>"<?php echo $value['district_id']==@$data['district_id']?'selected':''; ?>><?php echo $value['district_name']; ?></option>
                                            <?php }?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="zipcode">รหัสไปรษณีย์</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="zipcode" name="zipcode" class="form-control m-b-1" type="text" value="<?php echo @$data['zipcode']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <br />
                    <div class="row" >
                        <div class="g24-col-sm-20">
                            <div class="row" style="margin-top:1.5em;">
                                <div class="g24-col-sm-3">
                                    <h3 style="margin-top:0;">ที่อยู่ปัจจุบัน</h3>
                                </div>
                                <div class="g24-col-sm-10" >
                                    <div class="checkbox" style="margin-top:0px;margin-top:0px;padding-top:3px;">
                                        <label>
                                            <input type="checkbox" id="is_c_address"  value="1" onclick="dupp_address(this)" /> ใช้ที่อยู่ตามทะเบียนบ้าน
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <label class="g24-col-sm-3 control-label" for="c_address_no" id="c_address_no_label">เลขที่</label>
                            <div class="g24-col-sm-9" id="address_no_con">
                                <div class="form-group">
                                    <input id="c_address_no" name="c_address_no" class="form-control m-b-1" type="text" value="<?php echo @$data['c_address_no']; ?>" required title="กรุณาป้อน เลขที่อยู่ปัจจุบัน">
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="c_address_moo">หมู่</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="c_address_moo" name="c_address_moo" class="form-control m-b-1" type="text" value="<?php echo @$data['c_address_moo']; ?>">
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="c_address_village">หมู่บ้าน</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="c_address_village" name="c_address_village" class="form-control m-b-1" type="text" value="<?php echo @$data['c_address_village']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="c_address_soi">ซอย</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="c_address_soi" name="c_address_soi" class="form-control m-b-1" type="text" value="<?php echo @$data['c_address_soi']; ?>">
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="c_address_road">ถนน</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="c_address_road" name="c_address_road" class="form-control m-b-1" type="text" value="<?php echo @$data['c_address_road']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="c_province_id" id="c_province_id_label">จังหวัด</label>
                            <div class="g24-col-sm-9" id="province_con">
                                <div class="form-group">
                                    <select name="c_province_id" id="c_province_id" class="form-control m-b-1" onchange="change_province('c_province_id','c_amphure','c_amphur_id','c_district','c_district_id')">
                                        <option value="">เลือกจังหวัด</option>
                                        <?php foreach($province as $key => $value){ ?>
                                            <option value="<?php echo $value['province_id']; ?>"<?php echo $value['province_id']==@$data['c_province_id']?'selected':''; ?>><?php echo $value['province_name']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="c_amphur_id" id="c_amphur_id_label">อำเภอ</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group" id="amphure_con">
                                    <span id="c_amphure">
                                        <select name="c_amphur_id" id="c_amphur_id" class="form-control m-b-1" onchange="change_amphur('c_amphur_id','c_district','c_district_id')">
                                            <option value="">เลือกอำเภอ</option>
                                            <?php foreach($c_amphur as $key => $value){ ?>
                                                <option value="<?php echo $value['amphur_id']; ?>"<?php echo $value['amphur_id']==@$data['c_amphur_id']?'selected':''; ?>><?php echo $value['amphur_name']; ?></option>
                                            <?php }?>
                                        </select>
                                    </span>
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="c_district_id" id="c_district_id_label">ตำบล</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group"  id="district_con">
                                    <span id="c_district">
                                        <select name="c_district_id" id="c_district_id" class="form-control m-b-1">
                                            <option value="">เลือกตำบล</option>
                                            <?php foreach($c_district as $key => $value){ ?>
                                                <option value="<?php echo $value['district_id']; ?>"<?php echo $value['district_id']==@$data['c_district_id']?'selected':''; ?>><?php echo $value['district_name']; ?></option>
                                            <?php }?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <label class="g24-col-sm-3 control-label" for="c_zipcode">รหัสไปรษณีย์</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="c_zipcode" name="c_zipcode" class="form-control m-b-1" type="text" value="<?php echo @$data['c_zipcode']; ?>">
                                </div>
                            </div>

                        </div>

                        <div class="g24-col-sm-12">

                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="g24-col-sm-20">
                            <h3 style="margin-top:0;">ข้อมูลส่วนตัว</h3><br>

                            <div class="">

                                <label class="g24-col-sm-3 control-label" for="marry_status">สถานะสมรส</label>
                                <div class="g24-col-sm-9">
                                    <div class="form-group">
                                        <select id="marry_status" name="marry_status" class="form-control m-b-1" required title="กรุณาเลือก สถานะสมรส">
                                            <option value="1"<?php if(@$data["marry_status"] == 1) { ?> selected="selected"<?php } ?>>โสด</option>
                                            <option value="2"<?php if(@$data["marry_status"] == 2) { ?> selected="selected"<?php } ?>>สมรส</option>
                                        </select>
                                    </div>
                                </div>


                            </div>

                            <label class="g24-col-sm-3 control-label" for="id_card" style="white-space: nowrap;"> เลขบัตรประชาชน </label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="id_card" name="id_card" class="form-control m-b-1" type="text" value="<?php echo @$data['id_card']; ?>" required title="กรุณาป้อน เลขบัตรประชาชน" onkeypress="return chkNumber(this)" maxlength='13'>
                                    <input id="old_id_card"type="hidden" value="<?php echo @$data['id_card']; ?>">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="nationality">สัญชาติ</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="nationality" name="nationality" class="form-control m-b-1" type="text" value="<?php echo @$data['nationality']; ?>" required title="กรุณาป้อน สัญชาติ">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="birthday"> วันเกิด </label>
                            <div class="input-with-icon g24-col-sm-4" id="birthday_con">
                                <div class="form-group">
                                    <input id="birthday" name="birthday" class="form-control m-b-1" style="padding-left: 40px;" type="text" value="<?php echo $this->center_function->mydate2date(@$data['birthday']); ?>" data-date-language="th-th" required title="กรุณาเลือก วันเกิด">
                                    <span class="icon icon-calendar input-icon m-f-1"></span>
                                </div>
                            </div>


                            <div id="calendar-2"></div>
                            <div id="result-2"></div>

                            <label class="g24-col-sm-2 control-label">อายุ</label>
                            <div class="g24-col-sm-3">
                                <div class="form-group" id="birthday_border">
                                    <input id="age" class="form-control m-b-1" type="text" value="<?php echo birthday(@$data['birthday']) == 0 ? NULL : birthday(@$data['birthday'])?>" readonly="readonly" required title="&nbsp;">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="father_name">ชื่อบิดา</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="father_name" name="father_name" class="form-control m-b-1" type="text" value="<?php echo @$data['father_name']; ?>" required title="กรุณาป้อน ชื่อบิดา">
                                </div>
                            </div>

                            <label class="g24-col-sm-3 control-label" for="mother_name">ชื่อมารดา</label>
                            <div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="mother_name" name="mother_name" class="form-control m-b-1" type="text" value="<?php echo @$data['mother_name']; ?>" required title="กรุณาป้อน ชื่อมารดา">
                                </div>
                            </div>


                            <div class="row">
                                <div class="g24-col-sm-20">

                                    <h3>ข้อมูลที่ทำงาน</h3><br>
                                </div>
                                <div class="g24-col-sm-24">
                                    <label class="g24-col-sm-3 control-label" for="position">ตำแหน่ง</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="position" name="position" class="form-control m-b-1" type="text" value="<?php echo @$data['position']; ?>" required title="กรุณาป้อน ตำแหน่ง">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="department">ฝ่าย</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <select class="form-control m-b-1" name="department" id="department" required title="กรุณาเลือกฝ่าย" onchange="change_mem_group('department', 'faction')">
                                                <option value="">เลือกข้อมูล</option>
                                                <?php
                                                foreach($department as $key => $value){ ?>
                                                    <option value="<?php echo $value['id']; ?>" <?php echo @$data['department']==$value['id']?'selected':''; ?>><?php echo $value['mem_group_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="faction">แผนก</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group" id="faction_space">
                                            <select class="form-control m-b-1" name="faction" id="faction" required title="กรุณาเลือกแผนก" onchange="change_mem_group('faction','level')">
                                                <option value="">เลือกข้อมูล</option>
                                                <?php foreach($faction as $key => $value){ ?>
                                                        <option value="<?php echo $value['id']; ?>" <?php echo @$data['faction']==$value['id']?'selected':'';?>><?php echo $value['mem_group_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="level">หน่วยงาน</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group" id="level_space">
                                            <select class="form-control m-b-1" name="level" id="level" required title="กรุณาเลือกหน่วยงาน">
                                                <option value="">เลือกข้อมูล</option>
                                                <?php foreach($level as $key => $value){ ?>
                                                    <option value="<?php echo $value['id']; ?>" <?php echo @$data['level']==$value['id']?'selected':'';?>><?php echo $value['mem_group_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="salary_type">ประเภท</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <select id="salary_type" name="salary_type" class="form-control m-b-1" required title="กรุณาเลือก ประเภทรายวันรายเดือน">
                                                <option value="1"<?php if(@$data["salary_type"] == 1) { ?> selected="selected"<?php } ?>>รายวัน</option>
                                                <option value="2"<?php if(@$data["salary_type"] == 2) { ?> selected="selected"<?php } ?>>รายเดือน</option>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="work_id_card">เลขพนักงาน</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="work_id_card" name="work_id_card" class="form-control m-b-1" type="text" value="<?php echo @$data['work_id_card']; ?>" required title="กรุณาป้อน เลขพนักงาน">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="work_date">วันบรรจุ</label>
                                    <div class="input-with-icon g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="work_date" name="work_date" class="form-control m-b-1" type="text" style="padding-left: 45px;" value="<?php echo $this->center_function->mydate2date(@$data['work_date']); ?>" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd/mm/yyyy" data-date-language="th-th">
                                            <span class="icon icon-calendar input-icon m-f-1"></span>
                                        </div>
                                    </div>
                                    <label class="g24-col-sm-3 control-label" for="retry_date">เกษียณ</label>
                                    <div class="input-with-icon g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="retry_date" name="retry_date" class="form-control m-b-1" type="text"  style="padding-left: 45px;" value="<?php echo $this->center_function->mydate2date(@$data['retry_date']); ?>" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd/mm/yyyy" data-date-language="th-th">
                                            <span class="icon icon-calendar input-icon m-f-1"></span>
                                        </div>
                                    </div>

                                    <span style="<?php echo $salary_display; ?>">
							<label class="g24-col-sm-3 control-label" for="salary">เงินเดือน</label>
							<div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="salary" name="salary" class="form-control m-b-1" type="number" value="<?php echo @$data['salary']; ?>" required title="กรุณาป้อน เงินเดือน">
                                </div>
                            </div>
							<label class="g24-col-sm-3 control-label" for="other_income">เงินอื่นๆ</label>
							<div class="g24-col-sm-9">
                                <div class="form-group">
                                    <input id="other_income" name="other_income" class="form-control m-b-1" type="number" value="<?php echo @$data['other_income']; ?>" title="กรุณาป้อน เงินอื่นๆ">
                                </div>
                            </div>
						</span>

                                    <label class="g24-col-sm-3 control-label" for="salary">ลายเซ็นต์</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="signature" name="signature" class="form-control m-b-1" type="file" value="" style="height: auto;">
                                            <?php if(!empty($data['signature'])) { ?>
                                                <div>
                                                    <img src="<?php echo base_url(); ?>ci_project/assets/uploads/members/<?php echo @$data['signature']; ?>" alt="" style="width: 120px;" />
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="g24-col-sm-24">
                                    <h3 class="m-t-1">ข้อมูลคู่สมรส</h3><br>

                                    <label class="g24-col-sm-3 control-label" for="marry_name">ชื่อคู่สมรส</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="marry_name" name="marry_name" class="form-control m-b-1" type="text" value="<?php echo @$data['marry_name']; ?>">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" style="white-space: nowrap;" for="m_id_card">เลขบัตรประชาชน</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_id_card" name="m_id_card" class="form-control m-b-1" type="text" value="<?php echo @$data['m_id_card']; ?>" onkeypress="return chkNumber(this)" maxlength='13'>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="m_work_id_card">รหัสพนักงาน</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_work_id_card" name="m_work_id_card" class="form-control m-b-1" type="text" value="<?php echo @$data['m_work_id_card']; ?>">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="m_address_no">เลขที่</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_address_no" name="m_address_no" class="form-control m-b-1" type="text" value="<?php echo @$data['m_address_no']; ?>">
                                        </div>
                                    </div>
                                    <label class="g24-col-sm-3 control-label" for="m_address_moo">หมู่</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_address_moo" name="m_address_moo" class="form-control m-b-1" type="text" value="<?php echo @$data['m_address_moo']; ?>">
                                        </div>
                                    </div>
                                    <label class="g24-col-sm-3 control-label" for="m_address_village">หมู่บ้าน</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_address_village" name="m_address_village" class="form-control m-b-1" type="text" value="<?php echo @$data['m_address_village']; ?>">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="m_address_soi">ซอย</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_address_soi" name="m_address_soi" class="form-control m-b-1" type="text" value="<?php echo @$data['m_address_soi']; ?>">
                                        </div>
                                    </div>
                                    <label class="g24-col-sm-3 control-label" for="m_address_road">ถนน</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_address_road" name="m_address_road" class="form-control m-b-1" type="text" value="<?php echo @$data['m_address_road']; ?>">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="form-control-2">จังหวัด</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <select name="m_province_id" id="m_province_id" class="form-control m-b-1" onchange="change_province('m_province_id','m_amphure','m_amphur_id','m_district','m_district_id')">
                                                <option value="">เลือกจังหวัด</option>
                                                <?php foreach($province as $key => $value){ ?>
                                                    <option value="<?php echo $value['province_id']; ?>"<?php echo $value['province_id']==@$data['m_province_id']?'selected':''; ?>><?php echo $value['province_name']; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="form-control-2">อำเภอ</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <span id="m_amphure">
                                                <select name="m_amphur_id" id="m_amphur_id" class="form-control m-b-1" onchange="change_amphur('m_amphur_id','m_district','m_district_id')">
                                                    <option value="">เลือกอำเภอ</option>
                                                    <?php foreach($m_amphur as $key => $value){ ?>
                                                        <option value="<?php echo $value['amphur_id']; ?>"<?php echo $value['amphur_id']==@$data['m_amphur_id']?'selected':''; ?>><?php echo $value['amphur_name']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </span>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="form-control-2">ตำบล</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <span id="m_district">
                                                <select name="m_district_id" id="m_district_id" class="form-control m-b-1">
                                                    <option value="">เลือกตำบล</option>
                                                    <?php foreach($m_district as $key => $value){ ?>
                                                        <option value="<?php echo $value['district_id']; ?>"<?php echo $value['district_id']==@$data['m_district_id']?'selected':''; ?>><?php echo $value['district_name']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </span>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="m_zipcode">รหัสไปรษณีย์</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_zipcode" name="m_zipcode" class="form-control m-b-1" type="text" value="<?php echo @$data['m_zipcode']; ?>">
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-3 control-label" for="m_tel">โทรศัพท์</label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="m_tel" name="m_tel" class="form-control m-b-1" type="text" value="<?php echo @$data['m_tel']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <!--div class="row">
                                <div class="g24-col-sm-24">
                                    <h3 class="m-t-1">ส่งค่าหุ้นรายเดือน</h3><br>
                                    <label class="g24-col-sm-3 control-label" for="address_no"> เดือนละ </label>
                                    <div class="g24-col-sm-9">
                                        <div class="form-group">
                                            <input id="share_month" name="share_month" min=""  placeholder="" class="form-control m-b-1" type="number" value="<?php echo @$data['share_month']; ?>">
                                        </div>
                                    </div>
                                    <label class="g24-col-sm-2 control-label"  style="text-align:left;" for="address_no"> บาท</label>
                                    <div id="share_show" style="display:none;"><label class="g24-col-sm-10 control-label" style="text-align:left;" for="address_no"> เกณฑ์การถือหุ้นแรกเข้าต้องมากกว่า <span id="share_month_text"></span>  บาท </label> </div>
                                </div>
                            </div-->
                            <br />
                            <!-- ธนาคาร -->
                            <div class="row">
                                <div class="g24-col-sm-12" style="margin-bottom: 20px;">

                                    <h3 class="m-t-1">การรับจ่ายเงินปันผล</h3></br>

                                    <label class="g24-col-sm-6 control-label" for="">ทำรายการโดย</label>
                                    <div class=" g24-col-sm-18">
                                        <div class="form-group">
                                            <select id="transaction_id1" name="dividend_bank_act_id" class="form-control m-b-1 clear_pay">
                                                <option value=""> -เลือกประเภททำรายการ- </option>
                                                <?php foreach($act_bank as $key => $value) { ?>
                                                    <option value="<?php echo $value["act_bank_id"]; ?>"<?php if($value["act_bank_id"] == @$data["dividend_bank_act_id"]) { ?> selected="selected"<?php } ?>><?php echo $value["act_bank_name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <label class="g24-col-sm-6 control-label" for="">ธนาคาร</label>
                                    <div class="g24-col-sm-4">
                                        <div class="form-group">
                                            <input id="bank_id_show" class="form-control m-b-1" type="text" value="<?php echo @$data["dividend_bank_id"]; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class=" g24-col-sm-14">
                                        <div class="form-group">
                                            <select id="dividend_bank_id" name="dividend_bank_id" class="form-control m-b-1" onchange="change_bank()">
                                                <option value="">เลือกธนาคาร</option>
                                                <?php foreach($bank as $key => $value) { ?>
                                                <option value="<?php echo $value["bank_id"]; ?>" <?php if($value["bank_id"]==@$data["dividend_bank_id"]) { ?> selected="selected"<?php } ?> > <?php echo $value["bank_name"]; ?>
                                                    </option><?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-6 control-label" for="">สาขา</label>
                                    <div class="g24-col-sm-4">
                                        <div class="form-group">
                                            <input id="branch_id_show" class="form-control m-b-1" type="text" value="<?php echo @$data["dividend_bank_branch_id"]; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class=" g24-col-sm-14">
                                        <div class="form-group">
                                            <span id="bank_branch">
                                                <select id="dividend_bank_branch_id"  name="dividend_bank_branch_id" class="form-control m-b-1" onchange="change_branch()">
                                                    <option value="">เลือกสาขาธนาคาร</option>
                                                    <?php foreach($bank_branch as $key => $value) { ?>
                                                        <option value="<?php echo $value["branch_id"]; ?>" <?php if($value["branch_id"] == @$data["dividend_bank_branch_id"]) { ?> selected="selected"<?php } ?>><?php echo $value["branch_name"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </span>
                                        </div>
                                    </div>

                                    <label class="g24-col-sm-6 control-label" for="">เลขที่บัญชี</label>
                                    <div class=" g24-col-sm-18">
                                        <div class="form-group">
                                            <input id="dividend_acc_num" class="form-control m-b-1 clear_pay" name="dividend_acc_num"  type="text" value="<?php echo @$data["dividend_acc_num"]; ?>">
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="form-group text-center p-y-lg">
                        <button type="button" onclick="check_form()" class="btn btn-primary min-width-100">ตกลง</button>
                        <a href="<?php echo base_url(PROJECTPATH.'/manage_member_share'); ?>" class="btn btn-danger min-width-100">ยกเลิก</a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ข้อมูลสมาชิก</h4>
            </div>
            <div class="modal-body">
                <div class="input-with-icon">
                    <input class="form-control input-thick pill m-b-2" type="text" placeholder="กรอกเลขทะเบียนหรือชื่อ-สกุล" name="search_text" id="search_text" onkeyup="get_search_member()">
                    <span class="icon icon-search input-icon"></span>
                </div>
                <div class="bs-example" data-example-id="striped-table">
                    <table class="table table-striped">

                        <tbody id="table_data">

                        </tbody>

                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
<?php
$link = array(
    'src' => 'ci_project/assets/js/jquery.cookies.2.2.0.min.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
$link = array(
    'src' => 'ci_project/assets/js/manage_member_share.js',
    'type' => 'text/javascript'
);
echo script_tag($link);
?>
<script>
    function get_search_member(){
        $.ajax({
            type: "POST",
            url: base_url+'manage_member_share/get_search_member',
            data: {
                search_text : $("#search_text").val(),
				form_target : 'add'
            },
            success: function(msg) {
                $("#table_data").html(msg);
            }
        });
    }
	function chkNumber(ele){
        var vchar = String.fromCharCode(event.keyCode);
        if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
        ele.onKeyPress=vchar;
    }
</script>