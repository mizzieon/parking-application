<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
else {
  //variables
  $username = $_SESSION['username'];
  $access = $_SESSION['access'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Parking Application</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>

  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

  <!-- Scrollbar Custom CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
</head>

<body class="">

  <div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
      <?php
        require "includes/sidebarcontent.php";
      ?>
    </nav>
    <!-- end sidebar -->
    <!-- Page Content -->
    <div id="content" class="active">
      <header>
        <?php
        require "header.php";
        ?>
      </header>
      <!-- <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
          <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>
            <span>Toggle Side Menu</span>
          </button>
        </div>
      </nav> -->
      <!-- form area -->
      <div class="row mt-3">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ml-auto mr-auto">
          <form class="" id="search_form" action="searchbyplate.php" method="post">
            <div class="row">
              <div class="col-md-5">
                <select name="type" id="search_select" class="form-control">
                  <option value="plate">license plate #</option>
                  <option value="first_name">first name</option>
                  <option value="last_name">last name</option>
                  <option value="decal">decal #</option>
                </select> 
              </div>
              <div class="col-md-5">
                <input class="form-control" autocomplete="off" type="text" name="query" >
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary" type="submit" name="search" value="search">search</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- end form area -->
      <hr>
    </div>
    <!-- end page content -->
  </div>
  <!-- End wrapper -->


  <!-- jQuery CDN - Slim version (=without AJAX) -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <!-- jQuery Custom Scroller CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

  <script src="js/scripts.js" crossorigin="anonymous"></script>
  <script src="scripts/search_scripts.js" crossorigin="anonymous"></script>

</body>

</html>