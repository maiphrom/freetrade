<div class="layout-content">
    <div class="layout-content-body">
<style>
    .form-group { margin-bottom: 0; }
    .form-horizontal .control-label { text-align: left; }

    .permission_list { margin: 0 0 20px 0; padding: 0; list-style: none; }
    .permission_list ul { margin: 0 0 0 20px; padding: 0; list-style: none; }
</style>
<?php
$btitle = "แก้ไขข้อมูลส่วนตัว" ;
?>
    <div class="col-md-6 col-md-offset-3">

        <h1 class="text-center m-t-1 m-b-1"><?php echo $btitle; ?></h1>

        <form data-toggle="validator" method="post" action="?" class="form form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="username">Username</label>
                <div class="col-sm-9">
                    <p class="form-control-static m-b-1"><?php echo htmlspecialchars(@$user["username"]); ?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="password">Password</label>
                <div class="col-sm-9">
                    <input type="password" id="password" name="password" class="form-control m-b-1" value="<?php echo htmlspecialchars(@$user["password"]); ?>" required title="กรุณาป้อน Password">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="password">Re Password</label>
                <div class="col-sm-9">
                    <input type="password" id="re_password" name="re_password" class="form-control m-b-1" value="<?php echo htmlspecialchars(@$user["password"]); ?>" required title="Re Password ไม่เหมือนกับ Password" equalTo="#password">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="user_name">ชื่อสกุล</label>
                <div class="col-sm-9">
                    <input type="text" id="user_name" name="user_name" class="form-control m-b-1" value="<?php echo htmlspecialchars(@$user["user_name"]); ?>" required title="กรุณาป้อน ชื่อสกุล">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="user_department">แผนก</label>
                <div class="col-sm-9">
                    <input type="text" id="user_department" name="user_department" class="form-control m-b-1" value="<?php echo htmlspecialchars(@$user["user_department"]); ?>">
                </div>
            </div>

            <div class="form-group text-center p-y-lg">
                <button type="submit" class="btn btn-primary min-width-100">ตกลง</button>
                <a href="?"><button class="btn btn-danger min-width-100" type="button">ยกเลิก</button></a>
            </div>
        </form>

    </div>
    </div>
</div>