<?php
session_start();

//the credentials for the php login are stored in a seperate file
require("credentials.php");

if(!isset($_POST["search"]))
{
    header("location: index.php");
}

$search_key = htmlspecialchars($_POST["query"]);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($search_key == "")
{
    $sql = "SELECT * FROM drivers;";
}else{
    $sql = "SELECT * FROM drivers WHERE plate LIKE '%$search_key%';";
}

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Parking Application</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"crossorigin="anonymous">

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
<!--         <div id="content" class="active container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                </div>
            </nav> -->
            <!-- form area -->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ml-auto mr-auto">
                    <form class="" id="search_form" action="searchbyplate.php" method="post">
                        <div class="row">
                            <div class="col-md-5">
                                <select name="type" class="form-control" id="search_select">
                                    <option value="plate">license plate #</option>
                                    <option value="first_name">first name</option>
                                    <option value="last_name">last name</option>
                                    <option value="decal">decal #</option>
                                </select> 
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" autocomplete="off" type="text" name="query">
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
            <!-- Begin results area -->
            <div class="row justify-content-center">
                <div class="col-12">
                    <h4 class="result-header">Number of results: <b><?php echo $result->num_rows ?></b></h4>
                </div>
            </div>
            <?php
            if ($result->num_rows == 0 && $search_key != "") {
                echo '<div class="row justify-content-center">
                <div class="col-4">
                <form action="create.php" method="POST">
                <input class="d-none" type="text" name="query" value="' . $search_key . '">
                <button class="btn btn-info" type="submit" name="submit">Add '. $search_key . ' to the database</button>
                </form>
                </div>
                </div>';
            }
            ?>

            <?php 
            if ($result->num_rows > 0) {
            //create a table
                echo "<table class='table'>
                <thead>
                <tr>
                <th scope='col'>Plate</th>
                <th scope='col' class='d-none d-sm-table-cell'>First Name</th>
                <th scope='col' class='d-none d-sm-table-cell'>Last Name</th>
                <th scope='col'>Make</th>
                <th scope='col'>Model</th>
                <th scope='col' class='d-none d-sm-table-cell'>Decals</th>
                <th scope='col' class='d-none d-sm-table-cell'>Violations</th>
                </tr>
                </thead>
                <tbody>";
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    //get the number of decals
                    $sql2 = "SELECT COUNT(plate) AS num FROM permits WHERE plate = '{$row["plate"]}';";
                    $result2 = $conn->query($sql2);
                    $row2 = $result2->fetch_assoc();
                    $number_of_permits = $row2["num"];
                    //get the number of violations
                    $sql3 = "SELECT COUNT(plate) AS num FROM violations WHERE plate = '{$row["plate"]}';";
                    $result3 = $conn->query($sql3);
                    $row3 = $result3->fetch_assoc();
                    $number_of_violations = $row3["num"];

                    echo '
                    <tr>
                    <td>
                    <form action="view.php" method="POST">
                    <button class="btn btn-info" type=submit name="view_button" value=' . $row["plate"] . '>' . $row["plate"] . '
                    </button>
                    <input class="d-none" name="plate" type"text" value="' . $row["plate"] . '"></input>
                    </form>
                    </td>';
                    echo '<td class="d-none d-sm-table-cell">' . $row["first_name"] . '</td>';
                    echo '<td class="d-none d-sm-table-cell">' . $row["last_name"] . '</td>';
                    echo '<td>' . $row["make"] . '</td>';
                    echo '<td>' . $row["model"] . '</td>';
                    echo '<td class="d-none d-sm-table-cell">' . $number_of_permits . '</td>';
                    echo '<td class="d-none d-sm-table-cell">' . $number_of_violations . '</td>';
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } 
            ?>
            <!-- end results rea -->
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
    <script src="scripts/search_scripts.js" crossorigin="anonymous"></script>

    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

</body>

</html>