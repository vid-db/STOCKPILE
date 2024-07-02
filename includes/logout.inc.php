<?php
session_start();
unset($_SESSION["email"]);
session_destroy(); // destroy current tab then redirect
header("Location:../index.php");
