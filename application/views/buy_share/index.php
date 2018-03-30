<?php
function chkBrowser($nameBroser){
    return preg_match("/".$nameBroser."/",$_SERVER['HTTP_USER_AGENT']);
}
?>
<div class="layout-content">
    <div class="layout-content-body">
<style>
    .modal-header-confirmSave {
        padding:9px 15px;
        border:1px solid #0288d1;
        background-color: #0288d1;
        color: #fff;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-top-right-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-topright: 5px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    .modal-header-alert {
        padding:9px 15px;
        border:1px solid #FF0033;
        background-color: #FF0033;
        color: #fff;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-top-right-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-topright: 5px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    .center {
        text-align: center;
    }
    .modal-dialog-account {
        margin:auto;
        margin-top:7%;
    }
    .form-group{
        margin-bottom: 5px;
    }
</style>
<h1 style="margin-bottom: 0">ซื้อหุ้นเพิ่มพิเศษ</h1>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padding-l-r-0">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
        <?php $this->load->view('breadcrumb'); ?>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 padding-l-r-0">
            <a class="link-line-none" class="fancybox_share fancybox.iframe" href="<?php echo base_url(PROJECTPATH.'/buy_share'); ?>" style="float:right;">
                <button class="btn btn-primary btn-lg bt-add" type="button"><span class="icon icon-plus-circle"></span> เพิ่มรายการใหม่</button>
            </a>
    </div>

