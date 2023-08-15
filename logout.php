<!DOCTYPE PHP PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.aidentica.tech">
<html>
<head>
<title>logout</title>
<meta charset="utf-8">
</head>
<html>
<?php
session_start();

  $_SESSION['is_authorised'] = false;
  session_destroy();
  header("Location: index.php");
 ?>
</html>
