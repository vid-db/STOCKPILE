<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";




if (!isset($_SESSION['email']))
  header('location:index.php');

if (isset($_POST['submit'])) {
  $product = isset($_POST['product']) ? $_POST['product'] : '';
  $price = isset($_POST['price']) ? $_POST['price'] : '';
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';

  $unit = $_POST['unit'];

  if ($unit === "box") {
    $quantity = $_POST['quantity'] * 12;
  } else {
    $quantity = $_POST['quantity'];
  }



  if (empty($product) || empty($quantity)) {
    $message[] = 'Please fill out all fields';
  } else {
    $amount = $price * $quantity;
    $sql = "Select * from list where product = '$product'";
    $result = mysqli_query($conn, $sql);
    $existed = mysqli_num_rows($result);
    if ($existed > 0) {
      $message[] = "product already added";
    } else {

      $inn = $conn->query("SELECT sum(quantity) as inn FROM inventory where type = 1 and product_code = '$product'");

      $inn = $inn && $inn->num_rows > 0 ? $inn->fetch_array()['inn'] : 0;
      $out = $conn->query("SELECT sum(quantity) as `out` FROM inventory where type = 2 and product_code = '$$product'");
      $out = $out && $out->num_rows > 0 ? $out->fetch_array()['out'] : 0;
      $available = $inn - $out;


      echo $available;
      if ($quantity > $available) {
        $message[] = "quantity is greater than available stock";
      } else {
        $addtolist = "INSERT INTO list (product,price,quantity,amount) VALUES('$product','$price','$quantity','$amount')";
        mysqli_query($conn, $addtolist);
      }
    }
  }
}
?>

<?php
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM list WHERE id = $id");
}
?>
<!--MAIN/ADD PRODUCT-->
<main class="main">

  <div class="main-title">
    <p class="font-weight-bold">Product Management</p>
  </div>

  <!--add Product form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Add Sales</h3>
      <?php
      //messages
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<span class="message">' . $message . '</span>';
        }
      }
      ?>

      <div class="form">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" class="form">
          <select id="productName" name="product" class="profileSelect" oninput="updateOutput()">
            <option disabled selected class="default">Select Product</option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM products order by name asc");
            while ($product = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $product['name'] ?>">
                <?php echo $product['name'] ?>
              </option>
            <?php } ?>
          </select>

          <input type="number" id="price" name="price" min="0" placeholder="Price: " value="" readonly>
          <input type="number" name="quantity" placeholder="Quantity">
          <select name="unit" class="profileSelect">
            <option disabled selected class="default">Select unit</option>
            <option value="">piece</option>
            <option value="box">box</option>
          </select>
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="submit" value="Add To list">
          </div>

        </form>
      </div>


    </div>
  </div>


  <!--product display table-->
  <?php

  $select = mysqli_query($conn, "SELECT * FROM list");
  ?>

  <div class="product-display">

    <table class="product-display-table" id="table">
      <thead>
        <tr>

          <th>#</th>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Amount</th>
          <th>Action</th>
        </tr>
      </thead>
      <?php
      $no = 1;
      while ($row = mysqli_fetch_assoc($select)) {
      ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td><?php echo $row['product'] ?></td>
          <td>₱<?php echo $row['price']; ?></td>
          <td><?php echo $row['quantity']; ?></td>
          <td>₱<?php echo $row['amount']; ?></td>

          <td>
            <a href="addSales.php?delete=<?php echo $row['id']; ?>" class="actionBtn"> <span class="material-icons-outlined">
                delete
              </span></a>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <?php
        $total = 0;
        $select = mysqli_query($conn, "SELECT * FROM list");
        while ($row = mysqli_fetch_assoc($select)) {
          $total = $total + $row['amount'];
        }

        ?>
        <td colspan="5" align="right">Total: </td>
        <td align="left">₱ <?php echo number_format("$total", 2); ?></td>
        <?php


        ?>

      </tr>
    </table>
    <div class="paymentBox">
      <a href="payment.php" class="payment">payment</a>
    </div>
  </div>
  <?php
  // Fetch product names and prices from the database
  $productPrices = [];
  $select = mysqli_query($conn, "SELECT name, price FROM products");
  while ($product = mysqli_fetch_assoc($select)) {
    $productPrices[$product['name']] = $product['price'];
  }
  ?>


  <script>
    var productPrices = <?php echo json_encode($productPrices); ?>;

    function updateOutput() {
      var productName = document.getElementById("productName").value;

      // Look up the price in the productPrices object
      var price = productPrices[productName];

      // Update the value of the output field
      if (price !== undefined) {
        document.getElementById("price").value = price;
      } else {
        // Handle the case when the price is not found (optional)
        console.log("Price not found for the selected product");
      }

    }
  </script>


</main>
<script src="script/admin.js"></script>
</body>

</html>