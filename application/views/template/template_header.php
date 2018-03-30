<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
  <meta name="theme-color" content="#ffffff">
      <?php
      $link = array(
          'href' => 'ci_project/assets/images/logo/favicon.png',
          'rel' => 'shortcut icon'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/vendor.min.css',
          'rel' => 'stylesheet',
          'type' => 'text/css'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/elephant.min.css',
          'rel' => 'stylesheet',
          'type' => 'text/css'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/application.min.css',
          'rel' => 'stylesheet',
          'type' => 'text/css'
      );
      echo link_tag($link);
      //fancybox
      $link = array(
          'href' => 'ci_project/assets/css/fancybox/jquery.fancybox.css?v=2.1.5',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5',
          'rel' => 'stylesheet',
          'type' => 'text/css'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7',
          'rel' => 'stylesheet',
          'type' => 'text/css'
      );
      echo link_tag($link);
      //fancybox
      $link = array(
          'href' => 'ci_project/assets/css/custom.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/sweetalert.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/bootstrap-datetimepicker.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/jquery-ui-timepicker-addon.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/jquery-ui/jquery-ui.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
      $link = array(
          'href' => 'ci_project/assets/css/custom-grid24.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
	  $link = array(
          'href' => 'ci_project/assets/css/font-awesome/css/font-awesome.min.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);
	  $link = array(
          'href' => 'ci_project/assets/css/fonts/upbean/upbean.css',
          'rel' => 'stylesheet',
          'type' => 'text/css',
          'media'=>'screen'
      );
      echo link_tag($link);

      /*$link = array(
          'href' => 'assets/css/dataTables.bootstrap.min.css',
          'rel' => 'stylesheet',
          'type' => 'text/css'
      );
      echo link_tag($link);
      $link = array(
          'src' => 'assets/js/jquery.dataTables.min.js',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      $link = array(
          'src' => 'assets/js/dataTables.bootstrap.min.js',
          'type' => 'text/javascript'
      );
      echo script_tag($link);*/
      
      
      $link = array(
          'src' => 'ci_project/assets/js/vendor.min.js',
          'language' => 'javascript',
          'type' => 'text/javascript'
      );
      echo script_tag($link);
      ?>
    </head>
    <body class="<?php echo $body_class;?>">
    <div id="base_url" class="<?php echo base_url()."ci_project/"; ?>"></div>
