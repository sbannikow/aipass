<!DOCTYPE PHP PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.aidentica.tech">
<html>
<head>
<title>AI Pass Profile</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0;">
<link rel='stylesheet' href='stylesheets/profile.css?v=0.0018'>
</head>
<html>
<body>
<div class = "main_canvas">
<div class = "main_topper">
  <div class = "topper">
    <div class = "topper_left"></div>
    <div class = "topper_right"></div>
    <div class = "topper_status">
      <div class="status_date"></div>
      <div class ="status_str"></div>
    </div>
  </div>
  <div class = "buttons">
    <div class="button_check"></div>
    <div class = "button_menu"></div>
  </div>
  <div class="drop_down_menu">
    <div b_action ="currMonth" class = "drop_down_menu_item">Отчет за текущий месяц</div>
    <div b_action ="prevMonth" class = "drop_down_menu_item">Отчет за предыдущий месяц</div>
    <div b_action="exit" class = "drop_down_menu_item">Выход</div>

  </div>
  <div class = "caption_message"></div>
</div>
  <div class="record_table"></div>
</div>
</body>

<script type = "text/javascript" src = "js/jquery-3.6.1.js"></script>
<script type = "text/javascript" src = "js/profile_script.js?v=0.00000029"></script>
<script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<?php
session_start();
if(!$_SESSION['is_authorised']) {header("Location: index.php");session_destroy();}
else{
  if($_SESSION["USER_POST"]==1)header("Location: adminochka.php");
  $user_name = $_SESSION["USER_NAME"];
  $user_l_name = $_SESSION["USER_L_NAME"];
  $user_login = $_SESSION["USER_LOGIN"];
  $user_status = $_SESSION["USER_STATUS"];
  echo "<script type = 'text/javascript'>
      var user_login='$user_login';
      var user_name='$user_name';
      var user_lName = '$user_l_name';
      var user_status = '$user_status';
  </script>";
}
?>
</html>
