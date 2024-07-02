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

  $brand_name = $_POST['brand_name'];
  $update_data = "UPDATE brand SET name ='$brand_name'WHERE id ='$id'";
  if (mysqli_query($conn, $update_data)) {
?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      swal({
        text: "Category Successfully updated",
        icon: "success",
        button: "OK",
      }).then(() => {
        // Redirect to users.php
        window.location.href = "brand.php";
      });
    </script>

<?php
  }
}
?>

<!--MAIN/EDIT/UPDATE CATEGORY-->

<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Brands Management</p>
  </div>
  <!--update category form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Update Brand</h3>
      <?php
      $select = mysqli_query($conn, "SELECT * FROM brand WHERE id = '$id'");
      while ($row = mysqli_fetch_assoc($select)) {
      ?>

        <form action="" method="post" class="popupUpdateForm">
          <input type="text" name="brand_name" placeholder="Brand name: " value="<?php echo $row['name']; ?>" class="text">
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="update" value="Save">
          </div>
        </form>
      <?php } ?>

    </div>
  </div>
</main>