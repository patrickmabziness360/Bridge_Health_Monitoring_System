<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect them to welcome page

require_once "../common/db_connect.php";

// Processing form data when form is submitted
$username_err = $password_err = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check if username is empty
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter username.";
  } else {
    $username = trim($_POST["username"]);
  }

  // Check if password is empty
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validate credentials
  if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT UserName, Password FROM  bridge.tblUsers WHERE UserName = ?;";

    if ($stmt = mysqli_prepare($conn, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = $username;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          // Bind result variables
          mysqli_stmt_bind_result($stmt, $username, $hashed_password);
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {

              //for local login
              //if($password==$hashed_password){

              // Password is correct, so start a new session
              session_start();

              // Store data in session variables
              $_SESSION["loggedin"] = true;
              $_SESSION["username"] = $username;

              // Redirect user to welcome page
              header("location: index.php");
            } else {
              // Display an error message if password is not valid
              $invalid_entry_err = "Invalid username or password";
            }
          }
        } else {
          // Display an error message if username doesn't exist
          $invalid_entry_err = "Invalid username or password";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Close connection
  mysqli_close($conn);
}

?>


<!DOCTYPE html>
<html lang="en">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="<?= $relative_path; ?>assets/images/Skylabs.JPG" />
  <title>Sign In </title>

  <!-- ========== All CSS files linkup ========= -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/LineIcons.css" />
  <link rel="stylesheet" href="assets/css/quill/bubble.css" />
  <link rel="stylesheet" href="assets/css/quill/snow.css" />
  <link rel="stylesheet" href="assets/css/fullcalendar.css" />
  <link rel="stylesheet" href="assets/css/morris.css" />
  <link rel="stylesheet" href="assets/css/datatable.css" />
  <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>
  <!-- ========== signin-section start ========== -->


  <section class="signin-section">
    <div class="container-fluid">

      <div class="row" style="margin-top: 50px;">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
          <div class="signin-wrapper">
            <div class="form-wrapper">

              <div class="text-center" style="margin-top: -50px;">Log In

              </div>
              <?php
              if (!empty($invalid_entry_err)) { ?>
                <div class="text-center">
                  <span class="text-danger"><b>
                      <?= $invalid_entry_err; ?>
                    </b>
                  </span>
                </div>
              <?php } ?>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="row">
                  <div class="col-12">
                    <div class="input-style-1">
                      <label>Username</label>
                      <input type="text" placeholder="Enter username" name="username">
                      <?php
                      if (!empty($username_err)) { ?>
                        <span class="text-danger"><b>
                            <?= $username_err; ?>
                          </b></span>
                      <?php } ?>
                    </div>

                  </div>
                  <!-- end col -->
                  <div class="col-12">
                    <div class="input-style-1">
                      <label>Password</label>
                      <input type="password" placeholder="Enter Password" name="password">
                      <?php
                      if (!empty($password_err)) { ?>
                        <span class="text-danger"><b>
                            <?= $password_err; ?>
                          </b></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="button-group d-flex justify-content-center flex-wrap">
                      <button class="main-btn primary-btn btn-hover w-100 text-center">
                        Sign In
                      </button>
                    </div>
                  </div>
                </div>
                <!-- end row -->
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-4"></div>
        <!-- end col -->
      </div>
      <!-- end row -->
    </div>
  </section>


  <!-- ========= All Javascript files linkup ======== -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/Chart.min.js"></script>
  <script src="assets/js/apexcharts.min.js"></script>
  <script src="assets/js/dynamic-pie-chart.js"></script>
  <script src="assets/js/moment.min.js"></script>
  <script src="assets/js/fullcalendar.js"></script>
  <script src="assets/js/jvectormap.min.js"></script>
  <script src="assets/js/world-merc.js"></script>
  <script src="assets/js/polyfill.js"></script>
  <script src="assets/js/quill.min.js"></script>
  <script src="assets/js/datatable.js"></script>
  <script src="assets/js/Sortable.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>



</html>