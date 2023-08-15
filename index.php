<!DOCTYPE PHP PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.aidentica.tech">
<html>
<head>
<title>Aidentica Pass</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0;">
<link rel='stylesheet' href='stylesheets/st_main.css?v=0.00017'>
</head>
     <body id = "id_back">
       <center>
       <div class = 'canvas_logo'>
       <div class = 'logo1'>Ai</div>
       <div class = 'logo2'>pass</div>
       </div>
       <div class = 'canvas_form'>
         <form id="main_form">
         <div class = 'caps'>Логин</div>
         <input class = 'cl_input' name = 'input_login'>
         <div class = 'caps'>Пароль</div>
         <input class = 'cl_input' name = 'input_pass'><br>
         <button type = 'submit' class = 'cl_button'>Войти</button>
         <div class = "id_message"></div>
       </form>
       </div>
       </center>
     </body>
     <script type = "text/javascript" src = "js/jquery-3.6.1.js"></script>
     <script type = "text/javascript" src = "js/index_script.js?v=0.00000109"></script>

     <?php
     session_start();
     if($_SESSION['is_authorised']){
       if($_SESSION['USER_ID']==0) header("Location: adminochka.php");
       else header("Location: profile.php");
     }
     ?>
</html>
