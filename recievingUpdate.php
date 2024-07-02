<?php
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

$id = $_GET['edit'];
session_start();

//recieving stock
if (isset($_POST['save'])) {
  $supplier = $_POST['supplier'];
  $price = floatval($_POST['price']);
  $quantity = intval($_POST['quantity']);
  $amount = $price * $quantity;

  $update_data = "UPDATE recieving SET supplier='$supplier',price='$price',quantity='$quantity',amount='$amount' WHERE id='$id'";

  $result = mysqli_query($conn, $update_data);
  if ($result) {
?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
      swal({
        text: "Stock Recieved successfully",
        icon: "success",
        button: "OK",
      }).then(() => {
        // Redirect to users.php
        window.location.href = "products.php";
      });
    </script>
<?php
  } else {
    $message[] = 'could not edit Recieve Stock';
  }
}
?>

<!--MAIN/RECIEVING STOCK-->
<main class="main">

  <div class="main-title">
    <p class="font-weight-bold">Manage Receiving</p>
  </div>

  <!--add Product form-->
  <div class="box">
    <div class="prod-cont">
      <h3>EDIT RECIVE STOCK</h3>

      <?php
      $select = mysqli_query($conn, "SELECT * FROM recieving WHERE id = '$id'");
      while ($row = mysqli_fetch_assoc($select)) {
      ?>
        <div class="form">
          <form action="#" method="post" class="form">
            <select name="supplier" class="profileSelect">
              <option value="<?php echo $row['supplier']; ?>" class=" default"><?php echo $row['supplier']; ?></option>
              <?php
              $select = mysqli_query($conn, "SELECT * FROM supplier");
              while ($supplier = mysqli_fetch_assoc($select)) {
              ?>
                <option value="<?php echo $supplier['name'] ?>"> <?php echo $supplier['name'] ?> </option>
              <?php } ?>
            </select>
            <input type="number" name="price" min="0" placeholder="Recieving Price: " value="<?php echo $row['price'] ?>">
            <input type="number" name="quantity" min="0" placeholder="Quantity:" value="<?php echo $row['quantity'] ?>">
            <div class="addProdBtn">
              <input type="submit" class="addbtn" name="save" value="Save">
            </div>
          </form>
        <?php } ?>
        </div>

    </div>
  </div>

</main>


<script src="script/admin.js"></script>
</body>

</html>