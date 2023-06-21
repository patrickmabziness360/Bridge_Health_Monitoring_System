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
      // Check input errors Before inserting in database
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
    $critical_bridges= $conn->query("SELECT B.*, D.* FROM bridge.tblBridge B
    LEFT JOIN bridge.tblBridgeSensorData D ON B.BridgeID = D.BridgeID
    WHERE D.BridgeStatus = 'NOT SAFE TO USE'
        AND D.CreatedAt >= DATE_SUB(NOW(), INTERVAL 5 SECOND)
    GROUP BY B.BridgeID
    ORDER BY D.CreatedAt DESC;
    ");

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
                   
                  <h2 class="text-danger" data-bs-toggle="modal">Critical Bridges</h2>
                    
                  <table id="table" class="table">
                        <thead>
                          <tr>
                            
                            
                            <th>
                              <h6>Bridge Name</h6>
                            </th>
                            <th>
                              <h6>Location</h6>
                            </th>
                            <th>
                              <h6>Sensor Status</h6>
                            </th>
                            <th>
                              <h6>Bridge Status</h6>
                            </th>
                            <th>
                              <h6> Last Active </h6>
                            </th>
                            <th data-type="date" data-format="YYYY/MM/DD">
                              <h6>Action</h6>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $serial = 1;
                          while ($row = $critical_bridges->fetch_assoc()) { ?>
                            <tr>
                              
                            
                              <td>
                                <p> <a href="SensorDetails.php?id=<?= $row['BridgeID']; ?>">
                                <?= substr($row['Name'], 0, 40); ?> </a></p>
                              </td>
                              <td> <p><a href="SensorDetails.php?id=<?= $row['BridgeID']; ?>">
                                <?= substr($row['Location'], 0, 40); ?> </a> </p>
                              </td>


                              <td>
                              
                                  <?php
                                    $date = $conn->query("SELECT NOW() AS date");
                                    $date = $date->fetch_assoc()['date'];
                                    $currentTime = strtotime($date);
                                    
                                    $createdAt = $row['CreatedAt'];
      
                                      if ($createdAt === null || ($currentTime - strtotime($createdAt)) > 10) {
                                        echo '<p class="badge fs-12 font-weight-bold mb-3 text-danger">Offline</p>';
                                      } else {
                                        echo '<p class="badge fs-12 font-weight-bold mb-3 text-success">Online</p>';
                                      }
                                  ?>
                            </td>

                              <!-- <td>
                              <p> <a href="SensorDetails.php?id=<?= $row['BridgeID']; ?>">
                                  <?= $row['BridgeStatus']; ?> </a>
                                </p>
                            </td> -->
                            <td>
                            <p>
                              <a href="SensorDetails.php?id=<?= $row['BridgeID']; ?>">
                                  <span class="<?= ($row['BridgeStatus'] == 'NOT SAFE TO USE') ? 'text-danger badge fs-12 font-weight-bold' : 'text-success badge fs-12 font-weight-bold' ?>">
                                      <?= $row['BridgeStatus']; ?>
                                  </span>
                              </a>
                          </p>

                            </td>
                            <td>
                              
                              <p><a href="SensorDetails.php?id=<?= $row['BridgeID']; ?>">
                                  <?= $row['CreatedAt']; ?></a>
                                </p>
                            </td>
                            <td>
                                <div class="action">
                                  <form class="text-primary"
                                    action="add-bridge.php?action=editPost&amp;qwert=<?php echo $row['BridgeID']; ?>"
                                    enctype="multipart/form-data" method="POST">

                                    <button type="submit" name="editpost" class="lni lni-pencil"></button>

                                  </form>
                                  | <button class="text-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?= $row['BridgeID']; ?>"><i class="lni lni-trash-can"></i>
                                  </button>
                                </div>
                              </td>

                              <!-- Delete Modal -->
                              <div class="modal fade" id="deleteModal<?= $row['BridgeID']; ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    
                                    <div class="modal-body">
                                      Are you sure you want to delete <b>
                                        <?= $row['Name']; ?>
                                      </b>
                                    </div>
                                    <div class="modal-footer">
                                      <form action="bridges.php" method="post">
                                        <button type="submit" name="delete_post" class="btn btn-primary">Yes</button>
                                        <input type="hidden" name="postid" value="<?= $row['BridgeID']; ?>">
                                        <button type="button" class="btn btn-secondary"
                                          data-bs-dismiss="modal">No</button>
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
          <!-- End Row -->
        </div>
        <!-- end container -->
    </section>
   
  </main>
 ->



</body>


</html>