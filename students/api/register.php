<?php
session_start();

if(!isset($_SESSION["id"])){
	header("location:index.html");
}

function giveFeedback($status, $message ){
    if($status){
      header("location:../class-view.php?cid=".$_POST["cid"]);
    } else {
      header("location:../create-item-view.php?m=".$message); 
    } 
}

$message = "";
$status = 0;

if(isset($_POST["name"])){


    require "../db/db.php";

    $password = $_POST["password"];
    $name = $_POST["name"];
    $email = $_POST["email"];



    $email = ($email ? mysqli_real_escape_string($conn, $email) : "");
    $name = ($name ? mysqli_real_escape_string($conn, $name) : "");
    $password = ($password ? mysqli_real_escape_string($conn, $password) : "");

    $sql1 = " SELECT * FROM students WHERE email='" . $email . "' ";
    $sql2 = " SELECT * FROM teachers WHERE email='" . $email . "' ";

    $result1 = $conn->query($sql1);
    $result2 = $conn->query($sql2);

    $result1 = $result1->fetch_assoc();
    $result2 = $result2->fetch_assoc();

    if($result1["email"] == $email || $result2["email"] == $email){
      header("location:../index.php?n=email already taken");
    }else{
      $sql = "INSERT INTO `students` SET
        `name` = '" . $name . "',
        `email` = '" . $email . "',
        `password` = '" . $password . "'";
  
      if ($conn->query($sql) === TRUE) {
          header("location:../index.php?m=Successful registration. Please log in.");
      } else {
          $message = $conn->error ;
      }
    }


    $conn->close();


} else {
    header("location:../index.php?m=Please fill all fields.");
}
exit();
?>