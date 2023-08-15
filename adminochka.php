<!DOCTYPE PHP PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.aidentica.tech">
<html>
<head>
<title>AdminOchka</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0;">
<link rel='stylesheet' href='stylesheets/profile.css?v=0.0021'>
</head>
<html>
<body>
<div class = "main_canvas">
  <div class = "caption_admin">Администратор<div class='admin_exit_canvas'>
    <div class='admin_exit_canvas_1'><div class='admin_exit_canvas_2'></div></div><div class="admin_exit_canvas_3">выход</div>
  </div></div>
<div class = "main_topper">
  <div class = "topper">
    <div class = "topper_left"></div>
    <div class = "topper_right"></div>
    <div class = "topper_status">
      <div class="status_date"></div>
      <div class ="status_str"></div>
    </div>
  </div>
  <div class = "caption_message"></div>
</div>
  <div class="record_table">
  </div>
</div>
</body>

<script type = "text/javascript" src = "js/jquery-3.6.1.js"></script>
<script type = "text/javascript" src = "js/admin_script.js?v=0.00000032"></script>
<script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<?php
session_start();
if(!$_SESSION['is_authorised']) {header("Location: index.php");session_destroy();}
else{
  if($_SESSION['USER_POST']!=1) header("Location: profile.php");
  else{
  $user_login = $_SESSION["USER_LOGIN"];

  }
}
?>
</html>
