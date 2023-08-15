<?php
if((include 'cnt/content.php')=='OK') echo "OK";
session_start();
if($_SESSION["is_authorised"]){
  if(isset($_POST["request"])){
    $j_request = $_POST["request"];
    //запрос j_request для отметки прибытия-убытия
    if($j_request == "putEntry"){
    $user_status = $_SESSION["USER_STATUS"];
    $user_u_name = $_SESSION["USER_U_NAME"];
    $curr_year = $_POST["year"];
    $curr_month = $_POST["month"];
    $curr_day = $_POST["day"];
    $curr_mins = $_POST["mins"];
    $curr_hours = $_POST["hours"];
    $conn = new mysqli($hst, $lgn, $psswrd);
    if($conn->connect_error){
      echo "<code><message>$psswrd  $conn->connect_error</message><status>$user_status</status></code>";
    $conn->close();
    }
    else{
      $conn->set_charset("utf8");
      if($user_status==0) $sql = "UPDATE b7_32911471_db_empl.t_empl SET status = 1 WHERE u_name ='".$user_u_name."'";
      if($user_status==1) $sql = "UPDATE b7_32911471_db_empl.t_empl SET status = 0 WHERE u_name ='".$user_u_name."'";

      if($result=$conn->query($sql)){
        if($user_status==1){
          $curr_month += 1;
          $record_sql = "UPDATE b7_32911471_db_records.".$user_u_name."_".$curr_year." SET dep_day = ".$curr_day.", dep_month = ".$curr_month.", dep_hour = ".$curr_hours.", dep_min = ".$curr_mins;
          $record_sql = $record_sql.", rec_status = false WHERE rec_status = true";
            if($record_result = $conn->query($record_sql)) {
              $_SESSION["USER_STATUS"] = 0;
              $user_status = $_SESSION["USER_STATUS"];
              echo "<code><message>Запись внесена</message><status>$user_status</status></code>";
            }
          else echo "<code><message>Произошла ошибка. Внести данные убытия.</message><status>$user_status</status></code>";

          }
        else{
          $curr_month += 1;
          $record_sql = "INSERT INTO b7_32911471_db_records.".$user_u_name."_".$curr_year." (arr_day, arr_month, arr_hour, arr_min, dep_day, dep_month, dep_hour, dep_min, hour_amount, rec_status)";
          $record_sql = $record_sql."VALUES (".$curr_day.", ".$curr_month.", ".$curr_hours.", ".$curr_mins.", 0, 0, 0, 0, 0, true)";
          if($record_result = $conn->query($record_sql)) {
            $_SESSION["USER_STATUS"] = 1;
            $user_status = $_SESSION["USER_STATUS"];
            echo "<code><message>Запись внесена</message><status>$user_status</status></code>";
          }
        else echo "<code><message>Произошла ошибка. Внести данные прибытия.</message><status>$user_status</status></code>";

        }
      }
      else echo "<code><message>Произошла ошибка запроса к БД.</message><status>$user_status</status></code>";
    }

    $conn->close();
    }
    //первое условие j_Reuest

    //запрос j_request для загрузки данных за текущий месяц
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
        $user_u_name = $_SESSION["USER_U_NAME"];
        $s_month = dToMonth($curr_month);

        $sql = "SELECT id, arr_day, arr_month, arr_hour, arr_min, dep_day, dep_month, dep_hour, dep_min FROM b7_32911471_db_records.".$user_u_name."_".$curr_year." WHERE arr_month=".$curr_month." ORDER BY id DESC";
        $result = $conn->query($sql);
        if(mysqli_fetch_row($conn->query($sql))==NULL) {
          echo "<code><repDate>$s_month  $curr_year</repDate><message>Записей на текщий месяц пока нет.</message></code>";
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
          echo "<repDate>$s_month  $curr_year</repDate>";
          printf("<total>%.2f</total>", $total);
          echo "<message>Отчет за текущий месяц загружен</message>";
          echo "</code>";
        }
        else echo "<code><repDate>$s_month  $curr_year</repDate><message>Записей на текщий месяц пока нет.</message></code>";
      }
      }
      $conn->close();
    }
    //Второе условие j_request

    //запрос j_request для загрузки данных за предыдущий месяц

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

        $sql = "SELECT id, arr_day, arr_month, arr_hour, arr_min, dep_day, dep_month, dep_hour, dep_min FROM b7_32911471_db_records.".$user_u_name."_".$curr_year." WHERE arr_month=".$curr_month." ORDER BY id DESC";
        $result = $conn->query($sql);
        if(mysqli_fetch_row($conn->query($sql))==NULL) {
          echo "<code><repDate>$s_month  $curr_year</repDate><message>Отчетов за данный период не найдено.</message></code>";
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
          echo "<repDate>$s_month  $curr_year</repDate>";
          printf("<total>%.2f</total>", $total);
          echo "<message>Отчет за предыдущий месяц загружен $order</message>";
          echo "</code>";
        }
        else echo "<code><repDate>$s_month  $curr_year</repDate><message>Отчетов за данный период не найдено.</message></code>";
      }
      }
      $conn->close();
    }

    // 3 условие j_request
  }
  $conn->close();

}
else {
  session_destroy();
  header("Location: index.php");
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
