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

  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $contact_person = $_POST['contact_person'];
  $address = $_POST['address'];
  $update_data = "UPDATE supplier SET name ='$name', contact ='$contact',contact_person='$contact_person', address ='$address' WHERE id ='$id'";
  if (mysqli_query($conn, $update_data)) {
?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      swal({
        text: "Supplier Successfully updated",
        icon: "success",
        button: "OK",
      }).then(() => {
        // Redirect to users.php
        window.location.href = "supplier.php";
      });
    </script>

<?php
  }
}
?>
<!--MAIN/EDIT/UPDATE SUPPLIER-->

<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Product Supplier</p>
  </div>
  <!--update supplier form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Update Supplier</h3>
      <?php
      $select = mysqli_query($conn, "SELECT * FROM supplier WHERE id = '$id'");
      while ($row = mysqli_fetch_assoc($select)) {
      ?>

        <form action="" method="post" class="popupUpdateForm">
          <input type="text" name="name" placeholder="Supplier: " value="<?php echo $row['name']; ?>">
          <input type="text" name="contact" placeholder="Contact number:" value="<?php echo $row['contact']; ?>">
          <input type="text" name="contact_person" value="<?php echo $row['contact_person']; ?>" placeholder=" Contact person:">
          <input type="text" name="address" placeholder="Address:" value="<?php echo $row['address']; ?>">
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="update" value="Save">
          </div>
        </form>
      <?php } ?>

    </div>
    <div></div>
  </div>
</main>