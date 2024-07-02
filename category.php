<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";


if (!isset($_SESSION['email']))
  header('location:index.php');

//adding catgory
if (isset($_POST['submit'])) {

  $cat_name = $_POST['cat_name'];
  if (empty($cat_name)) {
    $message[] = 'please fill out all';
  } else {
    $sql = "Select * from category where name = '$cat_name'";
    $result = mysqli_query($conn, $sql);
    $existed = mysqli_num_rows($result);

    if ($existed > 0) {
      $message[] = "category already existed";
    } else {
      $insert = "INSERT INTO category(name) VALUES('$cat_name')";
      $result = mysqli_query($conn, $insert);
      if ($result) {
?>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
          swal({
            text: "category added successfully",
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

<!--MAIN/Category-->
<main class="main">

  <div class="main-title">
    <p class="font-weight-bold">Category Management</p>
  </div>

  <!--add category form-->
  <div class="box">
    <div class="prod-cont">
      <h3>Add Category</h3>

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
          <input type="text" name="cat_name" placeholder="category name: " class="text">
          <div class="addProdBtn">
            <input type="submit" class="addbtn" name="submit" value="Add Category">
          </div>
        </form>
      </div>

    </div>
  </div>

  <!--category display table-->
  <?php
  $select = mysqli_query($conn, "SELECT * FROM category order by date asc");
  ?>

  <div class="product-display">
    <table class="product-display-table" id="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>

      <?php
      $no = 1;
      while ($row = mysqli_fetch_assoc($select)) { ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td>
            <a href="categoryUpdate.php?edit=<?php echo $row['id']; ?>" class="actionBtn"> <span class="material-icons-outlined">
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