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
    include 'header.php';

    //For creating new user Define variables and initialize with empty values
    $username = $password = $email = $confirm_password = $role = "";
    $username_err = $password_err = $confirm_password_err = $email_err = $role_err = "";
    $message = '';
    $userid = "";

    

    

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
          $email = $_POST['email'];
        }
      }

      // validate user role////
    
      if (empty($_POST['role'])) {
        $role_err = 'Please select role for the user';
      } else {
        $role = $_POST['role'];
      }

      // Validate password
      if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
      } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
      } else {
        $password = trim($_POST["password"]);
      }

      // Validate confirm password
      if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
      } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
          $confirm_password_err = "Password did not match.";
        }
      }

      // Check input errors before inserting in database
      if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO bridge.tblUsers (UserName,Email, Password) VALUES (?, ?, ?);";

        if ($stmt = mysqli_prepare($conn, $sql)) {
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

          // Set parameters
          $param_username = $username;
          $param_email = $email;
          $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
    
          // Attempt to execute the prepared statement
          if (mysqli_stmt_execute($stmt)) {
            ///give user role
            $conn->query("INSERT INTO bridge.tblUserRoles (UserName,RoleID) VALUES('$username',$role);");
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

    //// Update a user//////
    
    if (isset($_POST['edit_user'])) {
      // Check input errors before inserting in database
      if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {

        $password = password_hash($_POST["confirm_password"], PASSWORD_DEFAULT);

        if (isset($_POST['confirm_password'])) {
          $sql = "UPDATE bridge.tblUsers SET UserName=?,Email=?,Password='$password' WHERE UserID=?;";
        } else if (isset($_POST['confirm_password']) == null) {
          $sql = "UPDATE bridge.tblUsers SET UserName=?,Email=? WHERE UserID=?;";
        }


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
            ///update user role
    
            $sql2 = "UPDATE bridge.tblUserRoles SET RoleID=? WHERE UserName=?;";
            if ($stmt2 = mysqli_prepare($conn, $sql2)) {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt2, "is", $param_roleid, $param_username);
              // Set parameters
              $param_username = trim($_POST["username"]);
              $param_roleid = trim($_POST["role"]);

              if (mysqli_stmt_execute($stmt2)) {

                $success = 'User created successfully';
              }
            } else {
              echo "Something went wrong. Please try again later";
            }

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

    $all_bridges = $conn->query("SELECT * FROM bridge.tblBridge WHERE BridgeStatus ='Critical';");
    $all_bridges_Count = $conn->query("SELECT * FROM bridge.tblBridge;");
    $all_users_sql = "SELECT * FROM bridge.tblUsers;";
    $all_users = $conn->query($all_users_sql);
    ?>

    <section class="section">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2>Dashboard</h2>
              </div>
            </div>

            <!-- end col -->
          </div>
          <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->
        <div class="row">
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="icon-card mb-30">
              <div class="icon purple">
                <i class="lni lni-empty-file"></i>
              </div>
              <div class="content">
                <h6 class="mb-10">Total Bridges</h6>
                <h3 class="text-bold mb-10">
                  <?= mysqli_num_rows($all_bridges_Count); ?>
                </h3>
              </div>
            </div>
            <!-- End Icon Cart -->
          </div>
          <!-- End Col -->

          <!-- End Col -->
          <div class="col-xl-3 col-lg-4 col-sm-6">

            <div class="icon-card mb-30">
              <div class="icon success">
                <i class="lni lni-user"></i>
              </div>
              <div class="content">
                <h6 class="mb-10">Total Users</h6>
                <h3 class="text-bold mb-10">
                  <?= mysqli_num_rows($all_users); ?>
                </h3>
              </div>
            </div>

          </div>


          <!-- End Row -->
          <div class="tables-wrapper">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-style mb-30">
                  <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                  </div>
                  <div class="table-wrapper table-responsive">
                   
                      <button class="btn primary-btn" data-bs-toggle="modal">
                           Critical Bridges</button>
                    
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="lead-info">
                            <h6>Bridge Name</h6>
                          </th>
                          <th class="lead-email">
                            <h6>Location</h6>
                          </th>
                          <th class="lead-phone">
                            <h6>Status</h6>
                          </th>
                          <th class="lead-company">
                            <h6>Road Status</h6>
                          </th>
                          <th>
                            <h6>Action</h6>
                          </th>
                        </tr>
                        <!-- end table row-->
                      </thead>
                      <tbody>
                        <?php
                        while ($row = $all_bridges->fetch_assoc()) { ?>
                          <tr>
                            <td>
                              <div class="lead">
                                <div class="lead-text">
                                  <p>
                                    <?= $row['Name']; ?>
                                  </p>
                                </div>
                              </div>
                            </td>
                            <td>
                              <p><a href="#0">
                                  <?= $row['Location']; ?>
                                </a></p>
                            </td>
                            <td>
                              <p>
                                <?= $row['BridgeStatus']; ?>
                              </p>
                            </td>
                            <td>
                              <?= $row['RoadStatus']; ?>
                            </td>
                            <td>

                             
                                <div class="action">
                                  <button class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row['Name']; ?>">
                                    <i class="lni lni-pencil"></i>
                                  </button> |
                                  <button class="text-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?= $row['Name']; ?>"><i class="lni lni-trash-can"></i>
                                  </button>
                                 
                                </div>
                            

                            </td>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['UserName']; ?>" tabindex="-1"
                              aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit Bridge</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                      aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="index.php" method="post" autocomplete="off">
                                      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                        <input type="hidden" name="userid" class="form-control"
                                          value="<?php echo $row['UserID']; ?>">
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


                                      <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                                        <label>Role</label>
                                        <select name="role" class="form-control">
                                          <option selected disabled hidden>--Select--</option>
                                          <?php
                                          $all_user_roles = $conn->query("SELECT * FROM bridge.tblRoles;");
                                          while ($row2 = $all_user_roles->fetch_assoc()) { ?>
                                            <option <?php if ($row['RoleName'] == $row2['RoleName'])
                                              echo "selected"; ?>
                                              value="<?= $row2['RoleID'] ?>"><?= $row2['RoleName'] ?></option>
                                          <?php }
                                          ?>
                                        </select>
                                        <span class="text-danger">
                                          <?php echo $role_err; ?>
                                        </span>
                                      </div>
                                      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control"
                                          value="<?php echo $password; ?>">
                                        <span class="text-danger">
                                          <?php echo $password_err; ?>
                                        </span>
                                      </div>
                                      <div
                                        class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control"
                                          value="<?php echo $confirm_password; ?>">
                                        <span class="text-danger">
                                          <?php echo $confirm_password_err; ?>
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
                            <!-- <div class="modal fade" id="deleteModal<?= $row['UserName']; ?>" tabindex="-1"
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
                                    <form action="index.php" method="post">
                                      <button type="submit" name="delete_user" class="btn btn-primary">Yes</button>
                                      <input type="hidden" name="username" value="<?= $row['UserName']; ?>">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div> -->
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
          <!-- End Row -->
        </div>
        <!-- end container -->
    </section>
   
  </main>
 ->



</body>


</html>