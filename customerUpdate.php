<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:login.php');
$id = $_GET['edit'];


//updating category
if (isset($_POST['update'])) {

  $name = $_POST['cust_name'];
  $customer_id = $_POST['cust_id'];
  $contact = $_POST['contact_no'];
  $addy = $_POST['address'];
  $bday = date('Y-m-d', strtotime($_POST['birth_date']));

  $update_data = "UPDATE customers SET name ='$name', customer_id ='$customer_id', contact_no ='$contact', address ='$addy', birth_date='$bday' WHERE id ='$id'";
  if (mysqli_query($conn, $update_data)) {
?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      swal({
        text: "Customer Successfully updated",
        icon: "success",
        button: "OK",
      }).then(() => {
        // Redirect to users.php
        window.location.href = "customer.php";
      });
    </script>

<?php
  }
}
?>
<!--MAIN/EDIT/UPDATE Customer-->

<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Member Management</p>
  </div>
  <!--update customer form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Update Member</h3>
      <?php
      $select = mysqli_query($conn, "SELECT * FROM customers WHERE id = '$id'");
      while ($row = mysqli_fetch_assoc($select)) {
      ?>

        <form action="" method="post" class="popupUpdateForm">
          <input type="text" name="cust_name" placeholder="Name: " value="<?php echo $row['name']; ?>" class="text">
          <input type="text" name="cust_id" placeholder="Id: " value="<?php echo $row['customer_id']; ?>" class="text">
          <input type="text" name="contact_no" placeholder="Contact Number:" value="<?php echo $row['contact_no']; ?>" class="text">
          <input type="text" name="address" placeholder="Address :" value="<?php echo $row['address']; ?>" class="text">
          <input type="date" name="birth_date" placeholder="Birth Date: " value="<?php echo $row['birth_date']; ?>">
          <div class="addProdBtn">
            <input type="submit" class="btn" name="update" value="Save">
          </div>

        </form>
      <?php } ?>

    </div>
  </div>
</main>