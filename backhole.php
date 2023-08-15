<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.aidentica.tech">
<html>
<head>
<title>backHole</title>
<meta charset="utf-8">
</head>
<?php
class cl_user{
  public $id;
  public $login = "";
  public $password = "";
  public $name = "";
  public $l_name = "";
  public $post = "";
  public $status = "";
  public $is_auth;

  public function log_out(){
  $_SESSION = array();
  session_destroy();
  }
}
session_start();
if(!isset($_SESSION['auth']))
{
  $_SESSION['auth'] = "false";
  $_SESSION['message'] = "Неверный запрос необходима авторизация.";
  header("Location: index.php");
}
$user = new cl_user();
$i=0;
$us_log=[];
$us_pass=[];
$user->is_auth = "false";


 $conn = new mysqli("sql207.byethost7.com", "b7_32911471", "mb240687");
  if ($conn->connect_error){
    $_SESSION['auth'] = "false";
    $_SESSION['message'] = "Ошибка подключения:" . $conn->connect_error;

  }
  $sql = "SELECT login, password FROM b7_32911471_db_empl.t_empl";
  $conn->set_charset("utf8");
  if($result = $conn->query($sql)){
    printf("База подключена");

     while($row = $result->fetch_array()){
    printf("<br>Данные получены");
     array_push($us_log, $row["login"]);
     array_push($us_pass, $row["password"]);
    }
   }
   else echo "Ошибка запроса к БД:" . $conn->error;

    $row = array();
    $count = count($us_log);

    printf("<br>Обработка массива");

    if (isset($_POST)) {



        for($i=0;$i<$count;$i++){
        if($us_log[$i] == $_POST['input_login'] && $us_pass[$i] == md5($_POST['input_pass'])){
          $user->is_auth = "true";
          $user->id = $i;

        }
      }

      if($user->is_auth == "true"){
        $sql = "SELECT login, name, l_name, post, status FROM b7_32911471_db_empl.t_empl WHERE id = " . $user->id;
        echo $sql;
        if($result = $conn->query($sql)){
        $row = $result->fetch_assoc();
        $user->name = $row['name'];
        $user->login = $row['login'];
        $user->l_name = $row['l_name'];
        $user->post = $row['post'];
        $user->status = $row['status'];
        }
        else printf("<br>Ошибка чтения запроса: <br>%s<br>%s", $sql, $user_login);
        $result->free();
        $row  = array();

          $_SESSION["auth"] = $user->is_auth;
          $_SESSION['NAME'] = $user->name;
          $_SESSION['LNAME'] = $user->l_name;
          $_SESSION['LOGIN'] = $user->login;
          $_SESSION['ID'] = $user->id;
          $_SESSION['POST'] = $user->post;
          $_SESSION['STATUS'] = $user->status;

          header("Location: profile.html");
        }

        else
        {
          $_SESSION["auth"] = $user->is_auth;
          $_SESSION["message"] = "Неверный логин или пароль";
          header("Location: index.php");

        }
    }
$conn->close();
$result->free();

?>
<body>

</form>
</body>
</html>
