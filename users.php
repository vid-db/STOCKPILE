<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";

if (!isset($_SESSION['email']))
  header('location:index.php');

function generateIdNumber($length = 5)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $idNo = 'IdNo.2023';
  // Generate random bytes for better security
  $bytes = random_bytes($length);
  // Convert random bytes to a string of characters
  for ($i = 0; $i < $length; $i++) {
    $index = ord($bytes[$i]) % strlen($characters);
    $idNo .= $characters[$index];
  }
  return $idNo;
}

if (isset($_POST["submit"])) {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $pass = $_POST["pass"];
  $cpass = $_POST["cpass"];
  $profile = $_POST["profile"];
  $id_no = generateIdNumber();

  if (empty($name) || empty($email) || empty($pass) || empty($cpass) || empty($profile)) {
    $message[] = 'please fill out the form!';
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message[] = 'Invalid Email!';
  } else if ($pass !== $cpass) {
    $message[] = 'password dont match';
  } else {
    $sql = "Select * from users where email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: users.php?error=stmtfailed");
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!mysqli_fetch_assoc($result)) {
      $hashpass = password_hash($pass, PASSWORD_DEFAULT);
      $sql = "INSERT INTO users (id,name,email,pass,profile) VALUES(?,?,?,?,?)";
      $stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: users.php?error=stmtfailed");
        exit();
      }
      $stmt->bind_param("sssss", $id_no, $name, $email, $hashpass, $profile);
      $stmt->execute();
?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        swal({
          text: "User added successfully",
          icon: "success",
          button: "OK",
        });
      </script>
<?php
    } else {
      $message[] = 'Email already existed';
    }
    mysqli_stmt_close($stmt);
  }
}
?>

<!--Main/user management-->
<main class="main">

  <div class="main-title">
    <p class="font-weight-bold">User Management</p>
  </div>

  <div class="box">

    <div class="prod-cont">
      <h3>Add user</h3>
      <?php
      //messages
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<span class="message">' . $message . '</span>';
        }
      }
      ?>
      <div class="form">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
          <input type="text" name="name" placeholder="Name: " class="text">
          <input type="email" name="email" placeholder="Email: ">
          <input type="password" name="pass" placeholder="Password: ">
          <input type="password" name="cpass" placeholder=" Confirm Password: ">
          <select name="profile" class="profileSelect">
            <option value="">Select User Type</option>
            <option value="administrator">Administrator</option>
            <option value="seller">Seller</option>
          </select>
          <div class="addProdBtn">
            <input type="submit" class="btn" name="submit" value="Add User">
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php
  $select = mysqli_query($conn, "SELECT * FROM users order by date asc");
  ?>
  <div class="product-display">
    <table class="product-display-table" id="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Id</th>
          <th>Email</th>
          <th>Profile</th>
          <th>action</th>
        </tr>
      </thead>
      <?php
      $no = 1;
      while ($row = mysqli_fetch_assoc($select)) { ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['profile']; ?></td>
          <!-- <td><?php echo $row['date']; ?></td> -->
          <td>
            <a href="usersUpdate.php?edit=<?php echo $row['id']; ?>" class="actionBtn"> <span class="material-icons-outlined">
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