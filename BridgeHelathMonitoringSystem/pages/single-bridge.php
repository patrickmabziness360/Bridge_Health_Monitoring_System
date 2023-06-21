<?php
  session_start();
  header("refresh: 5"); // Refresh the page after 5 seconds

  $relative_path = '../';
  include ('../common/db_connect.php');
  ////GET  NEWS/////
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $bridge_information = $conn->query("SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName,d.Water_level,d.CrackDepth,d.VibrationLevels,
    d.RoadStatus,d.BridgeStatus,d.StrainOnBridge
     FROM bridge.tblBridge LTP INNER JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID 
    INNER JOIN bridge.tblBridgeSensorData d ON LTP.BridgeID = d.BridgeID WHERE LTP.BridgeID =$id ORDER BY d.CreatedAt DESC LIMIT 1;");
    $result = $bridge_information -> fetch_assoc();

  }else{
    header("Location:../index.php");
  }
   
   include('../include/menu_bar.php');

?>

        <!-- partial -->
        <div class="flash-news-banner">
          <div class="container">
            <div class="d-lg-flex align-items-center justify-content-between">
              
                  <span>
                  <a href="<?= $relative_path; ?>index.php"  >Home</a>
                  </span>
                  <span>
                    Bridge Health Monitoring System(BHMS)
                </span>
               
                  
              <div class="d-flex">
                <span class="mr-3 text-danger">
                  <?php
                    $date = $conn->query("SELECT now() date");
                    $date = $date->fetch_assoc()['date'];
                    echo date('d F Y', strtotime($date));
                  ?>
                </span>
                <span class="mr-3 text-danger">
                <a href="<?= $relative_path; ?>admin/login.php"  >Login</a>
              </span>
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper">
          <div class="container">
            <div class="col-sm-12">
              <div class="card" data-aos="fade-up">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-8">
                      <div>
                        <h1 class="font-weight-600 mb-1">
                        Bridge Name : <?=$result['Name'];?>
                        </h1>
                        <h1 class="font-weight-60 mb-1">
                        Location : <?=$result['Location'];?>
                        </h1>
                        <h1 class="font-weight-600 mb-1">
                      
                        <div class="badge <?= ($result['RoadStatus'] == 'CLOSED') ? 'badge-danger' : 'badge-primary' ?> fs-20 font-weight-bold mb-3">
                           ROAD <?=$result['RoadStatus'];?>
                       </div>
                        </h1>
                        <p class="fs-13 text-muted mb-0">
                          <span class="mr-2">Date -</span><?=date('d F Y', strtotime($result['CreatedAt']));?>
                        </p>

                        <div class="mb-4 fs-15" style="border: 1px solid #dbdbdb;padding: 22px; border-top: none;">
                        <!-- <h1 class="font-weight-400 mb-1">
                        DUE TO THE FOLLOWING REASONS : 
                        </h1> -->
                        <h3 class="font-weight-600 mb-1">
                        <!--  -->
                        <?php

                          if ($result['RoadStatus'] == 'CLOSED') {
                            echo '<h1 class="font-weight-400 mb-1">DUE TO THE FOLLOWING REASONS:</h1>';
                            
                          // if ($result['VibrationLevels'] != 90000 ) {
                          //     echo '<h3 class="font-weight-400 mb-1">High Vibrations</h3>';
                          // } 
                          if ($result['CrackDepth'] > 13.0) {
                              echo '<h3>There exist a big crack which needs maintainance </h3>';
                          } if ($result['Water_level'] > 600) {
                            echo '<h3 class="font-weight-400 mb-1">High Water Level</h3>';
                        }
                        if ($result['StrainOnBridge'] > 1500) {
                          echo '<h3 class="font-weight-400 mb-1">Overload</h3>';
                         }

                          } else {
                            echo 'Bridge is safe to use.';
                          }

                          ?>

                        </h3>
                        </div>
                        <div class="rotate-img">
                          <img
                            src="<?=$relative_path;?>upload/<?=$result['AttachmentName'];?>"
                            alt="banner"
                            class="img-fluid mt-4 mb-4"
                          />
                        </div>
                        
                      </div>
                    </div>

                    <div class="col-lg-4" >
                      <h2 class="mb-4 text-primary font-weight-600">
                      Nearest Bridges
                      </h2>
                      <?php
                        while ($row = $nearestBridges->fetch_assoc()) {?>
                          <div class="row ml-2" >
                            <a href="single-bridge.php?id=<?=$row['BridgeID'];?>" style ="color: #032a63 !important;text-decoration: none;" >
                              <div class="col-sm-12">
                                <div class="border-bottom pb-4 pt-4">
                                  <div class="row">
                                    <div class="col-sm-7">
                                      <h5 class="font-weight-600 mb-1">
                                        <?=$row['Location'] ;?>
                                      </h5>
                                        <div class="fs-12">
                                        <span class="mr-2">Road Status </span>
                                        <div class="badge <?= ($row['RoadStatus'] == 'CLOSED') ? 'badge-danger' : 'badge-primary' ?> fs-12 font-weight-bold mb-3">
                                        <?=$row['RoadStatus'];?>
                                  </div>
                                    </div>
                                    <div class="col-sm-5">
                                      <div class="rotate-img">
                                        <img
                                          src="<?=$relative_path;?>upload/<?=$row['AttachmentName'];?>"
                                          alt="banner"
                                          class="img-fluid"
                                        />
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- main-panel ends -->
        <!-- container-scroller ends -->

        <!-- partial:partials/_footer.html -->
       

        <!-- partial -->
      </div>
    </div>
    <!-- inject:js -->
    <script src="<?=$relative_path;?>assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="<?=$relative_path;?>assets/vendors/aos/dist/aos.js/aos.js"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="<?=$relative_path;?>assets/js/demo.js"></script>
    <script src="<?=$relative_path;?>assets/js/jquery.easeScroll.js"></script>
    <!-- End custom js for this page-->
  </body>

<!-- Mirrored from www.bootstrapdash.com/demo/world-time/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 22 Jan 2023 20:18:52 GMT -->
</html>
