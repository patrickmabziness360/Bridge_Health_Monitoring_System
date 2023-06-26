<?php
  session_start();
  header("refresh: 5"); // Refresh the page after 5 seconds
  
  include 'head.php';
  
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


   include ('../common/db_connect.php');

   header("refresh: 5"); // Refresh the page after 5 seconds
   
   
   
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   } 
   
   if (isset($_GET['id']) && !empty($_GET['id'])) {
       $id = $_GET['id'];
       $bridge_information2 = $conn->query("SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName FROM bridge.tblBridge LTP LEFT JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID WHERE LTP.BridgeID =$id;");
       $resultw = $bridge_information2 -> fetch_assoc();
   
     }
     if (isset($_GET['id']) && !empty($_GET['id'])) {
       $id = $_GET['id'];
       $bridge_information = $conn->query("SELECT CrackDepth,StrainOnBridge,Tilt,BridgeStatus,Water_level,CreatedAt,RoadStatus FROM bridge.tblBridgeSensorData WHERE BridgeID =$id ORDER BY CreatedAt DESC LIMIT 1;");
       $info = $bridge_information -> fetch_assoc();
   
     }
   
   
     $date = $conn->query("SELECT now() date");
     $date = $date->fetch_assoc()['date'];
     $currentTime = strtotime($date);
   
     // if (isset($_GET['id']) && !empty($_GET['id'])) {
     //   $id = $_GET['id'];
     //   $initialOriatationQuery = $conn->query("SELECT ZAccelerometer, YAccelerometer, XAccelerometer FROM bridge.tblBridgeSensorData WHERE BridgeID =$id ORDER BY CreatedAt DESC LIMIT 1;");
     //   $initialOrriantaion = $initialOriatationQuery -> fetch_assoc();
   
     // }
   $sql = "SELECT ID, VibrationLevels, StrainOnBridge, Water_Level,CrackDepth,TIME(CreatedAt) AS CreatedTime FROM bridge.tblBridgeSensorData WHERE BridgeID =$id ORDER BY CreatedAt DESC LIMIT 14;";
   
   $result = $conn->query($sql);
   
   while ($data = $result->fetch_assoc()){
       $sensor_data[] = $data;
   }
   
   $readings_time = array_column($sensor_data, 'CreatedTime');
   $waterleveldata = json_encode(array_reverse(array_column($sensor_data, 'Water_Level')), JSON_NUMERIC_CHECK);
   $CreatedAt = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);
   
   
   $result->free();




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
                     

                    <main class="main-wrapper">
                <!-- ========== header start ========== -->
                
                
                          <!-- ========== table components start ========== -->
                          
                      <section class="table-components">
                        <div class="container-fluid">
                          <!-- ========== title-wrapper start ========== -->
                          <div class="title-wrapper pt-30 ">
                            <div class="row align-items-center">
                              <div class="col-md-6 align-items-center">
                                <div class="title mb-30">

                                  <h6>Bridge Name : <span class="<?= $info['BridgeStatus'] === 'SAFE TO USE' ? 'text-success' : 'text-danger' ?>"> <?=$resultw['Name'];?> Details</span></h6>
                                  <h6>Location : <span class="<?= $info['BridgeStatus'] === 'SAFE TO USE' ? 'text-success' : 'text-danger' ?>"> <?=$resultw['Location'];?> Details</span></h6>
                                  <h6> Status : <span class="<?= $info['BridgeStatus'] === 'SAFE TO USE' ? 'text-success' : 'text-danger' ?>"> <?=$info['BridgeStatus'];?></span></h6>

                                  <h6 class="text-enter">Date  :  <?=date('d F Y', strtotime($date));?></h6>
                                  <!-- <h6 class="text-enter">Sensor  : </h6> -->

                                  <?php

                                  $createdAt = $info['CreatedAt'];

                                    if ($createdAt === null || ($currentTime - strtotime($createdAt)) > 10) {
                                      echo "<h6 class=\"text-da\"> Sensor  : <span class=\"text-danger\">  Offline - Since {$createdAt}</span> </h6>";
                                    } else {
                                      echo '<h6 class="text-success"> Sensor  : Online</h6>';
                                    }
                                 ?>
 
                                  <?php

                                   if ($info['RoadStatus'] == 'CLOSED') {
                                      echo '<h3 class="font-weight-400 mb-1">DUE TO :</31>';
                                      
                                    // if ($row['VibrationLevels'] != 90000 ) {
                                    //     echo '<h3 class="font-weight-400 mb-1">High Vibrations</h3>';
                                    // } 
                                    if ($info['CrackDepth'] > 13.0) {
                                        echo '<h3>There exist a big crack which needs maintainance </h3>';
                                    } if ($info['Water_level'] > 50) {
                                      echo '<h3 class="font-weight-400 mb-1">High Water Level</h3>';
                                    }
                                    if ($info['Tilt'] == "HIGH TILT") {
                                    echo '<h3 class="font-weight-400 mb-1">The bridge is tilted</h3>';
                                    }

                                    } 

                                    ?>
                                
                              </div>
                            </div>
                          </div>
                          <!-- ========== title-wrapper end ========== -->
            <!-- ========== Additional Cards start ========== -->
            <div class="row">
              <div class="col-lg-4">
                <div class="card-style mb-30">
                  <div class="card-body" style="height: 250px;">
                    <h5 class="card-title">Orientation Details</h5>
                    <p class="card-text">The Bridge has: <span class="<?= $info['Tilt'] === 'HIGH TILT' ? 'text-danger' : 'text-success' ?>"><?= $info['Tilt'] ?></span></p>
                    <!-- <p class="card-text">Initial Orientation</p>
                    <p class="card-text">X-30 rad/s, y-67 rad/s, and z-35 rad/sec</p>
                    <p class="card-text">Current Orientation</p>
                    <p class="card-text">X-30 rad/s, y-67 rad/s, and z-35 rad/sec</p> -->
                    <p class="card-text">3D visualization</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="card-style mb-30">
                  <div class="card-body" style="height: 250px;">
                    <h5 class="card-title">Crack Details</h5>
                    <p class="card-text">Number of cracks: 1</p>
                    <p class="card-text">Number of harmful cracks: 1</p>
                    <p class="card-text">Crack 1: <span class="<?= $info['CrackDepth'] < 30 ? 'text-success' : 'text-danger' ?>"><?= $info['CrackDepth'] ?> M deep</span></p>
              
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="card-style mb-30">
                  <div class="card-body" style="height: 250px;">
                    <h5 class="card-title">Strain On Bridge Details</h5>
                    <p class="card-text">Max weight: <span class="text-success title">30 tons</span></p>
                    <p class="card-text">Current weight: <span class="<?= $info['StrainOnBridge'] < 30 ? 'text-success' : 'text-danger' ?>"><?= $info['StrainOnBridge'] ?> tons</span></p>
                    <p class="card-text">Status: <span class="<?= $info['StrainOnBridge'] > 30 ? 'text-danger' : 'text-success' ?>"><?= $info['StrainOnBridge'] > 30 ? 'Temporarily closed' : 'Safe to use' ?></span></p>

                  </div>
                </div>
              </div>
            </div>
            <!-- ========== Additional Cards end ========== -->

                          
                        </div>
                      </section>

                        <div class="tabs-wrapper">
                              <div class="row">
                                <div class="col-lg-12">
                                  <div class="tab-style-2 card-style mb-30">
                                    <div class="tab-content" id="nav-tabContent2">
                                      <div class="tab-pane fade show active" id="news">
                                        <span class="mr-3 text-primary">
                                          <?php
                                          echo date('d F Y', strtotime($date));
                                          ?>
                                        </span>


                                        <div id="chart-waterleve" class="container"></div>
                                       
                                      </div>
                                    </div>
                                  </div>
                                  <div class="rotate-img">
                                    Picture
                                <img
                                  src="<?=$relative_path;?>upload/<?=$resultw['AttachmentName'];?>"
                                  alt="banner"
                                  class="img-fluid mt-4 mb-4"
                                />
                        </div>
                                </div>
                              </div>
                            </div>
                            
  
  </main>






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
    
    <script src="https://code.highcharts.com/highcharts.js"></script>
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

var waterleveldata = <?php echo $waterleveldata; ?>;
var CreatedAt = <?php echo $CreatedAt; ?>;

var chartT = new Highcharts.Chart({
  chart: { renderTo: 'chart-waterleve' },
  title: { text: 'WATER LEVELS' },
  series: [{
    showInLegend: false,
    data: waterleveldata,
    color: '#059e8a', // Set the default color for the series
    threshold: 120 // Specify the threshold value for threadshort
  }],
  plotOptions: {
    line: {
      animation: false,
      dataLabels: { enabled: true }
    }
  },
  xAxis: {
    type: 'datetime',
    categories: CreatedAt
  },
  yAxis: {
    title: { text: 'Water level' }
  },
  credits: { enabled: false }
});

// Change the color of points exceeding the threshold to red
chartT.series[0].points.forEach(function(point) {
  if (point.y > 120) {
    point.update({
      color: 'red',
      marker: {
        fillColor: 'red'
      }
    }, false);
  }
});

// Redraw the chart to reflect the color changes
chartT.redraw();



</script>



  </body>

</html>



