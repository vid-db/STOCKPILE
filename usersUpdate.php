<?php
session_start();
include_once "header.php";
include_once "sidebar.php";
include "includes/conn.php";
if (!isset($_SESSION['email']))
  header('location:login.php');
$id = $_GET['edit'];

if (isset($_POST["update"])) {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $pass = $_POST["pass"];
  $cpass = $_POST["cpass"];
  $profile = $_POST["profile"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
    $mail = mysqli_fetch_assoc($result);



    if (!mysqli_fetch_assoc($result) || $mail['email'] == $email) {
      if ($pass == "") {
        $sql = "UPDATE users set name =?,email=?,profile=? WHERE id =?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: users.php?error=stmtfailed");
          exit();
        }
        $stmt->bind_param("sssi", $name, $email, $profile, $id);
        $stmt->execute();
      } else {
        $hashpass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE users set name =?, email=?, pass=?, profile=? WHERE id =?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: users.php?error=stmtfailed");
          exit();
        }
        $stmt->bind_param("ssssi", $name, $email, $hashpass, $profile, $id);
        $stmt->execute();
      }



?>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
        swal({
          text: "User successfully Updated",
          icon: "success",
          button: "OK",
        }).then(() => {
          // Redirect to users.php
          window.location.href = "users.php";
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
      <h3>Update User</h3>
      <?php
      //messages
      if (isset($message)) {
        foreach ($message as $message) {
          echo '<span class="message">' . $message . '</span>';
        }
      }
      ?>
      <?php
      $select = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
      while ($row = mysqli_fetch_assoc($select)) {
      ?>
        <div class="form">
          <form action="" method="post" novalidate>
            <input type="text" name="name" placeholder="Name: " value="<?php echo $row['name']; ?>" class="text">
            <input type="email" name="email" placeholder="Email: " value="<?php echo $row['email']; ?>">
            <input type="password" name="pass" placeholder="Password: ">
            <input type="password" name="cpass" placeholder=" Confirm Password: ">
            <select name="profile" class="profileSelect">
              <option value="<?php echo $row['profile']; ?>"><?php echo $row['profile']; ?></option>
              <option value="administrator">Administrator</option>
              <option value="seller">Seller</option>
            </select>
            <div class="addProdBtn">
              <input type="submit" class="btn" name="update" value="Save">
            </div>
          </form>
        <?php } ?>
        </div>
    </div>
  </div>



  </div>
</main>



<script src="script/admin.js"></script>
</body>

</html>