</div>
    <div class="row gutter-xs">
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-body" style="padding-top:0px !important;">
                <form id="form1" method="POST" action="<?php echo base_url(PROJECTPATH.'/buy_share/save_share'); ?>">
                    <input type="hidden" id="member_id" name="member_id" value="<?php echo $member_id; ?>">
                    <?php $this->load->view('search_member_new'); ?>
                    <div class="" style="padding-top:0;">
                        <h3 >ข้อมูลหุ้น</h3>
                        <div class="g24-col-sm-24">
                            <div class="form-group g24-col-sm-8">
                                <label class="g24-col-sm-10 control-label ">จำนวนหุ้นสะสม</label>
                                <div class="g24-col-sm-14">
                                    <input class="form-control" type="text" name="share_payable" value="<?php echo $count_share; ?>"  readonly>
                                </div>
                            </div>
                            <div class="form-group g24-col-sm-8">
                                <label class="g24-col-sm-10 control-label ">คิดเป็นมูลค่า</label>
                                <div class="g24-col-sm-14">
                                    <input class="form-control" name="share_payable_value" type="text" value="<?php echo $cal_share; ?>"  readonly>
                                </div>
                            </div>
                        </div>
                        <div class="g24-col-sm-24">
                            <div class="form-group g24-col-sm-8">
                                <label class="g24-col-sm-10 control-label ">ต้องการซื้อเพิ่ม</label>
                                <div class="g24-col-sm-14">
                                    <input id="share_early" class="form-control" name="share_early" onkeypress="return chkNumber(this)" onKeyUp="cal_share_result()" type="text" value="">
                                    <input type="hidden" name="share_value" id="share_value" value="<?php echo $share_value; ?>">
                                </div>
                            </div>

                            <div class="form-group g24-col-sm-8">
                                <label class="g24-col-sm-10 control-label ">คิดเป็นมูลค่า</label>
                                <div class="g24-col-sm-14">
                                    <input id="share_early_value" name="share_early_value" class="form-control " type="text" value=""  readonly>
                                </div>
                            </div>

                            <div class="form-group g24-col-sm-8">
                                <div class="g24-col-sm-10">
                                    <button class="btn btn-primary" onclick="return check_form()"><span class="icon icon-save"></span> บันทึก</button>
                                </div>
                            </div>

                        </div>

                    </div>
                    <span style="display:none;"><a class="link-line-none" data-toggle="modal" data-target="#confirmSave" id="confirmSaveModal" class="fancybox_share fancybox.iframe" href="#"></a></span>

                    <span style="display:none;"><a class="link-line-none" data-toggle="modal" data-target="#alert" id="alertModal" class="fancybox_share fancybox.iframe" href="#"></a></span>
                    <input type="hidden" id="delete" name="delete" value="0">
                    <input type="hidden" id="share_id" name="share_id" value="">
                </form>
                <div class="g24-col-sm-24 m-t-1">
                    <div class="bs-example" data-example-id="striped-table">
                        <table class="table table-bordered table-striped table-center">


                            <thead>
                            <tr class="bg-primary">
                                <th>วันที่ทำรายการ</th>
                                <th>รายการ</th>
                                <th >จำนวนหุ้น</th>
                                <th>ยอดเงิน</th>
                                <!--th>หุ้นสะสม</th-->
                                <th>สถานะ</th>
                                <th>เลขที่ใบเสร็จ</th>
                                <th width="20%">ผู้ทำรายการ</th>
                                <th>จัดการ</th>
                            </tr>
                            </thead>

                            <tbody id="result">
                            </tbody>

                            <tbody id="table_first">

                            <?php
                            $share_collect = 0;
                            $share_status = array('0'=>'รอชำระเงิน', '1'=>'ชำระเงินแล้ว', '2'=>'รออนุมัติยกเลิกใบเสร็จ', '3'=>'ยกเลิกใบเสร็จ');
                            foreach($data as $key => $row){
                                $share_collect += $row['share_early'];
                                $share_date = explode('.',$row['share_date']);
                                $share_date = explode(' ',$share_date[0]);
                                $date = explode('-',$share_date[0]);
                                $time = explode(':',$share_date[1]);
                                ?>
                                <tr>
                                    <td><?php echo $date[2]."/".$date[1]."/".($date[0]+543)." ".$time[0].":".$time[1]." น."; ?></td>
                                    <td align="left">ซื้อหุ้นเพิ่ม</td>
                                    <td align="right"><?php echo $row['share_early']; ?></td>
                                    <td align="right"><?php echo $row['share_early_value']; ?></td>
                                    <!--td align="right"><?php echo $share_collect; ?></td-->
                                    <td align="center"><span id="share_status_<?php echo $row['share_id']; ?>"><?php echo $share_status[$row['share_status']]; ?></span></td>
                                    <td align="center"><span id="share_bill_<?php echo $row['share_id']; ?>"><?php echo $row['share_bill']; ?></span></td>
                                    <td align="center"><?php echo $row['user_name']; ?></td>
                                    <td>
                                        <?php if($row['share_status']!='3'){ ?>
                                            <a style="font-size: 16px;" href="<?php echo base_url().PROJECTPATH; ?>/buy_share/receipt_buy_share_temp?member_id=<?php echo $row['member_id']; ?>&num_share=<?php echo $row['share_early']; ?>&value=<?php echo $row['share_early_value']; ?>" alt="ตัวอย่างใบเสร็จ" title="ตัวอย่างใบเสร็จ" target="_blank"><span class="icon icon-file-o" aria-hidden="true"></span></a>
                                            &nbsp;
                                            <a title="ออกใบเสร็จ" alt="ออกใบเสร็จ" style="cursor:pointer;font-size: 17px;" onclick="receipt_process('<?php echo $row['share_id']; ?>');"><span class="icon icon-print" aria-hidden="true"></span></a>
                                            &nbsp;
                                        <?php } ?>
                                        <?php if($row['share_status']=='0'){
                                            $display_1 = "";
                                            $display_2 = "display:none;";
                                        }else if($row['share_status']=='1'){
                                            $display_1 = "display:none;";
                                            $display_2 = "";
                                        }else{
                                            $display_1 = "display:none;";
                                            $display_2 = "display:none;";
                                        } ?>
                                        <a class="link-line-none" id="delete_<?php echo $row['share_id']; ?>" style="font-size: 18px;<?php echo $display_1; ?>" data-toggle="modal" data-target="#confirmDelete" id="confirmDeleteModal" class="fancybox_share fancybox.iframe" href="#" onclick="get_share_id('<?php echo $row['share_id']; ?>')" alt="ลบรายการ" title="ลบรายการ">
                                            <span style="cursor: pointer;" class="icon icon-trash-o"></span></a>

                                        <a class="link-line-none" id="cancel_<?php echo $row['share_id']; ?>" style="font-size: 19px;<?php echo $display_2; ?>" data-toggle="modal" data-target="#confirmCancel" id="confirmCancelModal" class="fancybox_share fancybox.iframe" href="#" alt="ยกเลิกใบเสร็จ" title="ยกเลิกใบเสร็จ" onclick="get_share_id('<?php echo $row['share_id']; ?>')">
                                            <span style="cursor: pointer;" class="icon icon-times-circle-o"></span></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <?php echo $paging ?>
        </div>
    </div>

    </div>
</div>
<?php $this->load->view('search_member_new_modal'); ?>
<div class="modal fade" id="confirmSave"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account">
        <div class="modal-content">
            <div class="modal-header modal-header-confirmSave">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h2 class="modal-title">ยืนยันข้อมูล</h2>
            </div>
            <div class="modal-body center">
                <p><span class="icon icon-arrow-circle-o-down" style="font-size:75px;"></span></p>
                <p style="font-size:18px;">ซื้อหุ้นเพิ่มจำนวน <span id="num_share"></span> หุ้น เป็นเงิน <span id="price_share"></span>  บาท</p>
            </div>
            <div class="modal-footer center">
                <button class="btn btn-info" onclick="submit_form()">ยืนยันการซื้อหุ้น</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmDelete"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account">
        <div class="modal-content">
            <div class="modal-header modal-header-alert">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h2 class="modal-title">ยืนยันการลบรายการ</h2>
            </div>
            <div class="modal-body center">

                <p style="font-size:18px;">ท่านต้องการลบรายการใช่หรือไม่?</p>
            </div>
            <div class="modal-footer center">
                <button class="btn btn-danger" onclick="del_share()">ยืนยัน</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmCancel"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account">
        <div class="modal-content">
            <div class="modal-header modal-header-alert">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h2 class="modal-title">ยืนยันการยกเลิกใบเสร็จ</h2>
            </div>
            <div class="modal-body center">

                <p style="font-size:18px;">ท่านต้องการยกเลิกใบเสร็จใช่หรือไม่?</p>
            </div>
            <div class="modal-footer center">
                <button class="btn btn-danger" onclick="cancel_share()" data-dismiss="modal">ยืนยัน</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="alert"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-account">
        <div class="modal-content">
            <div class="modal-header modal-header-alert">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h2 class="modal-title">กรุณากรอกข้อมูลต่อไปนี้</h2>
            </div>
            <div class="modal-body center">
                <p style="font-size:18px;"><span id="alert_text"></span></p>
            </div>
            <div class="modal-footer center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>
<script>
    function cal_share_result(){
        var share_early = $('#share_early').val();
        var share_value = $('#share_value').val();
        if(share_early!=''){
            $('#share_early_value').val(parseFloat(share_early)*parseFloat(share_value));

            $('#num_share').html(share_early);
            $('#price_share').html($('#share_early_value').val());
        }else{
            $('#num_share').html('');
            $('#price_share').html('');
        }
    }
    function check_form(){
        var alert_text = '';
        if($('#member_id').val()==''){
            alert_text += '- ข้อมูลสมาชิก\n';
        }
        if($('#share_early').val()==''){
            alert_text += '- จำนวนหุ้นที่ต้องการซื้อเพิ่ม\n';
        }
        if(alert_text == ''){
            $("#confirmSaveModal").trigger("click");
        }else{
            //$('#alert_text').html(alert_text);
            //$("#alertModal").trigger("click");
			swal('กรุณากรอกข้อมูลต่อไปนี้',alert_text,'warning');
        }

        return false;
    }
    function submit_form(){
        $('#form1').submit();
    }

    function get_share_id(share_id){
        $('#share_id').val(share_id);
    }

    function del_share(){
        $('#delete').val('1');
        $('#form1').submit();
    }

    function chkNumber(ele){
        var vchar = String.fromCharCode(event.keyCode);
        if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
        ele.onKeyPress=vchar;
    }

    function receipt_process(share_id){
        $.post(base_url+"buy_share/receipt_process",
            {
                receipt_create: "1",
                share_id: share_id
            }
            , function(result){
                $('#share_status_'+share_id).html('ชำระเงินแล้ว');
                $('#share_bill_'+share_id).html(result);
                $('#delete_'+share_id).hide();
                $('#cancel_'+share_id).show();
                window.open(base_url+'admin/receipt_pdf?receipt_id='+result, '_blank');
            });
    }
    function cancel_share(){
        var share_id = $('#share_id').val();
        $.post(base_url+"buy_share/save_share",
            {
                cancel_receipt: "1",
                share_id: share_id
            }
            , function(result){
                $('#cancel_'+share_id).hide();
                $('#share_status_'+share_id).html('รออนุมัติยกเลิกใบเสร็จ');
            });
    }
</script>
