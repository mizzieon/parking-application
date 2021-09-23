<?php
  session_start();
  require "includes/functions.php";

  //We need to reset the username_to_register variable in case the user leaves the createpassword.php
  //page without finishing registration
  if(isset($_SESSION['usernameToRegister'])){
    unset($_SESSION['usernameToRegister']);
  }

  //if the user is logged in already send them to the application
  //we will need to use a different session variable for the password creation
  if (isset($_SESSION['username'])) {
    header("Location: application/index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Parking Application Login</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body class="">
  <header>
    <?php
    require "header.php";
    ?>
  </header>

  <!-- begin notification section -->
  <?php
  if (isset($_GET['info'])) {
    $info = $_GET['info'];
    DisplayInfoMessage($info);
  }

  if (isset($_GET['error'])) {
    $error = $_GET['error'];
    DisplayErrorMessage($error);
  }

  ?>
  <!-- end notification section -->

  <div class="row h-100 text-center">
    <div class="col my-auto">
     <form action="includes/login.inc.php" method="POST" class="form-signin no-gutters">
      <h1 class="h3 mb-3 font-weight-normal">Please Sign in</h1>
      <label for="inputUsername" class="sr-only">Username</label>
      <input type="text" id="inputUsername" class="form-control" name="inputUsername" placeholder="Username" autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" placeholder="Password" id="inputPassword" class="form-control mb-3" name="inputPassword">
      <button type="submit" class="btn btn-lg btn-primary btn-block mb-3" name="inputSubmit">Submit</button>
      <p>Contact Security for Assistance</p>
    </form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>