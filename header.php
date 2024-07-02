<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SMPC</title>
  <link rel="short icon" type="image" href="images/pakshit.PNG" />
  <!--CSS link-->
  <link rel="stylesheet" href="styles/admin.css" />
  <link rel="stylesheet" href="styles/product.css" />
  <link rel="stylesheet" href="styles/modal.css">
  <!--Material Icons-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
  <!-- Material Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <script src="script/resubmitting.js"></script>

  <!-- <script src="script/preventBack.js"></script> -->
</head>

<body>
  <div class="container">
    <!--Header-->
    <header class="header">
      <div class="menu-icon" onclick="openSideBar()">
        <span class="material-symbols-outlined"> menu </span>
      </div>

      <div class="dropdown">
        <div class="inner" onclick="droplog()">
          <img src="images/admin-icon.png" alt="user-icon" class="user-pic" />
          <span> <?php echo $_SESSION["profile"]; ?><span class="material-icons-outlined">
              arrow_drop_down
            </span></span>

        </div>

        <div class="content" id="logout">
          <li>
            <span class="material-icons-outlined">
              person_outline
            </span>
            <span> <?php echo $_SESSION["name"]; ?></span>

          </li>
          <li class="logout">
            <span class="material-icons-outlined">
              power_settings_new
            </span>
            <a class="logout" href="includes/logout.inc.php">Logout</a>
          </li>
        </div>
      </div>
    </header>