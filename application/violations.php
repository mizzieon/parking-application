<?php
require("credentials.php");

session_start();

if (isset($_POST["submit"])) {
  $upper_range = $_POST["date_to"];
  $lower_range = $_POST["date_from"];
  $limit = $_POST["view_amount"];

    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

    //construct query
  if ($limit == "all") {
    $sql = 'SELECT * FROM violations WHERE 
    violation_date >= "' . $lower_range . '" 
    && violation_date <= "' . $upper_range . '"
    ORDER BY violation_date DESC;';
  }else{
    $sql = 'SELECT * FROM violations WHERE 
    violation_date >= "' . $lower_range . '" 
    && violation_date <= "' . $upper_range . '"
    ORDER BY violation_date DESC
    LIMIT ' . $limit . ';';
  }

    //perform query
  $result = $conn->query($sql);
}else{
  $upper_range_timestamp = strtotime("+1 day");
  $upper_range = date("Y-m-d", $upper_range_timestamp);
  $lower_range_timestamp = strtotime("-7 days");
  $lower_range = date("Y-m-d",$lower_range_timestamp);
  $limit = "100";

    // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

    //construct query
  $sql = 'SELECT * FROM violations WHERE 
  violation_date >= "' . $lower_range . '" 
  && violation_date <= "' . $upper_range . '"
  ORDER BY violation_date DESC
  LIMIT ' . $limit . '; ';

    //perform query
  $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Parking Application</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="css/styles.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>

  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

  <!-- Scrollbar Custom CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
</head>

<body>
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
        <!-- <div id="content" class="active container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                </div>
              </nav> -->
              <!-- form area -->
              <div class="row justify-content-center">
                <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                  <form class="form-inline" method="POST" action="violations.php">
                    <select class="form-control mr-3" name="view_amount">
                      <option value="10" <?php if($limit == 10){echo "selected";}?>>10</option>
                      <option value="25" <?php if($limit == 25){echo "selected";}?>>25</option>
                      <option value="50" <?php if($limit == 50){echo "selected";}?>>50</option>
                      <option value="100" <?php if($limit == 100){echo "selected";}?>>100</option>
                      <option value="all" <?php if($limit == "all"){echo "selected";}?>>All </option>
                    </select>
                    <label class="label mr-3" for="date_from">From</label>
                    <input class="form-control mr-3" type="date" name="date_from" value="<?php echo $lower_range; ?>">
                    <label class="label mr-3" for="date_to">To</label>
                    <input class="form-control" type="date" name="date_to" value="<?php echo $upper_range; ?>">
                    <input class="btn btn-sm btn-info ml-sm-3" type="submit" name="submit" value="Go">
                  </form>
                </div>
              </div>
              <!-- end form area -->
              <hr>
              <!-- violation table -->
              <div class="row justify-content-center">
                <div class="col-12">
                  <h4 class="result-header">Number of results: <b><?php echo $result->num_rows; ?></b></h4>
                </div>
              </div>
              <div>
                <div>
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope='col'>Plate</th>
                        <th scope='col'>Violation #</th>
                        <th scope='col'>Violation Date</th>
                        <th scope='col'>Comment</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                            //output the data of each row
                      while ($row = $result->fetch_assoc()) {
                       echo '
                       <tr>
                       <td>
                       <form action="view.php" method="POST">
                       <button class="btn btn-info" type=submit name="view_button" value=' . $row["plate"] . '>' . $row["plate"] . '
                       </button>
                       <input class="d-none" name="plate" type"text" value="' . $row["plate"] . '"></input>
                       </form>
                       </td>';
                       echo '<td>' . $row["num"] . '</td>';
                       echo '<td>' . $row["violation_date"] . '</td>';
                       echo '<td>' . $row["comment"] . '</td>';
                       echo "</tr>";
                     } 
                     ?>
                   </tbody>
                 </table>
               </div>
             </div>
             <!-- end violation table -->
           </div>
           <!-- end page content -->
         </div>
         <!-- End wrapper -->


         <!-- jQuery CDN - Slim version (=without AJAX) -->
         <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
         <!-- Popper.JS -->
         <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
         <!-- Bootstrap JS -->
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

         <script src="js/scripts.js" crossorigin="anonymous"></script>

         <!-- jQuery Custom Scroller CDN -->
         <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

       </body>

       </html>