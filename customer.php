<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:index.php');

// Function to generate a random id no.
function generateIdNo($length = 8)
{
  $characters = '012345678910';
  $idNo = 'no';

  // Generate random bytes for better security
  $bytes = random_bytes($length);

  // Convert random bytes to a string of characters
  for ($i = 0; $i < $length; $i++) {
    $index = ord($bytes[$i]) % strlen($characters);
    $idNo .= $characters[$index];
  }
  return $idNo;
}

if (isset($_POST['submit'])) {
  $name = $_POST['cust_name'];

  $contact = $_POST['contact_no'];
  $addy = $_POST['address'];
  $bday = date('Y-m-d', strtotime($_POST['birth_date']));
  // Generate a random Id No.
  $id_no = generateIdNo();

  if (empty($name) || empty($contact) || empty($addy) || empty($bday)) {
    $message[] = 'please fill out all';
  } else {
    $insert = "INSERT INTO customers(name,customer_id,contact_no,address,birth_date) VALUES('$name','$id_no','$contact','$addy','$bday')";
    $result = mysqli_query($conn, $insert);
    if ($result) {
?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        swal({
          text: "Customer added successfully",
          icon: "success",
          button: "OK",
        });
      </script>
<?php
    }
  }
}


?>
<!--Main/ADD CUSTOMER-->
<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Member Management</p>
  </div>

  <div class="box">
    <div class="prod-cont">
      <h3>Add Member</h3>
      <?php
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<span class="message">' . $message . '</span>';
        }
      }
      ?>
      <div class="form">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
          <input type="text" name="cust_name" placeholder="Name: " class="text">
          <input type="text" name="contact_no" placeholder="Contact Number: " class="text">
          <input type="text" name="address" placeholder="Address : " class="text">
          <input type="date" name="birth_date" placeholder="Birth Date: " class="text">
          <div class="addProdBtn">
            <input type="submit" class="btn" name="submit" value="Add Member">
          </div>
        </form>
      </div>
    </div>
  </div>



  <!--customer display table-->
  <?php
  if (isset($_POST["searchBtn"])) {
    $search = $_POST['search'];
    $sql = "Select * from `customers` name='$search'";
    $select = mysqli_query($conn, $sql);
  } else {
    $select = mysqli_query($conn, "SELECT * FROM customers order by date_register asc");
  }
  ?>
  <div class="product-display">
    <table class="product-display-table" id="table">
      <thead>

        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Id No.</th>
          <th>Contact</th>
          <th>Address</th>
          <th>Birth Date</th>
          <th>Date added </th>
          <th>action</th>
        </tr>
      </thead>
      <?php
      $no = 1;
      while ($row = mysqli_fetch_assoc($select)) { ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['customer_id']; ?></td>
          <td><?php echo $row['contact_no']; ?></td>
          <td><?php echo $row['address']; ?></td>
          <td><?php echo $row['birth_date']; ?></td>
          <td><?php echo $row['date_register']; ?></td>
          <td>
            <a href="customerUpdate.php?edit=<?php echo $row['id']; ?>" class="actionBtn"> <span class="material-icons-outlined">
                edit
              </span> </a>


          </td>
        </tr>
      <?php $no++;
      } ?>
    </table>
  </div>
</main>

<script src="script/admin.js"></script>
</body>

</html>