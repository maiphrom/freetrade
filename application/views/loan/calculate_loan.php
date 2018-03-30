<?php
    $day   = date('d');
    $month = date('n');
    $year  = date('Y') + 543;
?>
<div class="panel-body" style="padding:0px; margin:0px;">
<h3 style="padding:0px; margin:0px;">คำนวณสินเชื่อ</h3>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">วงเงินกู้</label>
				<div class="g24-col-sm-5">
					<input type="text" id="loan" onBlur="copy_value('loan', 'loan_amount');re_already_cal()" onkeyup="format_the_number(this)" class="form-control form-loan inline-block loan"/>
				</div>
				<label class="g24-col-sm-1 control-label ">บาท</label>
				<label class="g24-col-sm-3 control-label ">อัตราดอกเบี้ย</label>
				<div class="g24-col-sm-5">
					<input type="number" id="interest" class="form-control form-loan interest_rate" step="0.01" value="" readonly>
				</div>
				<label class="g24-col-sm-1 control-label ">%</label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label ">จำนวน</label>
				<div class="g24-col-sm-5">
					<select name="data[coop_loan][period_type]" id="period_type" class="form-control">
					  <option value="1"> งวดที่ต้องการผ่อน </option>
					  <option value="2"> เงินที่ต้องการผ่อนต่องวด </option>
					</select>
				</div>
				<div class="g24-col-sm-5">
					<input type="nuumber" id="period" onkeyup="format_the_number(this)" class="form-control form-loan inline-block" />
				</div>
				<label class="g24-col-sm-1 control-label " id="type_period"></label>
			</div>
			<div class="g24-col-sm-24 modal_data_input">
				<label class="g24-col-sm-4 control-label " >วันที่เริ่มคำนวณ</label>
				<div class="input-with-icon g24-col-sm-5">
					<div class="form-group">
						<input id="apply_date" name="apply_date" class="form-control m-b-1" style="padding-left: 50px;" type="text" value="<?php echo $this->center_function->mydate2date(date('Y-m-d')); ?>" data-date-language="th-th" required title="" >
						<span class="icon icon-calendar input-icon m-f-1"></span>
					</div>
				</div>
			</div>
			<div class="g24-col-sm-24 modal_data_input" style="display:none;">
				<label class="g24-col-sm-4 control-label ">ประเภทการชำระเงิน</label>
				<div class="g24-col-sm-5">
					<select id="pay_type" name="data[coop_loan][pay_type]"  class="form-control">
						<option value="1" selected>ชำระต้นเท่ากันทุกงวด</option>
						<option value="2">ชำระยอดเท่ากันทุกงวด</option>
					</select>
				</div>
			</div>
			<div class="center" style="margin-top: 5px;margin-bottom: 15px;">
				<input type="button" class="btn btn-primary btn-calculate" value="คำนวณ"> 
				<input type="button" class="btn btn-primary" onclick="close_modal('cal_period_normal_loan')" value="เลือกใช้ค่าคำนวณ">
			</div>
			<div id="result_wrap"></div>
</div>
  <div class="modal fade" id="alertLaon" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-header-info">
          <button type="button" class="close" onclick="close_modal('alertLaon')">&times;</button>
          <h4 class="modal-title">
            <h4>แจ้งเตือน</h4>
          </h4>
        </div>
        <div class="modal-body">
          <p style="font-size:18px;" id="alert_space"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick="close_modal('alertLaon')">Close</button>
        </div>
      </div>
    </div>
  </div>

<script>
	Number.prototype.format = function(n, x, s, c) {
	    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
		    num = this.toFixed(Math.max(0, ~~n));
	    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
	function get_data_from_modal(){
		 $('.period_amount').val($('#max_period').val());
		 $('.date_start_period_label').val($('#first_date_period_label').val());
		 $('.date_start_period').val($('#first_date_period').val());
		 $('#last_date_period').val($('#last_period').val());
		 
		 $('#date_period_1').val($('#first_date_period_label').val());
		 $('#date_period_2').val($('#second_date_period_label').val());
		 $('#money_period_1').val($('#first_pay').val());
		 $('#money_period_2').val($('#second_pay').val());
	}
	function cal(){
		var date = $('#apply_date').val().split('/');
		var day = date[0];
		var month = date[1];
		var year = date[2];
		
      $.ajax({
				type: "POST"
				, url: base_url+"/loan/ajax_calculate_loan"
				, data: {
						"ajax" : 1
						, "do" : "cal"
						, "loan" : $("#loan").val().replace(/,/g, "")
						, "pay_period" : $("#pay_period").val()
						, "pay_type" : $("#pay_type").val()
						, "day" : day
						, "month" : month
						, "year" : year
						, "period_type" : $("#period_type").val()
						, "period" : $("#period").val().replace(/,/g, "")
						, "interest" : $("#interest").val()
						, "_time" : Math.random()
						, "loan_type" : $("#loan_type").val()
				}
				, async: true
				, success: function(msg) {
					$("#result_wrap").html(msg);
					get_data_from_modal();
				}
			});
		}
	$('document').ready(function() {
		$(".btn-calculate").click(function(e){
         if($.trim($('#loan').val()) == '' || $.trim($('#period').val()) == '' || $.trim($('#apply_date').val()) == ''){
			 var alert_text = '';
			 if($.trim($('#loan').val()) == ''){
				alert_text += '- กรุณากรอกจำนวนวงเงินกู้\n';
			 }
			 if($.trim($('#period').val()) == '') {
				alert_text += '- กรุณากรอกจำนวนงวด หรือ จำนวนเงินต่องวด\n';
			 }
			 if($.trim($('#apply_date').val()) == '') {
				alert_text += '- กรุณากรอกวันที่เริ่มชำระเงิน\n';
			 }
			 swal(alert_text);
         } else {
            cal();
         }
    });

    $("#select_interest option").filter(function() {
      return $(this).val() == $("#interest").val();
    }).attr('selected', true);

    $("#select_interest").live("change", function() {
        $("#interest").val($(this).find("option:selected").attr("value"));
    });

    $('#loan').keyup(function(event) {
      if(event.which >= 37 && event.which <= 40) return;
      /*$(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
      });*/
    });

    $( "#period_type" )
    .change(function () {
      var str = " ";
      $( "#period_type option:selected" ).each(function() {
        if ($(this).val() == 1) {
          str += "งวด";
        } else {
          str += "บาท";
        }
      });
      $( "#type_period" ).text( str );
    })
    .change();

	
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

	});
	
</script>
