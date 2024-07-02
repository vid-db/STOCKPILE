<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:login.php');
$id = $_GET['edit'];


if (isset($_POST['update'])) {
  $prod_name = $_POST['prod_name'];
  $brand = $_POST['brand'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $supplier = $_POST['supplier'];
  $price = $_POST['price'];


  $update_data = "UPDATE products SET name='$prod_name',brand='$brand',description='$description',category ='$category',supplier ='$supplier',price='$price' WHERE id ='$id'";
  if (mysqli_query($conn, $update_data)) {
?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      swal({
        text: "product Updated sucessfully",
        icon: "success",
        button: "OK",
      }).then(() => {
        // Redirect to users.php
        window.location.href = "products.php";
      });
    </script>

<?php

  }
}
?>

<!--MAIN/EDIT/UPDATE PRODUCT-->

<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Product Management</p>
  </div>
  <!--update Product form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Update Product</h3>
      <?php
      $select = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
      while ($row = mysqli_fetch_assoc($select)) {
      ?>
        <form action="#" method="post" id="updatefrm" enctype="multipart/form-data">
          <!-- <input type="file" name="prod_image" accept="image/PNG,image/png,image/jpeg,image/jpg" placeholder="Image: " required> -->
          <input type="text" name="prod_name" value="<?php echo $row['name']; ?>" placeholder="Product Name:" class="text">

          <select name="brand" class="profileSelect">
            <option value="<?php echo $row['brand']; ?>"><?php echo $row['brand']; ?></option>
            <?php
            $get_brand = mysqli_query($conn, "SELECT * FROM brand");
            while ($brand = mysqli_fetch_assoc($get_brand)) {
            ?>
              <option value="<?php echo $brand['name'] ?>"> <?php echo $brand['name'] ?> </option>
            <?php } ?>
          </select>

          <input type="text" name="description" value="<?php echo $row['description']; ?>" placeholder="Description: " class="text">
          <select name="category" class="profileSelect">
            <option value="<?php echo $row['category']; ?>"><?php echo $row['category']; ?></option>
            <?php
            $get_cat = mysqli_query($conn, "SELECT * FROM category");
            while ($categories = mysqli_fetch_assoc($get_cat)) {
            ?>
              <option value="<?php echo $categories['name'] ?>"> <?php echo $categories['name'] ?> </option>
            <?php } ?>
          </select>

          <select name="supplier" class="profileSelect">
            <option value="<?php echo $row['supplier']; ?>"><?php echo $row['supplier']; ?></option>
            <?php
            $get_supplier = mysqli_query($conn, "SELECT * FROM supplier");
            while ($supplier = mysqli_fetch_assoc($get_supplier)) {
            ?>
              <option value="<?php echo $supplier['name'] ?>"> <?php echo $supplier['name'] ?> </option>
            <?php } ?>
          </select>

          <input type="number" name="price" value="<?php echo $row['price']; ?>" min="0" placeholder="Price: ">
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="update" value="Save">
          </div>
        </form>
      <?php } ?>
    </div>
  </div>

</main>
<script src="script/openPopup.js"></script>
<script src="script/admin.js"></script>
</body>

</html>