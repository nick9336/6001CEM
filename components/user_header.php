<!-- header section starts  -->

<header class="header">

   <nav class="navbar nav-1">
      <section class="flex">
         <a href="home.php" class="logo"><i class="fas fa-house"></i>MyEstate</a>

      </section>
   </nav>

   <nav class="navbar nav-2">
      <section class="flex">
         <div id="menu-btn" class="fas fa-bars"></div>

         <div class="menu">
            <ul>
               <li><a href="home.php">Home</a></li>
               <li><a href="listings.php">Buy/Rent Properties</a></li>
               <li><a href="#">Tools<i class="fas fa-angle-down"></i></a>
                  <ul>
                     <li><a href="search.php">Advanced Search</a></i></li>
                     <li><a href="mortgage.php">Mortgage Calculator</a></i></li>
                  </ul>
               </li>
               <li><a href="#">Advertise<i class="fas fa-angle-down"></i></a>
                  <ul>
                     <li><a href="post_property.php">Property Owner</a></i></li>
                     <li><a href="agent/login.php">Real Estate Agent</a></i></li>
                  </ul>
               </li>
               <li><a href="#">Support<i class="fas fa-angle-down"></i></a>
                  <ul>
                     <li><a href="about.php">About us</a></li>
                     <li><a href="contact.php">Contact us</a></li>               
                  </ul>
               </li>
               

            </ul>
         </div>
         <ul>
            <li><a href="saved.php">Saved <i class="far fa-heart"></i></a></li>
            <li><a href="dashboard.php">My Dashboard</a></li>
            <li><a href="#">Account <i class="fas fa-angle-down"></i></a>
               <ul>
                  <li><a href="login.php">Login now</a></li>
                  <li><a href="register.php">Register new</a></li>
               </ul>
               <ul>
                  <?php if($user_id != ''){ ?>
                  <li><a href="update.php">Update profile</a></li>
                  <li><a href="components/user_logout.php" onclick="return confirm('Logout from this website?');">Logout</a>
                  <?php } ?></li>
               </ul>
            </li>
         </ul>
      </section>
   </nav>

</header>

<!-- header section ends -->