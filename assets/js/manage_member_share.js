var base_url = $('#base_url').attr('class');
$( document ).ready(function() {
    $("#is_fix_member_id").click(function() {
        var mem = $(this).data('mem');
        if($(this).prop('checked')) {
            $("#member_id").val("");
            $("#member_id").prop("readonly", false);
            $("#member_id").focus();
        }
        else {
            $("#member_id").val(mem);
            $("#member_id").prop("readonly", true);
        }
    });

    $("#apply_date").datepicker({
        prevText : "ก่อนหน้า",
        nextText: "ถัดไป",
        currentText: "Today",
        changeMonth: true,
        changeYear: true,
        isBuddhist: true,
        monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
        constrainInput: true,
        dateFormat: "dd/mm/yy",
        yearRange: "c-50:c+10",
		autoclose: true,
    });

    $("#birthday").datepicker({
        prevText : "ก่อนหน้า",
        nextText: "ถัดไป",
        currentText: "Today",
        changeMonth: true,
        changeYear: true,
        isBuddhist: true,
        monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
        constrainInput: true,
        dateFormat: "dd/mm/yy",
        yearRange: "c-50:c+10",
		autoclose: true,
    });

    $("#birthday").change(function() {
        var arr_date = $(this).val().split("/");
        $("#age").val(getAge(new Date((arr_date[2] - 543), arr_date[1], arr_date[0])));
        $("#birthday").css("border-color", "#757575");
        $("#birthday_con small").remove();
        $("#birthday_border").removeClass("has-error");

    });

    $("#btn_member_pic").click(function() {
        $.fancybox({
            'href' : base_url+'manage_member_share/member_lb_upload'
            , 'padding' : '10'
            , 'width': 520
            , 'modal' : true
            , 'type' : 'iframe'
            , 'autoScale' : false
            , 'transitionIn' : 'none'
            , 'transitionOut' : 'none'
            , afterClose : function() {
                console.log($.cookies);
                if($.cookies.get('is_upload'))
                    get_image();
            }
        });

        return false;
    });
});

function getAge(d1, d2){
    d2 = d2 || new Date();
    var diff = d2.getTime() - d1.getTime();
    var age = Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
    return isNaN(diff) ? "" : (age < 0 ? 0 : age);
}

function change_province(id, id_to, id_input_amphur, district_space, id_input_district){
    var province_id = $('#'+id).val();
    $.ajax({
        method: 'POST',
        url: base_url+'manage_member_share/get_amphur_list',
        data: {
            province_id : province_id,
            id_input_amphur : id_input_amphur,
            district_space : district_space,
            id_input_district : id_input_district
        },
        success: function(msg){
            $('#'+id_to).html(msg);
        }
    });
}

function change_amphur(id, id_to, id_input_district){
    var amphur_id = $('#'+id).val();
    $.ajax({
        method: 'POST',
        url: base_url+'manage_member_share/get_district_list',
        data: {
            amphur_id : amphur_id,
            id_input_district : id_input_district
        },
        success: function(msg){
            $('#'+id_to).html(msg);
        }
    });
}

function change_bank(){
    var bank_id = $('#dividend_bank_id').val();
    $('#bank_id_show').val(bank_id);
    $('#branch_id_show').val('');
    $.ajax({
        method: 'POST',
        url: base_url+'manage_member_share/get_bank_branch_list',
        data: {
            bank_id : bank_id
        },
        success: function(msg){
            $('#bank_branch').html(msg);
        }
    });
}
function change_branch(){
    var branch_id = $('#dividend_bank_branch_id').val();
    $('#branch_id_show').val(branch_id);
}

function change_mem_group(id, id_to){
    var mem_group_id = $('#'+id).val();
    $.ajax({
        method: 'POST',
        url: base_url+'manage_member_share/get_mem_group_list',
        data: {
            mem_group_id : mem_group_id
        },
        success: function(msg){
            $('#'+id_to).html(msg);
        }
    });
}

function dupp_address(ele){
    if($(ele).prop('checked')) {
        $('#c_address_no').val($('#address_no').val());
        $('#c_address_moo').val($('#address_moo').val());
        $('#c_address_village').val($('#address_village').val());
        $('#c_address_soi').val($('#address_soi').val());
        $('#c_address_road').val($('#address_road').val());
        $('#c_province_id').val($('#province_id').val());
        $('#c_amphur_id').html($('#amphur_id').html());
        $('#c_amphur_id').val($('#amphur_id').val());
        $('#c_district_id').html($('#district_id').html());
        $('#c_district_id').val($('#district_id').val());
        $('#c_zipcode').val($('#zipcode').val());
    }
}

function get_image() {
    $.ajax({
        type: "POST"
        , url: base_url+'manage_member_share/get_image'
        , data: {
            "do" : "get_image"
            , _time : Math.random()
        }
        , success: function(data) {
            $("#member_pic").attr("src", data);
        }
    });
}

function change_mem_type(id){
    if(id!=''){
        $.ajax({
            method: 'POST',
            url: base_url+'manage_member_share/check_resign_date',
            data: { id : id},
            success: function(msg){
                if(msg == 'success'){
                    $('#member_time_select').val('2');
                    $('#member_time').val('2');
                }else{
                    $('#mem_type').val('2');
                    swal('ไม่สามารถเข้าใหม่ได้ ',msg,'warning');

                }
            }
        });
    }else{
        $('#mem_type').val('1');
    }
}

