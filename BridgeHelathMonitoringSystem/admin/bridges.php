<?php
include '../common/access.php';
include '../common/db_connect.php';
header("refresh: 5"); // Refresh the page after 5 seconds

$all_bridges = $conn->query("SELECT LTP.Name,LTP.BridgeID, LTP.Location, d.RoadStatus, d.BridgeStatus,d.CreatedAt
FROM bridge.tblBridge LTP 
LEFT JOIN (
    SELECT BridgeID, RoadStatus, BridgeStatus, MAX(CreatedAt) AS MaxCreatedAt
    FROM bridge.tblBridgeSensorData
    GROUP BY BridgeID
) AS maxSensorData ON LTP.BridgeID = maxSensorData.BridgeID
LEFT JOIN bridge.tblBridgeSensorData d ON maxSensorData.BridgeID = d.BridgeID AND maxSensorData.MaxCreatedAt = d.CreatedAt
ORDER BY d.CreatedAt DESC;");
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
    ?>
    <?php

    ////delete post ///////
    if (isset($_POST['delete_post'])) {
      $postid = $_POST['postid'];

      //DELETING A FILE 
      $sql = "SELECT AttachmentName FROM bridge.tblBridgeImages WHERE BridgeID = '$postid';";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $fileName = "";
      while ($row = $result->fetch_assoc()) {
        $fileName = $row["AttachmentName"];
      }

      if (unlink('../upload/' . $fileName)) {
        $conn->query("DELETE FROM bridge.tblBridge WHERE BridgeID = '$postid';");
        $message = "successfully deleted <b>" . $postid . "</b>";

        echo '<script language="javascript">'
          . ';location.href="bridges.php"'
          . '</script>';
      }
    }

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
                <h2>All Bridges</h2>
              </div>
            </div>
            <!-- end col -->
          </div>
          <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->
        <div class="tabs-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <div class="tab-style-2 card-style mb-30">

                <div class="tab-content" id="nav-tabContent2">
                  <div class="tab-pane fade show active" id="news">
                    <a href="add-bridge.php" class="btn primary-btn"> <span class=" lni lni-plus"></span> Add
                      Bridge</a><br><br><br>
                    <div class="table-responsive">
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
                          while ($row = $all_bridges->fetch_assoc()) { ?>
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
                    </div>
                  </div>
                  <div class="tab-pane fade" id="tabContent-2-2">
                    <h5>No items available</h5>
                  </div>
                  <div class="tab-pane fade" id="tabContent-2-3">
                    <h5>No items available</h5>
                  </div>
                </div>
              </div>
              <!-- end card -->
            </div>
          </div>
        </div>
      </div>
      <!-- end container -->
    </section>
   
  </main>
  

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