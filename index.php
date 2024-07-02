<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Login to SPMC</title>
  <link rel="short icon" type="image" href="images/pakshit.PNG" />
  <!--CSS link-->
  <link rel="stylesheet" href="styles/login.css" />
  <!--Material Icons-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />

  <script src="script/preventBack.js"></script>
</head>

<body>
  <h1 class="heading">STOCKPILE NAVIGATION</h1>
  <div class="bg-img">


    <div class="container">
      <div class="content1">
        <p class="aboutwho">Who we are?</p>
        <p class="message">
          Sapang Palay Multipurpose Cooperative is a member-owned cooperative
          established in March 18, 1991. We are committed to serving the
          unique needs of our community, offering a range of specialized
          services tailored to jeepney drivers and operators.
        </p>
      </div>

      <div class="content">
        <img class="logo" src="images/spmc_logo.PNG" />


        <?php if (isset($_GET['error'])) { ?>
          <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <form action="includes/login.inc.php" method="post">
          <div class="field">
            <span class="fa fa-user"></span>
            <input type="text" name="email" placeholder="Email or Phone" />
          </div>

          <div class="field space">
            <span class="fa fa-lock"></span>
            <input type="password" name="password" id="pass" placeholder="Password" />
            <span class="material-icons-outlined" id="eye" onclick="showpass()">
              visibility
            </span>

          </div>

          <div class="pass">
            <a href="#">Forgot Password?</a>
          </div>

          <div class="field">
            <button type="submit" name="submit">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="script/showpass.js"></script>
</body>

</html>