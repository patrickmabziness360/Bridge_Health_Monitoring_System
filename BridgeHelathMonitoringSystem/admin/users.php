<?php
include '../common/access.php';
include '../common/db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<?php
include 'head.php';
?>

<body>
  <!-- ======== sidebar-nav start =========== -->
  <?php
  include 'side_bar.php';
  ?>
  <div class="overlay"></div>
  <!-- ======== sidebar-nav end =========== -->

  <!-- ======== main-wrapper start =========== -->
  <main class="main-wrapper">
    <!-- ========== header start ========== -->
    <?php
    //include 'header.php';

    //For creating new user Define variables and initialize with empty values
    $username = $password = $email = $confirm_password = $role = "";
    $username_err = $password_err = $confirm_password_err = $email_err = $role_err = "";

    


    // Create new user///////
    if (isset($_POST['submit'])) {

      // Validate username
      if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
      } else {
        // Prepare a select statement
        $sql = "SELECT UserName FROM bridge.tblUsers WHERE UserName = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_username);

          // Set parameters
          $param_username = trim($_POST["username"]);

          // Attempt to execute the prepared statement
          if (mysqli_stmt_execute($stmt)) {
            /* store result */
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
              $username_err = "This username is already taken.";
            } else {
              $username = trim($_POST["username"]);
            }
          } else {
            echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
        }
      }

      // validate email
    
      if (empty($_POST['email'])) {
        $email_err = "Please enter email";
      } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $email_err = "Invalid email format";
        } else {

          // Prepare a select statement
          $sql = "SELECT Email FROM bridge.tblUsers WHERE Email = ?";

          if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
              /* store result */
              mysqli_stmt_store_result($stmt);

              if (mysqli_stmt_num_rows($stmt) == 1) {
                $email_err = "This email is already taken.";
              } else {
                $email = trim($_POST["email"]);
              }
            } else {
              echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
            //$email = $_POST['email'];
          }
        }
      }


      // Check input errors before inserting in database
      if (empty($username_err) && empty($email_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO bridge.tblUsers (UserName,Email, Password) VALUES (?, ?, ?);";

        if ($stmt = mysqli_prepare($conn, $sql)) {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

          // Set parameters
          $param_username = $username;
          $param_email = $email;
          $param_password = password_hash($username, PASSWORD_DEFAULT); // Creates a password hash
    
          // Attempt to execute the prepared statement
          if (mysqli_stmt_execute($stmt)) {
            $success = 'User created successfully';
          } else {
            echo "Something went wrong. Please try again later";
          }

          // Close statement
          mysqli_stmt_close($stmt);
        }
      } else {
        echo '<script type="text/javascript">'
          . '$( document ).ready(function() {'
          . '$("#addUserModal").modal("show")'
          . '});'
          . '</script>';
      }

    }

    ////delete user ///////
    if (isset($_POST['delete_user'])) {
      $user = $_POST['username'];
      $conn->query("DELETE FROM bridge.tblUsers WHERE UserName = '$user';");
      $message = "successfully deleted <b>" . $user . "</b>";

    }
    //// Update a user//////
    
    if (isset($_POST['edit_user'])) {
      // Check input errors before inserting in database
      if (empty($username_err) && empty($email_err)) {

          $sql = "UPDATE bridge.tblUsers SET UserName=?,Email=? WHERE UserID=?;";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "ssi", $param_username, $param_email, $param_userid);

          // Set parameters
          $param_username = trim($_POST["username"]);
          ;
          $param_email = trim($_POST["email"]);
          ;
          $param_userid = trim($_POST["userid"]);

          // Attempt to execute the prepared statement
          if (mysqli_stmt_execute($stmt)) {
           
                $success = 'User created successfully';
          }else {
            echo "Something went wrong. Please try again later";
          }


          // Close statement
          mysqli_stmt_close($stmt);
        }
      } else {
        echo '<script type="text/javascript">'
          . '$( document ).ready(function() {'
          . '$("#addUserModal").modal("show")'
          . '});'
          . '</script>';
      }



    }
    $all_users_sql = "SELECT * FROM bridge.tblUsers;";
    $all_users = $conn->query($all_users_sql);
    ?>
    <!-- ========== header end ========== -->

    <!-- ========== table components start ========== -->
    <section class="table-components">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2>Users</h2>
              </div>
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->

        <!-- ========== tables-wrapper start ========== -->
        <div class="tables-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-style mb-30">
                <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                </div>
                <div class="table-wrapper table-responsive">
                  
                    <button class="btn primary-btn" data-bs-toggle="modal" data-bs-target="#addUserModal"><i
                        class="lni lni-plus"></i> Add New User</button>
                  
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="lead-info">
                          <h6>Username</h6>
                        </th>
                        <th class="lead-email">
                          <h6>Email</h6>
                        </th>

                        <th>
                          <h6>Action</h6>
                        </th>
                      </tr>
                      <!-- end table row-->
                    </thead>
                    <tbody>
                      <?php
                      while ($row = $all_users->fetch_assoc()) { ?>
                        <tr>
                          <td>
                            <div class="lead">
                              <div class="lead-text">
                                <p>
                                  <?= $row['UserName']; ?>
                                </p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p><a href="#0">
                                <?= $row['Email']; ?>
                              </a></p>
                          </td>

                          <td>
                              <div class="action">
                                <button class="text-primary" data-bs-toggle="modal"
                                  data-bs-target="#editModal<?= $row['UserName']; ?>">
                                  <i class="lni lni-pencil"></i>
                                </button> | <button class="text-danger" data-bs-toggle="modal"
                                  data-bs-target="#deleteModal<?= $row['UserName']; ?>"><i class="lni lni-trash-can"></i>
                                </button>
                              </div>

                          </td>

                          <!-- Edit Modal -->
                          <div class="modal fade" id="editModal<?= $row['UserName']; ?>" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                                    autocomplete="off">
                                    <input type="hidden" name="userid" class="form-control"
                                      value="<?php echo $row['UserID']; ?>">
                                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                      <label>Username</label>
                                      <input type="text" name="username" class="form-control"
                                        value="<?php echo $row['UserName']; ?>">
                                      <span class="text-danger">
                                        <?php echo $username_err; ?>
                                      </span>
                                    </div>
                                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                      <label>Email</label>
                                      <input type="email" name="email" class="form-control"
                                        value="<?php echo $row['Email']; ?>">
                                      <span class="text-danger">
                                        <?php echo $email_err; ?>
                                      </span>
                                    </div>
                                    
                                    <div class="form-group">
                                      <button type="submit" class="btn btn-primary" name="edit_user">Update</button>
                                      <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Delete Modal -->
                          <div class="modal fade" id="deleteModal<?= $row['UserName']; ?>" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete <b>
                                    <?= $row['UserName']; ?>
                                  </b>
                                </div>
                                <div class="modal-footer">
                                  <form action="users.php" method="post">
                                    <button type="submit" name="delete_user" class="btn btn-primary">Yes</button>
                                    <input type="hidden" name="username" value="<?= $row['UserName']; ?>">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </tr>
                      <?php }

                      ?>
                    </tbody>
                  </table>
                  <!-- end table -->
                </div>
              </div>
              <!-- end card -->
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->
        </div>
        <!-- ========== tables-wrapper end ========== -->
      </div>
      <!-- Modal -->
      <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Create New User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                  <span class="text-danger">
                    <?php echo $username_err; ?>
                  </span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                  <span class="text-danger">
                    <?php echo $email_err; ?>
                  </span>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>




      <!-- end container -->
    </section>

  </main>
 

  <!-- ============ Theme Option End ============= -->

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
  <script>
    const dataTable = new simpleDatatables.DataTable("#table", {
      searchable: true,
    });
  </script>
</body>


</html>