<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

?>


<!-- <style>
  /* Add your custom styles here */
  .add-sales-button {
    margin: 10px;
  }
</style> -->

<!--Main/Dashboard-->
<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Sales Management</p>
  </div>

  <div class="product-display">
    <!-- Add Sales Button nadagdag-->
    <div class="addsalesbox">
      <a href="addSales.php" class="add-sales-button">Add Sales</a>
      <button onclick="printSales()" class="add-sales">Print Sales Summary</button>
    </div>
    <?php
    // Handle date range filtering
    if (isset($_POST['filterBtn'])) {
      $startDate = $_POST['start_date'];
      $endDate = $_POST['end_date'];

      // Validate date format
      if (validateDate($startDate) && validateDate($endDate)) {
        // Ensure that start date is earlier than or equal to end date
        if (strtotime($startDate) <= strtotime($endDate)) {
          $select = mysqli_query($conn, "SELECT * FROM sales WHERE date BETWEEN '$startDate' AND '$endDate'");
        } else {
          // Handle invalid date range
          echo '<p class="error-message">End date must be equal to or later than the start date.</p>';
          $select = mysqli_query($conn, "SELECT * FROM sales");
        }
      } else {
        // Handle invalid date format
        echo '<p class="error-message">Invalid date format. Please enter dates in YYYY-MM-DD format.</p>';
        $select = mysqli_query($conn, "SELECT * FROM sales");
      }
    } else {
      $select = mysqli_query($conn, "SELECT * FROM sales");
    }

    // Function to validate date format
    function validateDate($date, $format = 'Y-m-d')
    {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
    }
    ?>


    <!-- Date Range Filter Form -->
    <form method="post" class="date-range-form">
      <label for="start_date">Start Date:</label>
      <input type="date" name="start_date" required class="dateSD">

      <label for="end_date">End Date:</label>
      <input type="date" name="end_date" class="dateED" required>

      <button type="submit" name="filterBtn" class="filterbtn">Filter</button>
    </form>
    <table class="product-display-table" id="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Ref_no.</th>
          <th>Customer</th>
          <th>Total Amount</th>
          <th>Amount Tendered</th>
          <th>Amount Change</th>
          <th>method</th>
          <th>Date</th>
        </tr>
      </thead>
      <?php while ($row = mysqli_fetch_assoc($select)) { ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['ref_no']; ?></td>
          <td><?php echo $row['customer_name'] ?></td>
          <td><?php echo $row['total_amount']; ?></td>
          <td>₱<?php echo $row['amount_tendered']; ?></td>
          <td><?php echo $row['amount_change']; ?></td>
          <td><?php echo $row['method']; ?></td>
          <td><?php echo $row['date']; ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>

</main>

<!-- Other HTML and PHP code -->

<!-- Add this line just before the closing body tag -->

<script src="script/admin.js"></script>
<!-- JavaScript for Inventory  printing -->
<script>
  function printSales() {
    // Open a new window for the print-friendly version
    var printWindow = window.open('', '_blank');

    // HTML content for the print
    var printContent = `
      <html>
        <head>
          <title>SPMC SALES SUMMARY</title>
          <style>
            body {
              margin: 20px;
              text-align: center;
            }
            h1 {
              margin-bottom: 0px;
            }
            table {
              margin: 20px auto; /* Center the table with more spacing */
              width: 90%; /* Adjust the width as needed */
            }
            th, td {
              border-bottom: 1px solid #121111;
              padding: 12px; /* Increase padding for more spacing */
              text-align: left;
            }
            th {
              background-color: #f2f2f2;
            }
          </style>
        </head>
        <body>
        <h1>SPMC SALES SUMMARY</h1>
        <p>Sapang Palay Multi-Purpose Cooperative <br>123 Sapang Palay Proper, City San Jose Del Monte, Bulacan<br>TIN-210-145-1 89-0000non-v</p>
          ${document.querySelector('.product-display-table').outerHTML}
        </body>
      </html>
    `;

    // Write the HTML content to the new window
    printWindow.document.open();
    printWindow.document.write(printContent);


    // Print the content
    // Focus and print the new window
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  }
</script>


</body>

</html>