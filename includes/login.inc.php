<?php
include "conn.php";
if (isset($_POST["submit"])) {

  $email = $_POST["email"];
  $password = $_POST["password"];

  if (isset($_POST["email"]) && isset($_POST["password"])) {

    if (empty($email) && empty($password)) {
      header("Location: ../index.php?error=please fill up the form!");
    } else if (empty($email)) {
      header("Location: ../index.php?error=Email is Required!");
      exit();
    } else if (empty($password)) {
      header("Location: ../index.php?error=Password is Required!");
      exit();
    } else {

      $query = "SELECT * FROM users WHERE email='$email'";
      $result = mysqli_query($conn, $query);
      if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $hasspass = $row['pass'];
        $checkpass = password_verify($password, $hasspass);
        if ($row['email'] == $email && $checkpass == true) {
          session_start();
          $_SESSION["email"] = $row["email"];
          $_SESSION["name"] = $row["name"];
          $_SESSION["profile"] = $row["profile"];
          // Check user profile and redirect accordingly
          if ($_SESSION["profile"] == 'administrator') {
            header("Location:../dashboard.php");
            exit();
          } elseif ($_SESSION["profile"] == 'seller') {
            header("Location:../salesManage.php");
            exit();
          }
        } else {
          header("Location: ../index.php?error=Incorrect Email or Password!");
          exit();
        }
      } else {
        header("Location: ../index.php?error=Incorrect Email or Password!");
        exit();
      }
    }
  } else {
    header("Location:../index.php");
    exit();
  }
}