function check_form(){
    var text_alert = '';
    var text_id_card_alert = '';
    var id_card = $('#id_card').val();
    var old_id_card = $('#old_id_card').val();
    var id_to_focus = [];
    var i=0;
    if(id_card != old_id_card){
        $.ajax({
            method: 'POST',
            url: base_url+'manage_member_share/check_register',
            data: { id_card : id_card},
            success: function(msg){
                if(msg == 'success'){
                    //$('#myForm').submit();
                }else{
                    text_id_card_alert = msg;
                }
            }
        });
    }
    if($('#apply_date').val() == ''){
        text_alert += 'วันที่สมัครสมาชิก\n';
        id_to_focus[i] = 'apply_date';
        i++;
    }
    if($('#employee_id').val() == ''){
        text_alert += 'รหัสพนักงาน\n';
        id_to_focus[i] = 'employee_id';
        i++;
    }
    if($('#firstname_th').val() == ''){
        text_alert += 'ชื่อ(ภาษาไทย)\n';
        id_to_focus[i] = 'firstname_th';
        i++;
    }
    if($('#lastname_th').val() == ''){
        text_alert += 'สกุล(ภาษาไทย)\n';
        id_to_focus[i] = 'lastname_th';
        i++;
    }
    if($('#mobile').val() == ''){
        text_alert += 'เบอร์มือถือ\n';
        id_to_focus[i] = 'mobile';
        i++;
    }
    if($('#address_no').val() == ''){
        text_alert += 'บ้านเลขที่(ทะเบียนบ้าน)\n';
        id_to_focus[i] = 'address_no';
        i++;
    }
    if($('#province_id').val() == ''){
        text_alert += 'จังหวัด(ทะเบียนบ้าน)\n';
        id_to_focus[i] = 'province_id';
        i++;
    }
    if($('#amphur_id').val() == ''){
        text_alert += 'อำเภอ(ทะเบียนบ้าน)\n';
        id_to_focus[i] = 'amphur_id';
        i++;
    }
    if($('#district_id').val() == ''){
        text_alert += 'ตำบล(ทะเบียนบ้าน)\n';
        id_to_focus[i] = 'district_id';
        i++;
    }
    if($('#c_address_no').val() == ''){
        text_alert += 'บ้านเลขที่(ปัจจุบัน)\n';
        id_to_focus[i] = 'c_address_no';
        i++;
    }
    if($('#c_province_id').val() == ''){
        text_alert += 'จังหวัด(ปัจจุบัน)\n';
        id_to_focus[i] = 'c_province_id';
        i++;
    }
    if($('#c_amphur_id').val() == ''){
        text_alert += 'อำเภอ(ปัจจุบัน)\n';
        id_to_focus[i] = 'c_amphur_id';
        i++;
    }
    if($('#c_district_id').val() == ''){
        text_alert += 'ตำบล(ปัจจุบัน)\n';
        id_to_focus[i] = 'c_district_id';
        i++;
    }
    if($('#id_card').val() == ''){
        text_alert += 'เลขบัตรประชาชน\n';
        id_to_focus[i] = 'id_card';
        i++;
    }
    if($('#nationality').val() == ''){
        text_alert += 'สัญชาติ\n';
        id_to_focus[i] = 'nationality';
        i++;
    }
    if($('#birthday').val() == ''){
        text_alert += 'วันเกิด\n';
        id_to_focus[i] = 'birthday';
        i++;
    }
    if($('#father_name').val() == ''){
        text_alert += 'ชื่อบิดา\n';
        id_to_focus[i] = 'father_name';
        i++;
    }
    if($('#mother_name').val() == ''){
        text_alert += 'ชื่อมารดา\n';
        id_to_focus[i] = 'mother_name';
        i++;
    }
    if($('#position').val() == ''){
        text_alert += 'ตำแหน่ง\n';
        id_to_focus[i] = 'position';
        i++;
    }
    if($('#department').val() == ''){
        text_alert += 'ฝ่าย\n';
        id_to_focus[i] = 'department';
        i++;
    }
    if($('#faction').val() == ''){
        text_alert += 'แผนก\n';
        id_to_focus[i] = 'faction';
        i++;
    }
    if($('#level').val() == ''){
        text_alert += 'หน่วยงาน\n';
        id_to_focus[i] = 'level';
        i++;
    }
    if($('#work_id_card').val() == ''){
        text_alert += 'เลขพนักงาน\n';
        id_to_focus[i] = 'work_id_card';
        i++;
    }
    if($('#salary').val() == ''){
        text_alert += 'เงินเดือน\n';
        id_to_focus[i] = 'salary';
        i++;
    }
    if($('#other_income').val() == ''){
        text_alert += 'เงินอื่นๆ\n';
        id_to_focus[i] = 'other_income';
        i++;
    }

    text_alert = '';
    if(text_id_card_alert != '') {
        $('#id_card').focus();
        swal('ไม่สามารถสมัครสมาชิกได้ ',text_id_card_alert,'warning');
    }else if(text_alert != ''){
        $('#'+ id_to_focus[0]).focus();
        swal('กรุณากรอกข้อมูลต่อไปนี้',text_alert,'warning');

    }else{
        $('#myForm').submit();
    }
}