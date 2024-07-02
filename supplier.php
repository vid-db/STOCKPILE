<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";
if (!isset($_SESSION['email']))
  header('location:login.php');



//adding supplier
if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $contact_person = $_POST['contact_person'];
  $address = $_POST['address'];
  if (empty($name) || empty($contact) || empty($address)) {
    $message[] = 'please fill out all';
  } else {
    $sql = "Select * from supplier where name = '$name'";
    $result = mysqli_query($conn, $sql);
    $existed = mysqli_num_rows($result);

    if ($existed > 0) {
      $message[] = "supplier already existed";
    } else {
      $insert = "INSERT INTO supplier(name,contact,contact_person,address) VALUES('$name','$contact','$contact_person','$address')";
      $result = mysqli_query($conn, $insert);
      if ($result) {
?>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
          swal({
            text: "Supplier added successfully",
            icon: "success",
            button: "OK",
          });
        </script>
<?php
      }
    }
  }
}
?>

<!--MAIN/ADD SUPPLIER-->
<main class="main">
  <div class="main-title">
    <p class="font-weight-bold">Product Suppplier</p>
  </div>

  <!--add supplier form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Add Supplier</h3>

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

          <input type="text" name="name" placeholder="Supplier: " class="text">
          <input type="text" name="contact" placeholder="Contact number: " class="text">
          <input type="text" name="contact_person" placeholder="Contact person: " class="text">
          <input type="text" name="address" placeholder="Address : " class="text">
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="submit" value="Add Supplier">
          </div>
        </form>
      </div>

    </div>
  </div>



  <!-- <div>
    <form action="#" method="POST">
      <input type="text" name="search" placeholder="Search product" autocomplete="off">
      <button name="searchBtn">Search</button>
    </form>
  </div> -->

  <!--supplier display table-->
  <?php
  if (isset($_POST["searchBtn"])) {
    $search = $_POST['search'];
    $sql = "Select * from `supplier` name='$search'";
    $select = mysqli_query($conn, $sql);
  } else {
    $select = mysqli_query($conn, "SELECT * FROM supplier order by date asc");
  }
  ?>

  <div class="product-display">
    <table class="product-display-table" id="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Supplier</th>
          <th>Contacts</th>
          <th>Contact person</th>
          <th>Address</th>
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
          <td><?php echo $row['contact']; ?></td>
          <td><?php echo $row['contact_person']; ?></td>
          <td><?php echo $row['address']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td>
            <a href="supplierUpdate.php?edit=<?php echo $row['id']; ?>" class="actionBtn"> <span class="material-icons-outlined">
                edit
              </span> </a>
          </td>
        </tr>
      <?php $no++;
      } ?>

    </table>
  </div>


</main>
<script src="script/openPopup.js"></script>

<script src="script/admin.js"></script>
</body>

</html>