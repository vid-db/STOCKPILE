<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:index.php');

function generateProdCode($length = 5)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
  $prodNo = 'prod';

  // Generate random bytes for better security
  $bytes = random_bytes($length);

  // Convert random bytes to a string of characters
  for ($i = 0; $i < $length; $i++) {
    $index = ord($bytes[$i]) % strlen($characters);
    $prodNo .= $characters[$index];
  }

  return $prodNo;
}

//adding product
if (isset($_POST['submit'])) {
  $prod_name = $_POST['prod_name'];
  $prod_image = $_FILES['prod_image']['name'];
  $prod_image_tmp_name = $_FILES['prod_image']['tmp_name'];
  $prod_image_folder = 'uploaded_img/' . $prod_image;
  $brand = $_POST['brand'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $supplier = $_POST['supplier'];
  $price = $_POST['price'];

  $code = generateProdCode();


  if (empty($prod_image) || empty($prod_name) || empty($brand) || empty($description) || empty($code) || empty($category) || empty($supplier) || empty($price)) {
    $message[] = 'please fill out all';
  } else {
    $sql = "Select * from products where code = '$code'";
    $result = mysqli_query($conn, $sql);
    $existed = mysqli_num_rows($result);

    if ($existed > 0) {
      $message[] = "product code already existed";
    } else {
      $insert = "INSERT INTO products(image,name,code,brand,description,category,supplier,price) VALUES('$prod_image','$prod_name','$code','$brand','$description','$category','$supplier','$price')";
      $upload = mysqli_query($conn, $insert);
      if ($upload) {
        move_uploaded_file($prod_image_tmp_name, $prod_image_folder);
?>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
          swal({
            text: "new product added successfully",
            icon: "success",
            button: "OK",
          });
        </script>

<?php
      } else {
        $message[] = 'could not add the product';
      }
    }
  }
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
      <h3>Add Product</h3>

      <?php
      //messages
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<span class="message">' . $message . '</span>';
        }
      }
      ?>
      <div class="form">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form">
          <input type="file" name="prod_image" accept="image/PNG,image/png,image/jpeg,image/jpg,image/JPG," placeholder="Image: ">
          <input type="text" name="prod_name" placeholder="Product Name: " class="text">

          <select name="brand" class="profileSelect">
            <option value="">Select Brand</option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM brand");
            while ($brands = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $brands['name'] ?>"> <?php echo $brands['name'] ?> </option>
            <?php } ?>
          </select>

          <select name="category" class="profileSelect">
            <option value="">Select Category</option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM category");
            while ($categories = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $categories['name'] ?>"> <?php echo $categories['name'] ?> </option>
            <?php } ?>
          </select>
          <select name="supplier" class="profileSelect">
            <option value="">Select Supplier</option>
            <?php
            $select = mysqli_query($conn, "SELECT * FROM supplier");
            while ($supplier = mysqli_fetch_assoc($select)) {
            ?>
              <option value="<?php echo $supplier['name'] ?>"> <?php echo $supplier['name'] ?> </option>

            <?php } ?>
          </select>
          <input type="text" name="description" placeholder="Description: " class="text">
          <input type="number" name="price" min="0" placeholder="Selling rice: ">

          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="submit" value="Add Product">
          </div>
        </form>
      </div>

    </div>
  </div>

  <!--product display table-->

  <div class="product-display">
    <div class="print-btn">
      <button onclick="printProductList()" class="blue-button">Print Product list</button>
    </div>
    <!----------------*********** Search form **********-------------------->
    <div class="search-container">
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="text" name="search" placeholder="Search product" class="search">
        <button type="submit" name="searchBtn" class="searchbtn">Search</button>
      </form>
    </div>
    <!-- Product display table -->
    <?php
    if (isset($_POST["searchBtn"]) && !empty($_POST["search"])) { //....... Add isEmpty() To check wether the input is valid or not
      $search = $_POST['search'];
      $sql = "Select * from `products` where code LIKE '%$search%' or name LIKE '%$search%'";
      $select = mysqli_query($conn, $sql);
    } else {
      $select = mysqli_query($conn, "SELECT * FROM products order by date asc");
    } ?>
    <!----------************ End of search input ************------------->

    <table class="product-display-table" id="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Image</th>
          <th>Name</th>
          <th>Code</th>
          <th>Brand</th>
          <th>Description</th>
          <th>Category</th>
          <th>Supplier</th>
          <th>Price</th>
          <th>Date Added</th>
          <th>action</th>
        </tr>
      </thead>
      <?php
      $no = 1;
      while ($row = mysqli_fetch_assoc($select)) {
      ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="30" alt="product image"></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['code']; ?></td>
          <td><?php echo $row['brand']; ?></td>
          <td><?php echo $row['description']; ?></td>
          <td><?php echo $row['category']; ?></td>
          <td><?php echo $row['supplier']; ?></td>
          <td>₱<?php echo $row['price']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td>

            <a href="productUpdate.php?edit=<?php echo $row['id']; ?>" class="actionBtn"> <span class="material-icons-outlined">
                edit
              </span> </a>


          </td>
        </tr>
      <?php
      } ?>
    </table>
  </div>



  <script>
    function printProductList() {
      // Open a new window for the print-friendly version
      var printWindow = window.open('', '_blank');

      // HTML content for the print
      var printContent = `
    <html>
      <head>
        <title>SPMC LIST OF PRODUCT</title>
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

  `;

      // Get the table element
      var table = document.getElementById('table');

      // Create a new table without the last column (action column)
      var newTable = document.createElement('table');
      newTable.classList.add('product-display-table'); // Add the class for styling

      // Copy the header row
      var headerRow = newTable.createTHead().insertRow();
      for (var i = 0; i < table.rows[0].cells.length - 1; i++) {
        headerRow.insertCell().outerHTML = table.rows[0].cells[i].outerHTML;
      }

      // Copy the rows (excluding the last cell of each row)
      for (var i = 1; i < table.rows.length; i++) {
        var newRow = newTable.insertRow();
        for (var j = 0; j < table.rows[i].cells.length - 1; j++) {
          newRow.insertCell().outerHTML = table.rows[i].cells[j].outerHTML;
        }
      }

      // Complete the HTML content
      printContent += newTable.outerHTML + `
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


</main>
<script src="script/admin.js"></script>
</body>

</html>