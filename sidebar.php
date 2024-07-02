    <!--SideBar-->
    <sidebar id="sidebar">
      <div class="sidebar-title">
        <img class="logo" src="images/spmc_logo.PNG" />
        <span class="material-icons-outlined" onclick="closeSideBar()">
          close
        </span>
      </div>
      <h2 class="title">SMPC</h2>

      <ul class="sidebar-list">
        <li class="sidebar_item">
          <a href="dashboard.php"><span class="material-symbols-outlined"> home </span> Home</a>
        </li>

        <li class="sidebar_item">
          <a href="products.php"><span class="material-icons-outlined"> shopping_bag </span>
            Products</a>
        </li>

        <li class="sidebar_item">
          <a href="brand.php"><span class="material-icons-outlined"> widgets </span>
            Brands</a>
        </li>

        <li class="sidebar_item">
          <a href="category.php"><span class="material-symbols-outlined">
              category
            </span>
            Category</a>
        </li>

        <li class="sidebar_item">
          <a href="inventory.php"><span class="material-icons-outlined">
              inventory_2
            </span>
            Inventory</a>
        </li>
        <li class="sidebar_item">
          <a href="receiving.php"><span class="material-icons-outlined">
              rv_hookup
            </span>
            Receiving</a>
        </li>

        <li class="sidebar_item">
          <a href="supplier.php"><span class="material-icons-outlined">
              factory
            </span>
            Supplier</a>
        </li>

        <li class="sidebar_item">
          <a href="customer.php"><span class="material-symbols-outlined"> groups </span>
            Member</a>
        </li>
        <li class="sidebar_item">
          <a href="salesManage.php"><span class="material-symbols-outlined"> payments </span>
            Sales</a>
        </li>

        <li class="sidebar_item">
          <a href="users.php"><span class="material-symbols-outlined"> contact_mail </span>
            User Management</a>
        </li>
      </ul>

      <script>
        document.addEventListener("DOMContentLoaded", function() {
          // Replace 'seller' with the actual role/profile of the user
          var userRole = '<?php echo $_SESSION['profile']; ?>';

          var sidebarItems = document.querySelectorAll(".sidebar_item");

          sidebarItems.forEach(function(item) {
            var link = item.querySelector("a");

            // Check if the user is a seller and the link is not for "Sales," then disable the link
            if (userRole === 'seller' && link.getAttribute("href") !== "salesManage.php") {
              link.classList.add("disabled-link");

              link.addEventListener("click", function(event) {
                event.preventDefault();
              });
            }
          });
        });
      </script>
      <style>
        .disabled-link {
          pointer-events: none;
          color: #a39d9d;

        }

        .disabled-link:hover {
          background-color: transparent;
          /* Adjust as needed */
          cursor: auto;
          /* Adjust as needed */
        }
      </style>
    </sidebar>