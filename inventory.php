<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:login.php');
?>

<!--MAIN/ADD PRODUCT-->
<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Inventory Management</p>
  </div>

  <!--product display table-->
  <div class="product-display">
    <div class="print-btn">
      <button onclick="printInventory()" class="blue-button">Print inventory</button>
    </div>
    <table class="product-display-table" id="table">
      <thead>
        <th>#</th>
        <th>Product Name/Code</th>
        <th>Stock In</th>
        <th>Stock Out</th>
        <th>Stock Available</th>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $product = $conn->query("SELECT * FROM products order by name asc");

        while ($row = $product->fetch_assoc()) :
          $name = $row["name"];
          $inn = $conn->query("SELECT sum(quantity) as inn FROM inventory where type = 1 and product_code = '$name'");

          $inn = $inn && $inn->num_rows > 0 ? $inn->fetch_array()['inn'] : 0;
          $out = $conn->query("SELECT sum(quantity) as `out` FROM inventory where type = 2 and product_code = '$name'");
          $out = $out && $out->num_rows > 0 ? $out->fetch_array()['out'] : 0;
          $available = $inn - $out;
          $color = ($available > 15) ? 'green' : 'red';
        ?>
          <tr>
            <td class="text-center"><?php echo $no++ ?></td>
            <td><?php echo $row['name'] . '/' . $row['code']; ?></td>
            <td><?php echo $inn ?></td>
            <td><?php echo $out ?></td>
            <td style="color: <?php echo $color; ?>"><?php echo $available ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    </table>
  </div>

</main>
<!-- JavaScript for Inventory  printing -->
<script>
  function printInventory() {
    // Open a new window for the print-friendly version
    var printWindow = window.open('', '_blank');

    // HTML content for the print
    var printContent = `
      <html>
        <head>
          <title>SPMC Product Inventory</title>
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
        <h1>SPMC LIST OF PRODUCT</h1>
        <p>Sapang Palay Multi-Purpose Cooperative <br>123 Sapang Palay Proper, City San Jose Del Monte, Bulacan<br>TIN-210-145-1 89-0000non-v</p>
          ${document.querySelector('.product-display-table').outerHTML}
        </body>
      </html>
    `;

    // Write the HTML content to the new window
    printWindow.document.open();
    printWindow.document.write(printContent);
    printWindow.document.close();

    // Print the content
    // Focus and print the new window
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  }
</script>

<script src="script/openPopup.js"></script>

<script src="script/admin.js"></script>
</body>

</html>