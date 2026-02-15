<?php

$user = $_POST["username"];
$pwd = md5($_POST['password']);

include 'includes/connection.php';


$sql = "SELECT * FROM admin WHERE username='$user' AND password='$pwd'";
$result = mysqli_query($koneksi,"$sql");

$num = mysqli_num_rows($result);



if($num==1)
{
  session_start();
  $_SESSION['pwd'] = $pwd;
  header('location:index.php');
}
else
{
  session_start();
  $_SESSION['msg'] = '<h2>Invalid username or password!</h2>';
  header('location:login.php');
}

?>
