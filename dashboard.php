   <?php
    session_start();
    include_once "header.php";
    include_once "sidebar.php";
    include "includes/conn.php";


    if (!isset($_SESSION['email'])) {
      header('location:index.php');
      exit();
    }

    ?>


   <!--Main/Dashboard-->
   <?php
    // Function to get count using prepared statement
    function getCount($conn, $table)
    {
      $sql = "SELECT COUNT(*) as count FROM `$table`";
      $stmt = mysqli_prepare($conn, $sql);

      if ($stmt) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $count;
      } else {
        return 0;
      }
    }
    ?>
   <main class="main">
     <div class="main-title">
       <p class="font-weight-bold">DASHBOARD</p>
     </div>
     <div class="main-cards">
       <div class="card">
         <div class="card-inner">
           <p class="text-primary">SALES</p>
           <span class="material-icons-outlined text-black"> point_of_sale </span>
         </div>

         <span class="text-primary font-weight-bold"><?php echo getCount($conn, 'sales'); ?></span>
       </div>

       <div class="card">
         <div class="card-inner">
           <p class="text-primary">PRODUCTS</p>
           <span class="material-icons-outlined text-black">
             shopping_cart
           </span>
         </div>

         <span class="text-primary font-weight-bold"><?php echo getCount($conn, 'products'); ?></span>
       </div>
       <div class="card">
         <div class="card-inner">
           <p class="text-primary">CATEGORIES</p>
           <span class="material-icons-outlined text-black"> widgets </span>
         </div>

         <span class="text-primary font-weight-bold"><?php echo getCount($conn, 'category'); ?></span>
       </div>

       <div class="card">
         <div class="card-inner">
           <p class="text-primary">MEMBERS</p>
           <span class="material-icons-outlined text-black"> person_add </span>
         </div>
         <span class="text-primary font-weight-bold"><?php echo getCount($conn, 'customers'); ?></span>
       </div>
     </div>



     <div class="charts">
       <div class="charts-card">
         <p class="chart-title">Best Seller Products</p>
         <canvas id="product" height="50"></canvas>
       </div>

       <div class="charts-card">
         <p class="chart-title">Sales Graph</p>
         <canvas id="sales" height="300"></canvas>
       </div>
     </div>
   </main>
   </div>

   <!-- Charts -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="script/admin.js"></script>

   </body>

   </html>