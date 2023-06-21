<?php

$relative_path = '';
include('common/db_connect.php');
include('include/menu_bar.php');

header("refresh: 5"); // Refresh the page after 5 seconds

$date = $conn->query("SELECT NOW() AS date");
$date = $date->fetch_assoc()['date'];
$currentTime = strtotime($date);


?>

<!-- partial -->
<div class="flash-news-banner">
  <div class="container">
    <div class="d-lg-flex align-items-center justify-content-between">
    
      <span>
         Bridge Health Monitoring System(BHMS)
      </span>
      <!-- <span>
         Group 14
      </span> -->

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
    <div class="row" data-aos="fade-up">
      <div class="col-xl-8 stretch-card grid-margin">
        <div class="position-relative">
          <?php
          while ($row = $closest_bridge->fetch_assoc()) { ?>
            <!-- <a href="pages/single-bridge.php?id=<?= $row['BridgeID']; ?>"> -->
              <img src="upload/<?= $row['AttachmentName']; ?>" alt="banner" class="img-fluid" />
              <div class="banner-content" style="background-color: #00000085;">

                
                <h1 class="mb-0">
                  <?= $row['Location']; ?>
                </h1>
                <h1 class="mb-2">
                  <?= substr($row['Name'], 0, 80) . '...'; ?>
                </h1>
                <?php
                      $createdAt = $row['CreatedAt'];
                      
                      if ($createdAt === null || ($currentTime - strtotime($createdAt)) > 10) {
                          //echo "<p class=\"badge fs-12 font-weight-bold mb-3 text-danger\">Offline";
                          if ($createdAt !== null) {
                            echo "<h3 class=\"text-primarym\"><span class=\"text-danger\">Offline</span> - Since {$createdAt}</h3>";

                          }
                          echo "</p>";
                      } else {
                          echo "<h3 class=\"text-primarym\"><span class=\"text-success\">Online</span></h3>";
                      }
                                 
                               
                              ?>
                <div class="fs-30">
                  <!-- <span class="mr-2">Date </span> -->
                  <div class="badge <?= ($row['BridgeStatus'] == 'NOT SAFE TO USE') ? 'badge-danger' : 'badge-primary' ?>  font-weight-bold mb-3">
                  ROAD  <?= $row['RoadStatus'] ?> BRIDGE <?= $row['BridgeStatus']?> 
                </div>

                <?php

                    if ($row['RoadStatus'] == 'CLOSED') {
                      echo '<h3 class="font-weight-400 mb-1">DUE TO :</31>';
                      
                    // if ($row['VibrationLevels'] != 90000 ) {
                    //     echo '<h3 class="font-weight-400 mb-1">High Vibrations</h3>';
                    // } 
                    if ($row['CrackDepth'] > 13.0) {
                        echo '<h3>There exist a big crack which needs maintainance </h3>';
                    } if ($row['Water_level'] > 50) {
                      echo '<h3 class="font-weight-400 mb-1">High Water Level</h3>';
                    }
                    if ($row['Tilt'] == "HIGH TILT") {
                    echo '<h3 class="font-weight-400 mb-1">The bridge is tilted</h3>';
                    }

                    } 

                ?>
                
                </div>
                <h4><a  href="pages/single-bridge.php?id=<?= $row['BridgeID']; ?>">See more ...</a></h4>
              </div>
             

          <?php }
          ?>
          
        </div>
      </div>
      <div class="col-xl-4 stretch-card grid-margin">
        <div class="card bg- text-black">
          <div class="card-body">
            <h2>Nearest Bridges</h2>

            <?php
            while ($row = $nearestBridges->fetch_assoc()) { ?>
              <a href="pages/single-bridge.php?id=<?= $row['BridgeID']; ?>" style="color: black;">
                <div class="d-flex border-bottom-blue pt-3 align-items-center justify-content-between">
                
                  <div class="pr-3">
                    <h5>
                      <?= substr($row['Name'], 0, 30) . '...'; ?>
                    </h5>
                    <div class="fs-12">
                      <span class="mr-2">Road Status </span>
                      <div class="badge <?= ($row['RoadStatus'] == 'CLOSED') ? 'badge-danger' : 'badge-primary' ?> fs-12 font-weight-bold mb-3">
                      <?= $row['RoadStatus'] ?>
                   </div>
                              
                      
                           <?php
                                 $createdAt = $row['CreatedAt'];
                                 
                                 if ($createdAt === null || ($currentTime - strtotime($createdAt)) > 10) {
                                     echo "<p class=\"badge fs-12 font-weight-bold mb-3 text-danger\">Offline";
                                     if ($createdAt !== null) {
                                         echo "<span class=\"text-primary\"> - Since {$createdAt}</span>";
                                     }
                                     echo "</p>";
                                 } else {
                                     echo '<p class="badge fs-12 font-weight-bold mb-3 text-success">Online</p>';
                                 }
                                 
                               
                              ?>
                    </div>
                  </div>
                  <div class="rotate-img">
                    <img src="upload/<?= $row['AttachmentName']; ?>" alt="thumb" class="img-fluid img-lg" />
                  </div>
                </div>
              </a>
            <?php } ?>
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
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="assets/vendors/aos/dist/aos.js/aos.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="assets/js/demo.js"></script>
<script src="assets/js/jquery.easeScroll.js"></script>
<!-- End custom js for this page-->
</body>

</html>