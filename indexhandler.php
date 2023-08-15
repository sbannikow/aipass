<?php
if((include 'cnt/content.php')=='OK') echo "OK";
session_start();
$login = "";
$pass = "";
$comp = false;
$i = 0;
if (isset($_POST["input_login"])) {
    $login = htmlspecialchars($_POST["input_login"]);
    $pass = htmlspecialchars($_POST["input_pass"]);
    $conn = new mysqli($hst, $lgn, $psswrd);
    if($conn->connect_error){
      echo "<code>";
      echo "<message>Ошибка подключения к БД: $conn->connect_error</message>";
      echo "</code>";
      $conn->close();
    }
    else{
      $sql = "SELECT id, login, password, u_name, name, l_name, post, status FROM b7_32911471_db_empl.t_empl";
      $conn->set_charset("utf8");
      if($result = $conn->query($sql)){
          while($row = $result->fetch_array()){
            if($row["login"] == $login && $row["password"] == md5($pass)){

              $comp = true;
              $conn->query($sql);
              $_SESSION["USER_ID"] = $row["id"];
              $_SESSION["USER_U_NAME"] = $row["u_name"];
              $_SESSION["USER_NAME"] = $row["name"];
              $_SESSION["USER_L_NAME"] = $row["l_name"];
              $_SESSION["USER_LOGIN"] = $row["login"];
              $_SESSION["USER_STATUS"] = $row["status"];
              $_SESSION["USER_POST"] = $row["post"];
              $_SESSION["is_authorised"] = true;
              $e_post = $row['post'];
              $row = array();
              $conn->close();
              $result->free();
              if($e_post==0) $dest = 'profile.php';
              else $dest = 'adminochka.php';
              echo "<code>";
              echo "<message>Совпадение найдено</message>";
              echo "<post>$e_post</post>";
              echo "<link>$dest</link>";
              echo "</code>";
              break;
              }
              $i++;
            }
            if($comp==false) {
              echo "<code>";
              echo "<message>Логин и пароль не найдены</message>";
              echo "</code>";
            }
            $row = array();
            $conn->close();
            $result->free();
          }
          else{
            echo "<code>";
            echo "<message>Неверный запрос к БД: $sql</message>";
            echo "</code>";
            $conn->close();
          }

        }
  }
  else header("Location: index.php");


?>
