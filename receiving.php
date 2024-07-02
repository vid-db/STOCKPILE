<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:login.php');


// Function to generate a secure random reference number
function generateReferenceNumber($length = 5)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $referenceNumber = 'REF';

  // Generate random bytes for better security
  $bytes = random_bytes($length);

  // Convert random bytes to a string of characters
  for ($i = 0; $i < $length; $i++) {
    $index = ord($bytes[$i]) % strlen($characters);
    $referenceNumber .= $characters[$index];
  }
  return $referenceNumber;
}


//recieving stock
if (isset($_POST['submit'])) {
  $prod_name = $_POST['product_name'] ?? '';
  $supplier = $_POST['supplier'] ?? '';
  $price = $_POST['price'];
  $unit = $_POST['unit'];

  if ($unit === "box") {
    $quantity = $_POST['quantity'] * 12;
  } else {
    $quantity = $_POST['quantity'];
  }


  // Generate a random reference number
  $ref_no = generateReferenceNumber();

  if (empty($prod_name) || empty($supplier) || empty($quantity) || empty($price)) {
    $message[] = 'please fill out all';
  } else {
    $receiver = $_SESSION['name'];
    $amount = $price * $quantity;
    $insert = "INSERT INTO recieving (ref_no,product_name,supplier,price,quantity,receiver,amount) VALUES('$ref_no','$prod_name','$supplier','$price', '$quantity','$receiver','$amount')";
    $upload = mysqli_query($conn, $insert);
    if ($upload) {
      $type = '1';
      $stock_from = "receiving";
      $remarks = "Stock from receiving-$ref_no";
      $insertInv = "INSERT INTO inventory (product_code,quantity,type,stock_from,remarks) VALUES('$prod_name','$quantity','$type','$stock_from','$remarks')";
      $result = mysqli_query($conn, $insertInv);

?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        swal({
          text: "Stock Recieved successfully",
          icon: "success",
          button: "OK",
        });
      </script>
<?php
    } else {
      $message[] = 'could not Recieve Stock';
    }
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
      <h3>RECEIVE STOCK</h3>

      <?php
      //messages
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<span class="message">' . $message . '</span>';
        }
      }
      ?>
      <div class="form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
          <select name="product_name" class="profileSelect">
            <option disabled selected class="default">Receive Product</option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM products order by name asc");
            while ($product = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $product['name'] ?>">
                <?php echo $product['name'] ?>
              </option>
            <?php } ?>
          </select>
          <select name="supplier" class="profileSelect">
            <option disabled selected class="default">Select Supplier</option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM supplier");
            while ($supplier = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $supplier['name'] ?>"> <?php echo $supplier['name'] ?> </option>
            <?php } ?>
          </select>
          <input type="number" name="price" min="0" placeholder="Receiving Price: ">
          <input type="number" name="quantity" min="0" placeholder="Quantity: ">
          <select name="unit" class="profileSelect">
            <option disabled selected class="default">Select unit</option>
            <option value="">piece</option>
            <option value="box">box</option>
          </select>
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="submit" value="Received">
          </div>
        </form>
      </div>

    </div>
  </div>



  <div class="product-display">
    <!----------------*********** Search form **********-------------------->
    <div class="search-container">
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="text" name="search" placeholder="Search Receiving" class="SearchReceive">
        <button type="submit" name="searchBtn" class="Searchbtn">Search</button>
      </form>
    </div>

    <!-- Product display table -->
    <?php
    if (isset($_POST["searchBtn"]) && !empty($_POST["search"])) { //....... Add isEmpty() To check wether the input is valid or not
      $search = $_POST['search'];
      $sql = "Select * from `recieving` where product_name LIKE '%$search%' or ref_no LIKE '%$search%'";
      $select = mysqli_query($conn, $sql);
    } else {
      $select = mysqli_query($conn, "SELECT * FROM recieving order by date asc");
    }
    ?>

    <!----------************ End of search input ************------------->

    <table class="product-display-table" id="table">
      <thead>
        <tr>
          <th>#</th>
          <th>ref_no</th>
          <th>Product </th>
          <th>Supplier</th>
          <th>Contact Person</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>amount</th>
          <th>Receiver</th>
          <th>Date delivered</th>

        </tr>
      </thead>
      <?php
      $no = 1;
      while ($row = mysqli_fetch_assoc($select)) { ?>
        <tr>
          <td><?php echo $no++ ?></td>
          <td><?php echo $row['ref_no']; ?></td>
          <td><?php echo $row['product_name'] ?></td>
          <td><?php echo $row['supplier']; ?></td>

          <td><?php

              $supplierName = $row['supplier'];
              $selectPerson = mysqli_query($conn, "SELECT DISTINCT(contact_person) FROM supplier WHERE name = '$supplierName'");
              while ($contact_person = mysqli_fetch_assoc($selectPerson)) {
                echo $contact_person['contact_person'];
              } ?></td>
          <td>₱<?php echo $row['price']; ?></td>
          <td><?php echo $row['quantity']; ?></td>
          <td>₱<?php echo $row['amount']; ?></td>
          <td><?php echo $row['receiver']; ?></td>
          <td><?php echo $row['date']; ?></td>
        </tr>
      <?php } ?>

    </table>
  </div>




</main>


<script src="script/admin.js"></script>
</body>

</html>