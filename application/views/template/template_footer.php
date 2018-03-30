<?php
	
      $link = array(
          'src' => 'ci_project/assets/js/elephant.min.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
	$link = array(
          'src' => 'ci_project/assets/js/application.min.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
	  $link = array(
          'src' => 'ci_project/assets/js/jquery-migrate-1.4.1.min.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/bootstrap-datepicker/bootstrap-datepicker-thai.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/bootstrap-datepicker/locales/bootstrap-datepicker.th.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);

      $link = array(
          'src' => 'ci_project/assets/js/fancybox/jquery.fancybox.js?v=2.1.5',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/fancybox/jquery.mousewheel-3.0.6.pack.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);

      $link = array(
          'src' => 'ci_project/assets/js/toast.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);

      $link = array(
          'src' => 'ci_project/assets/js/jquery.blockUI.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/sweetalert.min.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/moment.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'ci_project/assets/js/bootstrap-datetimepicker.min.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);

		$link = array(
          'src' => 'ci_project/assets/js/center_js.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
		$link = array(
			'src' => 'ci_project/assets/js/jquery.number_format.js',
			'type' => 'text/javascript'
		);
		echo script_tag($link);
?>
    <div class="layout-footer">
        <div class="layout-footer-body">
            <small class="version">Version 1.0.0</small>
            <small class="copyright">UpbeanCOOP by <a href="http://upbean.co.th/">Upbean Co.,Ltd</a></small>
        </div>
    </div>
</div>

<div class="theme">
    <div class="theme-panel theme-panel-collapsed">
        <div class="theme-panel-body">
            <div class="custom-scrollbar">
                <div class="custom-scrollbar-inner">
                    <ul class="theme-settings">
                        <li class="theme-settings-heading">
                            <div class="divider">
                                <div class="divider-content">Theme Settings</div>
                            </div>
                        </li>
                        <li class="theme-settings-item">
                            <div class="theme-settings-label">Header fixed</div>
                            <div class="theme-settings-switch">
                                <label class="switch switch-primary">
                                    <input class="switch-input" type="checkbox" name="layout-header-fixed" data-sync="true">
                                    <span class="switch-track"></span>
                                    <span class="switch-thumb"></span>
                                </label>
                            </div>
                        </li>
                        <li class="theme-settings-item">
                            <div class="theme-settings-label">Sidebar fixed</div>
                            <div class="theme-settings-switch">
                                <label class="switch switch-primary">
                                    <input class="switch-input" type="checkbox" name="layout-sidebar-fixed" data-sync="true">
                                    <span class="switch-track"></span>
                                    <span class="switch-thumb"></span>
                                </label>
                            </div>
                        </li>
                        <li class="theme-settings-item">
                            <div class="theme-settings-label">Sidebar sticky*</div>
                            <div class="theme-settings-switch">
                                <label class="switch switch-primary">
                                    <input class="switch-input" type="checkbox" name="layout-sidebar-sticky" data-sync="true">
                                    <span class="switch-track"></span>
                                    <span class="switch-thumb"></span>
                                </label>
                            </div>
                        </li>
                        <li class="theme-settings-item">
                            <div class="theme-settings-label">Sidebar collapsed</div>
                            <div class="theme-settings-switch">
                                <label class="switch switch-primary">
                                    <input class="switch-input" type="checkbox" name="layout-sidebar-collapsed" data-sync="false">
                                    <span class="switch-track"></span>
                                    <span class="switch-thumb"></span>
                                </label>
                            </div>
                        </li>
                        <li class="theme-settings-item">
                            <div class="theme-settings-label">Footer fixed</div>
                            <div class="theme-settings-switch">
                                <label class="switch switch-primary">
                                    <input class="switch-input" type="checkbox" name="layout-footer-fixed" data-sync="true">
                                    <span class="switch-track"></span>
                                    <span class="switch-thumb"></span>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    $( document ).ready(function() {
        $('#myModal').on('shown.bs.modal', function() {
            $('#search_mem').focus();
        });
        $('.modal').on('shown.bs.modal', function() {
            $.blockUI({
                message: '',
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                },
                baseZ: 2000,
                bindEvents: false
            });
        });
        var prev_id;
        $('.modal').on('hide.bs.modal', function () {
            if(this.id != 'cal_period_normal_loan' && this.id != 'show_file_attach' && this.id != 'search_member_loan_modal'){
                $.unblockUI();
            }

        });

        // Toast
        var toast = "<?php echo isset($_COOKIE['toast']) ? $_COOKIE['toast'] : "" ?>";
        if(toast) {
            toastNotifications(toast);
        }
        // Toast Danger
        var toast_e = "<?php echo isset($_COOKIE['toast_e']) ? $_COOKIE['toast_e'] : "" ?>";
        if(toast_e) {
            toastDanger(toast_e);
        }
    });

</script>
<!-- search_member -->
<?php
    $link = array(
        'src' => 'ci_project/assets/js/search_member.js',
        'language' => 'javascript',
        'type' => 'text/javascript'
    );
    echo script_tag($link);
?>
<!-- search_member -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <strong></strong>
                </div>
                <div></div>
            </footer>
    </body>
</html>
