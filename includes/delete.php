<?php
include "conn.php";

// delete product
if (isset($_GET['deleteProd'])) {
  $id = $_GET['deleteProd'];
  $sql = "DELETE FROM products WHERE id = '$id'";
  mysqli_query($conn, $sql);
  header("Location:../products.php");
  exit();
}

// delete category
if (isset($_GET['deleteCat'])) {
  $id = $_GET['deleteCat'];
  mysqli_query($conn, "DELETE FROM category WHERE id = $id");
  header("Location:../category.php");
  exit();
}
// delete supplier
if (isset($_GET['deleteSupplier'])) {
  $id = $_GET['deleteSupplier'];
  mysqli_query($conn, "DELETE FROM supplier WHERE id = $id");
  header("Location:../supplier.php");
  exit();
}

// delete customer
if (isset($_GET['deleteCustomer'])) {
  $id = $_GET['deleteCustomer'];
  mysqli_query($conn, "DELETE FROM customers WHERE id = $id");
  header("Location:../customer.php");
  exit();
}
// delete User
if (isset($_GET['deleteUser'])) {
  $id = $_GET['deleteUser'];
  $sql = "DELETE FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location:../users.php");
    exit();
  } else {
    echo "Error in preparing statement: " . mysqli_error($conn);
  }
}


// delete Recieve Stock
if (isset($_GET['deleteRevStock'])) {
  $id = $_GET['deleteRevStock'];
  mysqli_query($conn, "DELETE FROM recieving WHERE id = $id");
  header("Location:../receiving.php");
  exit();
}
