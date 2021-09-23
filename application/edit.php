<?php
session_start();
require("credentials.php");

//send the user to the home page if the page was not properly accessedd. (POST or Redirection)
if(!isset($_SESSION["edit_data_ready"]) 
    && !isset($_POST["edit"]) 
    && !isset($_POST["remove_permit"])
    && !isset($_POST["remove_violation"])
    && !isset($_POST["save"])
    && !isset($_POST["add_permit"]
))
{
    header("location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //this section executes when the user clicks one of the save buttons
    if (isset($_POST["save"])) {
        //get the driver information from the POST global
        $first_name = ucfirst(htmlspecialchars($_POST["first_name"]));
        $last_name = ucfirst(htmlspecialchars($_POST["last_name"]));
        $make = ucfirst(htmlspecialchars($_POST["make"]));
        $model = ucfirst(htmlspecialchars($_POST["model"]));
        $year = ucfirst(htmlspecialchars($_POST["year"]));
        $color = ucfirst(htmlspecialchars($_POST["color"]));
        $department = ucfirst(htmlspecialchars($_POST["department"]));
        $sub_department = ucfirst(htmlspecialchars($_POST["department_2"]));
        $supervisor = ucfirst(htmlspecialchars($_POST["supervisor"]));
        $plate = htmlspecialchars($_POST["plate"]);

        //connect to the database and update the information in the
        //drivers table
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //Insert action into the action table
        $username = $_SESSION['username'];
        $target = $plate;
        $actionType = "profile edit";
        $details = "The profile for plate: {$plate} was edited. New values:
        \n Plate: {$plate}
        \n First Name: {$first_name}
        \n Last Name: {$last_name}
        \n Make: {$make}
        \n Model: {$model}
        \n Year: {$year}
        \n Color: {$color}
        \n Department: {$department}
        \n Sub Department: {$sub_department}
        \n Supervisor: {$supervisor}";

        $sql = "INSERT INTO actions (user_username, target, time_stamp , action_type, details) VALUES ('{$username}', '{$target}',(SELECT NOW()),'{$actionType}','{$details}');";
        $conn->query($sql);

        $sql = "UPDATE drivers SET
        first_name = '{$first_name}',
        last_name = '{$last_name}',
        year = '{$year}',
        make = '{$make}',
        model = '{$model}',
        color = '{$color}',
        department = '{$department}',
        sub_department = '{$sub_department}',
        supervisor = '{$supervisor}'
        WHERE plate = '{$plate}';";

        $conn->query($sql);

        //update the decals
        while (count($_SESSION["permit_id_numbers"]) > 0) {
            //there is an array stored in SESSION that contains
            //all the permit id numbers that were read from the database
            $current_id = array_pop($_SESSION["permit_id_numbers"]);

            $current_num = $_POST["permit_number{$current_id}"];
            $current_color = $_POST["color{$current_id}"];
            $current_date_assigned = $_POST["date_assigned{$current_id}"];
            $current_plate = $_POST["plate{$current_id}"];

            $sql = "UPDATE permits SET
            num = '$current_num',
            color = '$current_color',
            date_assigned = '$current_date_assigned',
            plate = '$current_plate'
            WHERE id = '$current_id';";

            $conn->query($sql);

            $username = $_SESSION['username'];
            $target = $plate;
            $actionType = "decal edit";
            $details = "A decal belonging to plate: {$plate} was edited. New values:
            \n Number: {$current_num}
            \n Color: {$current_color}
            \n Date Assigned: {$current_date_assigned}
            \n plate: {$current_plate}";

            $sql = "INSERT INTO actions (user_username, target, time_stamp , action_type, details) VALUES ('{$username}', '{$target}',(SELECT NOW()),'{$actionType}','{$details}');";
            $conn->query($sql);

        }

        //send the user to the view page for the selected entry
        $_SESSION["create_data_ready"] = true;
        $_SESSION["create_plate"] = $plate;
        header("location: view.php");

    }
    //this section executes if the user clicks the "remove" button from the violations table
    if(isset($_POST["remove_violation"]))
    {
        //Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //remove the permit attached to the selected ID
        if (isset($_POST["violation_to_remove"])) {
            //obtain information for the action log
            $sql = "SELECT * FROM violations WHERE num = '{$_POST["violation_to_remove"]}';";
            $violation_row_data_result = $conn->query($sql);
            $violation_row_data = $violation_row_data_result->fetch_assoc();
            $violation_id = $violation_row_data["num"];
            $violation_add_date = $violation_row_data["violation_date"];
            $violation_plate = $violation_row_data["plate"];
            $violation_comment = $violation_row_data["comment"];

            $username = $_SESSION['username'];
            $target = $violation_plate;
            $actionType = "violation remove";
            $details = "A violation belonging to plate: {$violation_plate} was removed. Values:
            \n Number: {$violation_id}
            \n Add date: {$violation_add_date}
            \n Violation Date: {$violation_add_date}
            \n plate: {$violation_plate}";

            $sql = "INSERT INTO actions (user_username, target, time_stamp , action_type, details) VALUES ('{$username}', '{$target}',(SELECT NOW()),'{$actionType}','{$details}');";
            $conn->query($sql);

            $sql = "DELETE FROM violations WHERE num = '{$_POST["violation_to_remove"]}';";
            $conn->query($sql);
        }

        $first_name = ucfirst(htmlspecialchars($_POST["first_name"]));
        $last_name = ucfirst(htmlspecialchars($_POST["last_name"]));
        $make = ucfirst(htmlspecialchars($_POST["make"]));
        $model = ucfirst(htmlspecialchars($_POST["model"]));
        $year = ucfirst(htmlspecialchars($_POST["year"]));
        $color = ucfirst(htmlspecialchars($_POST["color"]));
        $department = ucfirst(htmlspecialchars($_POST["department"]));
        $sub_department = ucfirst(htmlspecialchars($_POST["department_2"]));
        $supervisor = ucfirst(htmlspecialchars($_POST["supervisor"]));
        $plate = htmlspecialchars($_POST["plate"]);

        //get permit information from the database
        $sql = "SELECT * FROM permits WHERE plate = '$plate';";
        $permits_result = $conn->query($sql);

        //get violation information from the database
        $sql = "SELECT * FROM violations WHERE plate = '$plate';";
        $violations_result = $conn->query($sql);

        //get a list of departments from the database
        $sql = "SELECT DISTINCT department FROM drivers ORDER BY department ASC;";
        $departments_result = $conn->query($sql);

        //get a list of sub_departments from the database
        $sql = "SELECT DISTINCT sub_department FROM drivers WHERE sub_department != '' ORDER BY sub_department ASC;";
        $sub_departments_result = $conn->query($sql);

    //get a list of supervisors from the database
        $sql = "SELECT DISTINCT supervisor FROM drivers WHERE supervisor != '' ORDER BY supervisor ASC;";
        $supervisor_result = $conn->query($sql);
    }
    //this section executes if the user clicks the "add permit" button
    if(isset($_POST["add_permit"]))
    {
        //Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //add a new permit into the 'permits' table
        $sql = "INSERT INTO permits (num, color, date_assigned, plate) VALUES ('','',NULL,'{$_POST["plate"]}');";
        $conn->query($sql);
        $idOfLastInsert = $conn->insert_id;

        $username = $_SESSION['username'];
        $target = $_POST["plate"];
        $actionType = "add decal";
        $details = "A decal was added and assigned to plate: {$_POST["plate"]} . Id #: {$idOfLastInsert}";

        $sql = "INSERT INTO actions (user_username, target, time_stamp , action_type, details) VALUES ('{$username}', '{$target}',(SELECT NOW()),'{$actionType}','{$details}');";
        $conn->query($sql);

        $first_name = ucfirst(htmlspecialchars($_POST["first_name"]));
        $last_name = ucfirst(htmlspecialchars($_POST["last_name"]));
        $make = ucfirst(htmlspecialchars($_POST["make"]));
        $model = ucfirst(htmlspecialchars($_POST["model"]));
        $year = ucfirst(htmlspecialchars($_POST["year"]));
        $color = ucfirst(htmlspecialchars($_POST["color"]));
        $department = ucfirst(htmlspecialchars($_POST["department"]));
        $sub_department = ucfirst(htmlspecialchars($_POST["department_2"]));
        $supervisor = ucfirst(htmlspecialchars($_POST["supervisor"]));
        $plate = htmlspecialchars($_POST["plate"]);

        //get permit information from the database
        $sql = "SELECT * FROM permits WHERE plate = '$plate';";
        $permits_result = $conn->query($sql);

        //get violation information from the database
        $sql = "SELECT * FROM violations WHERE plate = '$plate';";
        $violations_result = $conn->query($sql);

        //get a list of departments from the database
        $sql = "SELECT DISTINCT department FROM drivers ORDER BY department ASC;";
        $departments_result = $conn->query($sql);

        //get a list of sub_departments from the database
        $sql = "SELECT DISTINCT sub_department FROM drivers WHERE sub_department != '' ORDER BY sub_department ASC;";
        $sub_departments_result = $conn->query($sql);

    //get a list of supervisors from the database
        $sql = "SELECT DISTINCT supervisor FROM drivers WHERE supervisor != '' ORDER BY supervisor ASC;";
        $supervisor_result = $conn->query($sql);
    }
    //this section eecutes if the user clicks the "remove decal" button
    if (isset($_POST["remove_permit"])) {
        //Retrieve entry information from the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //remove the permit attached to the selected ID
        if (isset($_POST["permit_to_remove"])) {
            $sql = "SELECT * FROM permits WHERE id = '{$_POST["permit_to_remove"]}';";
            $permit_row_data_result = $conn->query($sql);
            $permit_row_data = $permit_row_data_result->fetch_assoc();
            $permit_id = $permit_row_data["id"];
            $permit_date_assigned = $permit_row_data["date_assigned"];
            $permit_plate = $permit_row_data["plate"];
            $permit_num = $permit_row_data["num"];
            $permit_color = $permit_row_data["color"];

            $username = $_SESSION['username'];
            $target = $permit_plate;
            $actionType = "Decal remove";
            $details = "A Decal belonging to plate: {$permit_plate} was removed. Values:
            \n Number: {$permit_num}
            \n Assign date: {$permit_date_assigned}
            \n Id#: {$permit_id}
            \n Color: {$permit_color}";

            $sql = "INSERT INTO actions (user_username, target, time_stamp , action_type, details) VALUES ('{$username}', '{$target}',(SELECT NOW()),'{$actionType}','{$details}');";
            $conn->query($sql);

            $sql = "DELETE FROM permits WHERE id = '{$_POST["permit_to_remove"]}';";
            $conn->query($sql);
        }

        $first_name = ucfirst(htmlspecialchars($_POST["first_name"]));
        $last_name = ucfirst(htmlspecialchars($_POST["last_name"]));
        $make = ucfirst(htmlspecialchars($_POST["make"]));
        $model = ucfirst(htmlspecialchars($_POST["model"]));
        $year = ucfirst(htmlspecialchars($_POST["year"]));
        $color = ucfirst(htmlspecialchars($_POST["color"]));
        $department = ucfirst(htmlspecialchars($_POST["department"]));
        $sub_department = ucfirst(htmlspecialchars($_POST["department_2"]));
        $supervisor = ucfirst(htmlspecialchars($_POST["supervisor"]));
        $plate = htmlspecialchars($_POST["plate"]);

        //get permit information from the database
        $sql = "SELECT * FROM permits WHERE plate = '$plate';";
        $permits_result = $conn->query($sql);

        //get violation information from the database
        $sql = "SELECT * FROM violations WHERE plate = '$plate';";
        $violations_result = $conn->query($sql);

        //get a list of departments from the database
        $sql = "SELECT DISTINCT department FROM drivers ORDER BY department ASC;";
        $departments_result = $conn->query($sql);

        //get a list of sub_departments from the database
        $sql = "SELECT DISTINCT sub_department FROM drivers WHERE sub_department != '' ORDER BY sub_department ASC;";
        $sub_departments_result = $conn->query($sql);

        //get a list of supervisors from the database
        $sql = "SELECT DISTINCT supervisor FROM drivers WHERE supervisor != '' ORDER BY supervisor ASC;";
        $supervisor_result = $conn->query($sql);

    }

    //this code is executed when the user comes here from the 'view' page
    if(isset($_POST["edit"]))
    {
        $plate = $_POST["current_plate"];

        //Retrieve entry information from the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //get driver information from databse
        $sql = "SELECT * FROM drivers WHERE plate = '$plate';"; 
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $make = $row["make"];
        $model = $row["model"];
        $year = $row["year"];
        $color = $row["color"];
        $department = $row["department"];
        $sub_department = $row["sub_department"];
        $supervisor = $row["supervisor"];

        //get permit information from the database
        $sql = "SELECT * FROM permits WHERE plate = '$plate';";
        $permits_result = $conn->query($sql);

        //get violation information from the database
        $sql = "SELECT * FROM violations WHERE plate = '$plate';";
        $violations_result = $conn->query($sql);

        //get a list of departments from the database
        $sql = "SELECT DISTINCT department FROM drivers ORDER BY department ASC;";
        $departments_result = $conn->query($sql);

        //get a list of sub_departments from the database
        $sql = "SELECT DISTINCT sub_department FROM drivers WHERE sub_department != '' ORDER BY sub_department ASC;";
        $sub_departments_result = $conn->query($sql);

    //get a list of supervisors from the database
        $sql = "SELECT DISTINCT supervisor FROM drivers WHERE supervisor != '' ORDER BY supervisor ASC;";
        $supervisor_result = $conn->query($sql);
    }

}else{
    //get the license plate of the entry we will be working with
    $_SESSION["edit_data_ready"] = false;
    $plate = $_SESSION["edit_plate"];

    //Retrieve entry information from the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //get driver information from databse
    $sql = "SELECT * FROM drivers WHERE plate = '$plate';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $make = $row["make"];
    $model = $row["model"];
    $year = $row["year"];
    $color = $row["color"];
    $department = $row["department"];
    $sub_department = $row["sub_department"];
    $supervisor = $row["supervisor"];

    //get permit information from the database
    $sql = "SELECT * FROM permits WHERE plate = '$plate';";
    $permits_result = $conn->query($sql);

    //get violation information from the database
    $sql = "SELECT * FROM violations WHERE plate = '$plate';";
    $violations_result = $conn->query($sql);

    //get a list of departments from the database
    $sql = "SELECT DISTINCT department FROM drivers WHERE department != '' ORDER BY department ASC;";
    $departments_result = $conn->query($sql);

    //get a list of sub_departments from the database
    $sql = "SELECT DISTINCT sub_department FROM drivers WHERE sub_department != '' ORDER BY sub_department ASC;";
    $sub_departments_result = $conn->query($sql);

    //get a list of supervisors from the database
    $sql = "SELECT DISTINCT supervisor FROM drivers WHERE supervisor != '' ORDER BY supervisor ASC;";
    $supervisor_result = $conn->query($sql);
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
<!--         <div id="content" class="active container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                </div>
            </nav>
            <hr> -->
            <!-- form area -->
            <div class="row">
                <div class="col m-2">
                    <form class="form-group" action="edit.php" method="post">
                        <div class="row">
                            <div class="col text-sm-center">
                                <h3 class="">Driver Information</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit" name="save" value="save">Save</button>
                                <button class="btn btn-info" type="submit" name="remove_entry" value="Remove Entry">Remove Entry</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="first_name">First Name</label>
                                <input class="form-control" type="text" name="first_name" value="<?php echo $first_name ?>">
                            </div>
                            <div class="col">
                                <label for="last_name">Last Name</label>
                                <input class="form-control" type="text" name="last_name" value="<?php echo $last_name ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="plate">License Plate</label>
                                <input class="form-control" type="text" name="plate" value="<?php echo $plate ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="year">Year</label>
                                <input class="form-control" type="text" name="year" value="<?php echo $year ?>">
                            </div>
                            <div class="col">
                                <label for="make">Make</label>
                                <input class="form-control" type="text" name="make" value="<?php echo $make ?>">
                            </div>
                            <div class="col">
                                <label for="model">Model</label>
                                <input class="form-control" type="text" name="model" value="<?php echo $model ?>">
                            </div>
                            <div class="col">
                                <label for="color">Color</label>
                                <input class="form-control" type="text" name="color" value="<?php echo $color ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <datalist id="departments">
                                    <?php
                                    while ($department_rows = $departments_result->fetch_assoc()) {
                                        echo "<option value='" . $department_rows["department"] . "'>";
                                    }

                                    while ($sub_department_rows = $sub_departments_result->fetch_assoc()) {
                                        echo "<option value='" . $sub_department_rows["sub_department"] . "'>";
                                    }
                                    ?>
                                </datalist>
                                <label for="department">Department</label>
                                <input list="departments" autocomplete="off" class="form-control" type="text" name="department" value="<?php echo $department ?>">
                            </div>
                            <div class="col">
                                <label for="department_2">Department 2</label>
                                <input class="form-control" autocomplete="off" list="departments" type="text" name="department_2" value="<?php echo $sub_department?>">
                            </div>
                            <div class="col">
                                <datalist id="supervisors">
                                    <?php
                                    while ($supervisor_rows = $supervisor_result->fetch_assoc()) {
                                        echo "<option value='" . $supervisor_rows["supervisor"] . "'>";
                                    }
                                    ?>
                                </datalist>
                                <label for="supervisor">Supervisor</label>
                                <input class="form-control" list="supervisors" autocomplete="off" type="text" name="supervisor" value="<?php echo $supervisor ?>">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col text-sm-center">
                                <h3 class="">Permits</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit" name="save">Save</button>
                                <button class="btn btn-info" type="submit" name="add_permit">Add Permit</button>
                            </div>
                        </div>
                        <?php
                        if($permits_result->num_rows > 0)
                        {
        //reset the 'permit_id_numbers' array
                            if (isset($_SESSION["permit_id_numbers"])) {
                                unset($_SESSION["permit_id_numbers"]);
                            }
                            while($permit_row = $permits_result->fetch_assoc())
                            {
                                if(isset($_SESSION["permit_id_numbers"]))
                                {
                                    array_push($_SESSION["permit_id_numbers"],$permit_row["id"]);
                                }
                                else{
                                    $_SESSION["permit_id_numbers"] = array($permit_row["id"]);
                                }
                                $id = $permit_row["id"];
                                echo '
                                <div class="row">
                                <input class="d-none permit-to-remove" type="text" name="permit_to_remove" value="">
                                <div class="col">
                                <label for="permit_id">ID</label>
                                <input class="form-control" type="text" name="id" readonly value="' . $permit_row["id"] . '">
                                </div>
                                <div class="col">
                                <label for="permit_number">Permit Number</label>
                                <input class="form-control" type="text" name="permit_number' . $id . '" value="' . $permit_row["num"] . '">
                                </div>
                                <div class="col">
                                <label for="permit_color">Color</label>
                                <input class="form-control" type="text" name="color' . $id . '" value="' . $permit_row["color"] . '">
                                </div>
                                <div class="col">
                                <label for="date_assigned">Date Assigned</label>
                                <input class="form-control" type="date" name="date_assigned' . $id . '" value="' . $permit_row["date_assigned"] . '">
                                </div>
                                <div class="col">
                                <label for="plate">Plate</label>
                                <input class="form-control" type="text" readonly name="plate' . $id . '" value="' . $permit_row["plate"] . '">
                                </div>
                                <div class="col mt-4">
                                <button class="remove-permit-button btn btn-danger" type="submit" name="remove_permit" value="Remove" data-id="' . $permit_row["id"] . '">Remove</button>
                                </div>
                                </div>';
                            }
                        } 
                        ?>
                        <hr>
                        <div class="row">
                            <div class="col text-sm-center">
                                <h3 class="">Violations</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <?php
                                if($violations_result->num_rows > 0)
                                {
                                    echo "
                                    <input class='d-none violation-to-remove' type='text' name='violation_to_remove' value=''>
                                    <table class='table'>
                                    <thead>
                                    <tr>
                                    <th class='d-none d-sm-table-cell'>Violation Number</th>
                                    <th>Plate</th>
                                    <th>Date</th>
                                    <th>Comment</th>
                                    <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>";

                                    while ($violation_row = $violations_result->fetch_assoc()) {
                                        echo "
                                        <tr>
                                        <td class='d-none d-sm-table-cell'>" . $violation_row["num"] . "</td>
                                        <td>" . $violation_row["plate"] . "</td>
                                        <td>" . $violation_row["violation_date"] . "</td>
                                        <td>" . $violation_row["comment"] . "</td>
                                        <td><button class='remove-violation-button btn btn-danger' data-id='{$violation_row["num"]}' type='submit' name='remove_violation' value='remove'>Remove</button></td>
                                        </tr>
                                        ";
                                    }

                                    echo"
                                    </tbody>
                                    </table>";
                                } 
                                ?>
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

    <script type="text/javascript" src="scripts/editscripts.js"></script>

</body>

</html>