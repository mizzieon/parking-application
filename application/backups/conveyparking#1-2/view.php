<?php
session_start();

//the credentials for the php login are stored in a seperate file
require("credentials.php");

//Send the user back to the homepage if they didnt come here from a valid source
if(!isset($_POST["view_button"]) && !(isset($_SESSION["create_data_ready"])) &&!(isset($_POST["violation"])))
{
    header("location: index.php");
}

//obtain the plate information from either POST or SESSION
if(isset($_POST["plate"]))
{
    $search_key = $_POST["plate"];
}else{
    $search_key = $_SESSION["create_plate"];
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//create a new violation if the violation button was clicked
if(isset($_POST["violation"]))
{
    $comment = $_POST["comment"];
    $plate = $search_key;

    $sql = "INSERT INTO violations (plate,violation_date,comment) VALUES ('$plate',(SELECT NOW()),'$comment');";
    $conn->query($sql);
}

$driver_information_sql = "SELECT * FROM drivers WHERE plate = '$search_key';";
$driver_permits_sql = "SELECT * FROM permits WHERE plate = '$search_key';";
$driver_violations_sql = "SELECT * FROM violations WHERE plate = '$search_key';";

$driver_information_result = $conn->query($driver_information_sql);
$driver_permits_result = $conn->query($driver_permits_sql);
$driver_violations_result = $conn->query($driver_violations_sql);

//send back to home page if the plate doesnt exist
if($driver_information_result->num_rows == 0)
{
    //header("location: index.php");
}

$conn->close();

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
            <div class="sidebar-header">
                <h3>Parking Application</h3>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="index.php">Search</a>
                </li>
                <li>
                    <a href="create.php">Create</a>
                </li>
                <li>
                    <a href="violations.php">Violations</a>
                </li>
            </ul>

        </nav>
        <!-- end sidebar -->
        <!-- Page Content -->
        <div id="content" class="active container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                </div>
            </nav>
            <!-- form area -->
            <div class="row justify-content-center">
                <div class="col-12 col-sm-4 col-md-4">
                    <form class="row" action="edit.php" method="POST">
                        <div class="col-6 col-sm-2 mb-2 mb-sm-0">
                            <button class="ml-auto btn" type="submit" name="edit">Edit</button>
                        </div>
                        <input class="d-none" type="text" name="current_plate" value="<?php echo $search_key ?>">
                    </form>
                </div>
                <div class="col-12 col-md-6">
                    <form action="view.php" method="POST">
                        <input type="text" class="d-none" name="plate" value="<?php echo $search_key ?>">
                        <div class="row">
                            <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                                <button class="btn" type="submit" name="violation">Add Violation</button>
                            </div>
                            <div class="col-12 col-sm-5 mr-auto">
                                <input class="form-control" type="text" name="comment" placeholder="Comment">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end form area -->

            <hr>

            <!-- driver information -->
            <?php $row = $driver_information_result->fetch_assoc(); ?>
            <div class="row ml-2">
                <div class="col-12 text-info">
                    <h4>Driver Information</h4>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>First Name:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["first_name"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Last Name:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["last_name"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>License Plate:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["plate"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Year:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["year"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Make:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["make"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Model:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["model"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Color:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["color"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Department:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["department"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Department 2:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["sub_department"]; ?></div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-1 col-xl-2">
                    <div>Supervisor:</div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 text-info">
                    <div><?php echo $row["supervisor"]; ?></div>
                </div>
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <!-- end driver information -->
            <!-- permits -->
            <div class="row ml-2 mt-1">
                <div class="col text-info">
                    <h4>Permits</h4>
                </div>
            </div>
            <div class="row ml-2">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="d-none d-sm-table-cell">Permit ID</th>
                                <th>Permit Number</th>
                                <th>Color</th>
                                <th>Date Assigned</th>
                                <th>Plate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $driver_permits_result->fetch_assoc()) {
                            echo "<tr>";
                            echo '<td class="d-none d-sm-table-cell">' . $row["id"] . '</td>';
                            echo '<td>' . $row["num"] . ' </td>';
                            echo '<td>' . $row["color"] . '</td>';
                            echo '<td>' . $row["date_assigned"] . '</td>';
                            echo '<td>' . $row["plate"] . '</td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    </table>
                </div>           
            </div>
            <!-- end permits -->
            <!-- Violations -->
            <div class="row ml-2">
                <div class="col text-info">
                    <h4>Violations</h4>
                </div>
            </div>
            <div class="row ml-2">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Violation Number</th>
                                <th>Plate</th>
                                <th>Date</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $driver_violations_result->fetch_assoc()) {
                            echo "<tr>";
                            echo '<td>' . $row["num"] . '</td>';
                            echo '<td>' . $row["plate"] . ' </td>';
                            echo '<td>' . $row["violation_date"] . '</td>';
                            echo '<td>' . $row["comment"] . '</td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>           
        </div>
        <!-- Violations -->
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