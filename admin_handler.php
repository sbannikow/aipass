<?php
include "cnt/content.php";
session_start();
if(!($_SESSION['is_authorised'])){
  session_destroy();
  header("Location: index.php");
}
else{
  if($_SESSION['USER_POST']=='1'){
      $j_request = $_POST['rst'];
      if($j_request=='getEstatus'){
        $conn = new mysqli($hst, $lgn, $psswrd);
        if($conn->connect_error){
          echo "<code><message>$conn->connect_error</message></code>";
        $conn->close();
        }
        else{
          $conn->set_charset("utf8");
          $sql = "SELECT u_name, name, l_name, status, post FROM b7_32911471_db_empl.t_empl WHERE post = 0";

          if($result = $conn->query($sql)){
            echo "<code>";
            while($row = $result->fetch_array()){
              $name = $row['name'];
              $l_name = $row['l_name'];
              $u_name = $row['u_name'];
              $status = $row['status'];
              echo "<employee>";
              echo "<e_name>$name</e_name>";
              echo "<e_lname>$l_name</e_lname>";
              echo "<e_uname>$u_name</e_uname>";
              echo "<e_status>$status</e_status>";
              echo "</employee>";
            }
            echo "<message>Данные сотрудников загружены</message>";
            echo "</code>";

            $conn->close();
            $result->free();
          }
          else echo "<code><message>Неверный запрос БД</message></code>";
        }
      }


      if($j_request == "getCurrMonth"){
        $conn = new mysqli($hst, $lgn, $psswrd);
        $sql = array();
        if($conn->connect_error){
          echo "<code><message>Произошла ошибка запроса к БД. $conn->connect_error</message></code>";
        $conn->close();
        }
        else {
          $curr_month = $_POST["month"];
          $curr_month +=1;
          $curr_year = $_POST["year"];
          $user_u_name = $_POST["uname_attr"];
          $s_month = dToMonth($curr_month);
          $sql = "SELECT login, name, l_name, status FROM b7_32911471_db_empl.t_empl WHERE u_name = '$user_u_name'";
          $conn->set_charset("utf8");
          $result = $conn->query($sql);
            if($result) {
              $row = $result->fetch_array();
              $user_name = $row['name'];
              $user_lname = $row['l_name'];
              $login = $row['login'];
              $user_status = $row['status'];
            }
          $sql = "SELECT id, arr_day, arr_month, arr_hour, arr_min, dep_day, dep_month, dep_hour, dep_min FROM b7_32911471_db_records.".$user_u_name."_".$curr_year." WHERE arr_month=".$curr_month." ORDER BY id DESC";
          $result = $conn->query($sql);
          if(mysqli_fetch_row($conn->query($sql))==NULL) {
            echo "<code><repDate>$s_month  $curr_year</repDate><message>Записей на текщий месяц пока нет.</message>";
            echo "<name>$user_name</name>";
            echo "<lname>$user_lname</lname>";
            echo "<login>$login</login>";
            echo "<status>$user_status</status>";
            echo "</code>";
          }
          else{
          if($result) {
            $total = 0;
            echo "<code>";
            while($row = $result->fetch_array()){
              $r_id = $row["id"];$r_aDay = $row["arr_day"];$r_aMonth = $row["arr_month"];$r_aHour = $row["arr_hour"];$r_aMin = $row["arr_min"];
              $r_dDay = $row["dep_day"];$r_dMonth=$row["dep_month"];$r_dHour = $row["dep_hour"];$r_dMin = $row["dep_min"];
              if($r_dDay!=$r_aDay) $amount = (((($r_dHour+24)-$r_aHour)*60) + ($r_dMin-$r_aMin))/60;
              else $amount = ((($r_dHour-$r_aHour)*60) + ($r_dMin-$r_aMin))/60;
              if($r_aHour<10)$r_aHour="0".$r_aHour;
              if($r_aMin<10)$r_aMin="0".$r_aMin;
              if($r_dHour<10)$r_dHour="0".$r_dHour;
              if($r_dMin<10)$r_dMin="0".$r_dMin;
              if($r_dDay<10)$r_dDay="0".$r_dDay;
              if($r_dMonth<10)$r_dMonth="0".$r_dMonth;
              if($r_aDay<10)$r_aDay="0".$r_aDay;
              if($r_aMonth<10)$r_aMonth="0".$r_aMonth;
              echo "<record>";
              echo "<id>$r_id</id>";
              echo "<arr_date>$r_aDay.$r_aMonth.$curr_year</arr_date>";
              echo "<arr_time>$r_aHour:$r_aMin</arr_time>";
              echo "<dep_date>$r_dDay.$r_dMonth.$curr_year</dep_date>";
              echo "<dep_time>$r_dHour:$r_dMin</dep_time>";
              printf("<amount>%.2f</amount>", $amount);
              echo "</record>";
              $total += $amount;
            }
            echo "<name>$user_name</name>";
            echo "<lname>$user_lname</lname>";
            echo "<login>$login</login>";
            echo "<status>$user_status</status>";
            echo "<repDate>$s_month  $curr_year</repDate>";
            printf("<total>%.2f</total>", $total);
            echo "<message>Отчет за текущий месяц загружен</message>";
            echo "</code>";
          }
          else echo "<code><repDate>$s_month  $curr_year</repDate><message>Записей на текщий месяц пока нет.</message></code>";
        }
        }
        $result->free();
        $conn->close();
      }


      if($j_request == "getPrevMonth"){
        $conn = new mysqli($hst, $lgn, $psswrd);
        $sql = array();
        if($conn->connect_error){
          echo "<code><message>Произошла ошибка запроса к БД. $conn->connect_error</message></code>";
        $conn->close();
        }
        else {
          $curr_month = $_POST["month"];
          $curr_year = $_POST["year"];
          if(($curr_month - 1)<0){$curr_year -= 1; $curr_month = 12;}
          $user_u_name = $_SESSION["USER_U_NAME"];
          $s_month = dToMonth($curr_month);
          $user_u_name = $_POST["uname_attr"];
          $s_month = dToMonth($curr_month);
          $sql = "SELECT login, name, l_name, status FROM b7_32911471_db_empl.t_empl WHERE u_name = '$user_u_name'";
          $conn->set_charset("utf8");
          $result = $conn->query($sql);
            if($result) {
              $row = $result->fetch_array();
              $user_name = $row['name'];
              $user_lname = $row['l_name'];
              $login = $row['login'];
              $user_status = $row['status'];
            }
          $sql = "SELECT id, arr_day, arr_month, arr_hour, arr_min, dep_day, dep_month, dep_hour, dep_min FROM b7_32911471_db_records.".$user_u_name."_".$curr_year." WHERE arr_month=".$curr_month." ORDER BY id DESC";
          $result = $conn->query($sql);
          if(mysqli_fetch_row($conn->query($sql))==NULL) {
            echo "<code><repDate>$s_month  $curr_year</repDate><message>Отчетов за данный период не найдено.</message>";
            echo "<name>$user_name</name>";
            echo "<lname>$user_lname</lname>";
            echo "<login>$login</login>";
            echo "<status>$user_status</status>";
            echo "</code>";
          }
          else{
          if($result) {
            $total = 0;
            echo "<code>";
            while($row = $result->fetch_array()){
              $r_id = $row["id"];$r_aDay = $row["arr_day"];$r_aMonth = $row["arr_month"];$r_aHour = $row["arr_hour"];$r_aMin = $row["arr_min"];
              $r_dDay = $row["dep_day"];$r_dMonth=$row["dep_month"];$r_dHour = $row["dep_hour"];$r_dMin = $row["dep_min"];
              if($r_dDay!=$r_aDay) $amount = (((($r_dHour+24)-$r_aHour)*60) + ($r_dMin-$r_aMin))/60;
              else $amount = ((($r_dHour-$r_aHour)*60) + ($r_dMin-$r_aMin))/60;
              if($r_aHour<10)$r_aHour="0".$r_aHour;
              if($r_aMin<10)$r_aMin="0".$r_aMin;
              if($r_dHour<10)$r_dHour="0".$r_dHour;
              if($r_dMin<10)$r_dMin="0".$r_dMin;
              if($r_dDay<10)$r_dDay="0".$r_dDay;
              if($r_dMonth<10)$r_dMonth="0".$r_dMonth;
              if($r_aDay<10)$r_aDay="0".$r_aDay;
              if($r_aMonth<10)$r_aMonth="0".$r_aMonth;
              echo "<record>";
              echo "<id>$r_id</id>";
              echo "<arr_date>$r_aDay.$r_aMonth.$curr_year</arr_date>";
              echo "<arr_time>$r_aHour:$r_aMin</arr_time>";
              echo "<dep_date>$r_dDay.$r_dMonth.$curr_year</dep_date>";
              echo "<dep_time>$r_dHour:$r_dMin</dep_time>";
              printf("<amount>%.2f</amount>", $amount);
              echo "</record>";
              $total += $amount;
            }
            echo "<name>$user_name</name>";
            echo "<lname>$user_lname</lname>";
            echo "<login>$login</login>";
            echo "<status>$user_status</status>";
            echo "<repDate>$s_month  $curr_year</repDate>";
            printf("<total>%.2f</total>", $total);
            echo "<message>Отчет за предыдущий месяц загружен</message>";
            echo "</code>";
          }
          else echo "<code><repDate>$s_month  $curr_year</repDate><message>Отчетов за данный период не найдено.</message></code>";
        }
        }
        $result->free();
        $conn->close();
      }
  }
  else header("Location: profile.php");
}
function dToMonth($monthNumber){
  switch($monthNumber){
    case 1:
    $str_month = "Январь";
    break;
    case 2:
    $str_month = "Февраль";
    break;
    case 3:
    $str_month = "Март";
    break;
    case 4:
    $str_month = "Апрель";
    break;
    case 5:
    $str_month = "Май";
    break;
    case 6:
    $str_month = "Июнь";
    break;
    case 7:
    $str_month = "Июль";
    break;
    case 8:
    $str_month = "Август";
    break;
    case 9:
    $str_month = "Сентябрь";
    break;
    case 10:
    $str_month = "Октябрь";
    break;
    case 11:
    $str_month = "Ноябрь";
    break;
    case 12:
    $str_month = "Декабрь";
    break;

  }
  return $str_month;
}
?>
