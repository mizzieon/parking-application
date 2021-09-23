<?php
session_start();

require("credentials.php");
//Check and see if the license plate exists already
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["query"]))
{
    $plate = strtoupper($_POST['query']);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM drivers WHERE plate = '$plate'";

    $result = $conn->query($sql);

    //if the plate exists then go to the view page for that plate, else create an entry for the plate in the database;
    //information on the purpose of the session variables can be found in notes.php
    if($result->num_rows > 0)
    {
        $_SESSION["create_data_ready"] = true;
        $_SESSION["create_plate"] = $plate;
        header("location: view.php");
    }else{
        $_SESSION["edit_data_ready"] = true;
        $_SESSION["edit_plate"] = $plate;

        //Create the new entry into the database
        $sql = "
        INSERT INTO drivers VALUES ('','','','','','','$plate','','','');";
        $conn->query($sql);

        //Redirect to the edit page for the newly created entry
        header("location: edit.php");
}
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
            <hr>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ml-auto mr-auto mt-5">
                    <form class="" action="create.php" method="POST">
                        <div class="row">
                            <div class="col-4">
                                <label for="query">License Plate #</label>
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" autocomplete="off" type="text" name="query" required="true">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit" name="create" value="search">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end form area -->
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