<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";
if (!isset($_SESSION['email']))
  header('location:login.php');

function generateReferenceNumber($length = 5)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $referenceNumber = 'ref';

  // Generate random bytes for better security
  $bytes = random_bytes($length);

  // Convert random bytes to a string of characters
  for ($i = 0; $i < $length; $i++) {
    $index = ord($bytes[$i]) % strlen($characters);
    $referenceNumber .= $characters[$index];
  }

  return $referenceNumber;
}

if (isset($_POST['submit'])) {
  $customer = isset($_POST['customer']) ? $_POST['customer'] : '';
  $method = isset($_POST['method']) ? $_POST['method'] : '';
  $amountTendered = isset($_POST['amountTendered']) ? $_POST['amountTendered'] : '';
  $total = isset($_POST['total']) ? $_POST['total'] : '';
  $change = isset($_POST['change']) ? $_POST['change'] : '';

  // Generate a random reference number
  $ref_no = generateReferenceNumber();

  if (!empty($method) && !empty($customer)) {
    if ($method == "cash") {
      if (empty($customer) || empty($method) || empty($amountTendered)) {
        $message[] = 'Please fill out all fields';
      } else if ($amountTendered < $total) {
        $message[] = 'payment is less than total purchase';
      } else {
        $insert = "INSERT INTO sales (ref_no,customer_name,total_amount,amount_tendered,amount_change,method) VALUES('$ref_no','$customer','$total','$amountTendered', '$change', '$method')";
        $upload = mysqli_query($conn, $insert);
        if ($upload) {

          $type = '2';
          $stock_from = "sales";
          $remarks = "Stock from sales-$ref_no";
          $select = mysqli_query($conn, "SELECT * FROM list");
          $dataArray = array();
          while ($row = mysqli_fetch_assoc($select)) {

            $product = mysqli_real_escape_string($conn, $row['product']);
            $quantity = mysqli_real_escape_string($conn, $row['quantity']);

            // Insert data into the inventory table for each product in the list
            $sql = "INSERT INTO inventory (product_code, quantity, type, stock_from, remarks) VALUES ('$product', '$quantity', '$type', '$stock_from', '$remarks')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
              mysqli_query($conn, "DELETE FROM list");
?>
              <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
              <script>
                swal({
                  text: "payment process successfully",
                  icon: "success",
                  button: "OK",
                }).then(() => {
                  // Redirect to users.php
                  window.location.href = "salesManage.php";
                });
              </script>

            <?php

            } else {
              echo "Error in inventory statement execution: " . mysqli_error($conn);
            }
          }
        }
      }
    } else if ($method == "credit") {
      if (empty($customer) || empty($method)) {
        $message[] = 'Please fill out all fields';
      } else {
        $amountTendered = 0;
        $change = 0;
        $method = "credit";
        $insert = "INSERT INTO sales (ref_no,customer_name,total_amount,amount_tendered,amount_change,method) VALUES('$ref_no','$customer','$total','$amountTendered', '$change', '$method')";
        $upload = mysqli_query($conn, $insert);
        if ($upload) {

          $type = '2';
          $stock_from = "sales";
          $remarks = "Stock from sales-$ref_no";
          $select = mysqli_query($conn, "SELECT * FROM list");
          $dataArray = array();
          while ($row = mysqli_fetch_assoc($select)) {

            $product = mysqli_real_escape_string($conn, $row['product']);
            $quantity = mysqli_real_escape_string($conn, $row['quantity']);

            // Insert data into the inventory table for each product in the list
            $sql = "INSERT INTO inventory (product_code, quantity, type, stock_from, remarks) VALUES ('$product', '$quantity', '$type', '$stock_from', '$remarks')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
              mysqli_query($conn, "DELETE FROM list");
            ?>
              <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
              <script>
                swal({
                  text: "payment process successfully",
                  icon: "success",
                  button: "OK",
                }).then(() => {
                  // Redirect to users.php
                  window.location.href = "salesManage.php";
                });
              </script>

<?php

            } else {
              echo "Error in inventory statement execution: " . mysqli_error($conn);
            }
          }
        }
      }
    }
  } else {
    $message[] = 'Please fill out all fields';
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
      <h3>Payment</h3>
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
          <label for="customer">Customer Name:</label>
          <select name="customer" class="profileSelect">
            <option disabled selected class="default">Select Customer name: </option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM customers order by name asc");
            while ($product = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $product['name'] ?>">
                <?php echo $product['name'] ?>
              </option>
            <?php } ?>
          </select>

          <label for="method">Payment Method: </label>
          <select name="method" id="paymentMethod" class="profileSelect" onchange="togglePaymentFields()">
            <option disabled selected class=" default">Select payment method: </option>
            <option value="cash">Cash</option>
            <option value="credit">Credit</option>
          </select>

          <?php
          $total = 0;
          $select = mysqli_query($conn, "SELECT * FROM list");
          while ($row = mysqli_fetch_assoc($select)) {
            $total = $total + $row['amount'];
          } ?>
          <label for="total">Total Amount:</label>
          <input type="number" id="total" name="total" value="<?php echo $total; ?>" readonly>
          <label for="amountTendered">Amount Tendered:</label>
          <input type="number" id="amountTendered" name="amountTendered" min="0" oninput="updateOutput()">

          <label for="change">Amount Change:</label>
          <input type="number" id="change" name="change" value="" readonly>
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="submit" value="Done">
          </div>

        </form>
      </div>

    </div>
  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Disable fields on page load
      disablePaymentFields();

      // Add event listener for payment method change
      document.getElementById("paymentMethod").addEventListener("change", togglePaymentFields);
    });

    function disablePaymentFields() {
      var amountTendered = document.getElementById("amountTendered");
      var change = document.getElementById("change");

      // Disable the fields
      amountTendered.disabled = true;
      change.disabled = true;

      // Clear the values
      amountTendered.value = "";
      change.value = "";
    }

    function togglePaymentFields() {
      var paymentMethod = document.getElementById("paymentMethod");
      var amountTendered = document.getElementById("amountTendered");
      var change = document.getElementById("change");

      // Check if paymentMethod is "cash", enable fields; otherwise, disable them
      if (paymentMethod.value === "cash") {
        amountTendered.disabled = false;
        change.disabled = false;
      } else {
        // If not "cash," disable the fields
        disablePaymentFields();
      }
    }

    function updateOutput() {
      // Get the value from the input field
      var amountTendered = document.getElementById("amountTendered").value;
      var total = document.getElementById("total").value;

      // Update the value of the output field
      var change = document.getElementById("change");
      change.value = amountTendered - total;
    }
  </script>

</main>
<script src="script/admin.js"></script>
</body>

</html>