<?php
session_start();
$relative_path = '../';
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
    $bridge_information = $conn->query("SELECT CrackDepth,StrainOnBridge,Tilt,BridgeStatus,CreatedAt,RoadStatus,Water_level FROM bridge.tblBridgeSensorData WHERE BridgeID =$id ORDER BY CreatedAt DESC LIMIT 1;");
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
  $sql = "SELECT ID, VibrationLevels, StrainOnBridge, Water_Level, CrackDepth, TIME(CreatedAt) AS CreatedTime FROM bridge.tblBridgeSensorData WHERE BridgeID = $id ORDER BY CreatedAt DESC LIMIT 13;";
  $result = $conn->query($sql);
  
  $sensor_data = array();
  while ($data = $result->fetch_assoc()) {
      $sensor_data[] = $data;
  }
  
  // Check if sensor data is null
  if (!empty($sensor_data)) {
      $readings_time = array_column($sensor_data, 'CreatedTime');
      $waterleveldata = json_encode(array_reverse(array_column($sensor_data, 'Water_Level')), JSON_NUMERIC_CHECK);
      $CreatedAt = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);
  
      // Plot the graph or perform other actions with the sensor data
      // ...
  } else {
      // Sensor data is null, handle accordingly (e.g., display a message, skip graph plotting)
      // ...
  }
  
$result->free();
//$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- <meta http-equiv="refresh" content="3"> -->
  <!-- endinject -->
</head>
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
                
                
                          <!-- ========== table components start ========== -->
                          
                      <section class="table-components">
                        <div class="container-fluid">
                          <!-- ========== title-wrapper start ========== -->
                          <div class="title-wrapper pt-30 ">
                            <div class="row align-items-center">
                              <div class="col-md-6 align-items-center">
                                <div class="title mb-30">
                                <h6>Bridge Name : <span class="<?= $info['BridgeStatus'] === 'SAFE TO USE' ? 'text-success' : 'text-danger' ?>"> <?=$resultw['Name'];?></span></h6>
                                <h6>Location : <span class="<?= $info['BridgeStatus'] === 'SAFE TO USE' ? 'text-success' : 'text-danger' ?>"> <?=$resultw['Location'];?></span></h6>
                                  

                                <?php if (!empty($info['BridgeStatus'])) : ?>
                                  <h6> Status : <span class="<?= $info['BridgeStatus'] === 'SAFE TO USE' ? 'text-success' : 'text-danger' ?>"> <?=$info['BridgeStatus'];?></span></h6>

                                  <h6 class="text-enter">Date  :  <?=date('d F Y', strtotime($date));?></h6>

                                  
                                  <?php

                                  $createdAt = $info['CreatedAt'];

                                    if ($createdAt === null || ($currentTime - strtotime($createdAt)) > 10) {
                                      echo "<h6 > Sensor  : <span class=\"text-danger\">  Offline - Since {$createdAt}</span> </h6>";
                                    } else {
                                      echo '<h6> Sensor  :<span class="text-success"> Online</span> <h6>';
                                    }
                                 ?>
 
                                  <?php

                                   if ($info['RoadStatus'] == 'CLOSED') {
                                    echo '<h5 class="font-weight-400 mb-1 text-danger">DUE TO :</h5>';
                                      
                                    if ($info['StrainOnBridge'] > 70 ) {
                                        echo '<h5 class="font-weight-400 text-danger mb-1">Over Weight</h5>';
                                    } 
                                    
                                    if ($info['CrackDepth'] > 13.0) {
                                        echo '<h5 class="font-weight-400 text-danger">There exists a big crack which needs maintenance</h5>';
                                    }
                                    
                                    if ($info['Water_level'] > 50) {
                                        echo '<h5 class="font-weight-400 mb-1 text-danger">High Water Level</h5>';
                                    }
                                    
                                    if ($info['Tilt'] == "HIGH TILT") {
                                        echo '<h5 class="font-weight-400 mb-1 text-danger">The bridge is tilted</h3>';
                                    }
                                    

                                    } 

                                    ?>
                                
                              </div>
                            </div>
                          </div>
                          
                    <div class="row">
                    <div class="col-lg-4">
                          <div class="card-style mb-30 <?= $info['Tilt'] === 'HIGH TILT' ? 'bg-danger' : '' ?>">
                              <div class="card-body" style="height: 250px;">
                                  <h5 class="card-title ">Orientation Details</h5>
                                  <p class="card-text font-weight-400">
                                  <span class="<?= $info['Tilt'] === 'HIGH TILT' ? 'text-white' : 'text-black' ?>">The Bridge is:</span>
                                         <span class="<?= $info['Tilt'] === 'HIGH TILT' ? 'text-danger' : 'text-success' ?>">
                                          <?= $info['Tilt'] === 'HIGH TILT' ? '<span class="text-white">' . "Tilted" . '</span>' : "Not Tilted" ?>
                                      </span>
                                  </p>
                                  <!-- <p class="card-text">Initial Orientation</p>
                                  <p class="card-text">X-30 rad/s, y-67 rad/s, and z-35 rad/sec</p>
                                  <p class="card-text">Current Orientation</p>
                                  <p class="card-text">X-30 rad/s, y-67 rad/s, and z-35 rad/sec</p> -->
                                  <!-- <p class="card-text">3D visualization</p> -->
                              </div>
                          </div>
                  </div>



                        <div class="col-lg-4">
                            <div class="card-style mb-30 <?= $info['CrackDepth'] > 13 ? 'bg-danger' : '' ?>">
                                <div class="card-body" style="height: 250px;">
                                    <h5 class="card-title">Crack Details</h5>
                                    <!--<p class="card-text">Number of cracks: 1</p>
                                    <p class="card-text">Need attention cracks: 1</p>
                                    <p class="card-text">Normal cracks: none</p>-->
                                    <p class="card-text">
                                    <span class="<?= $info['CrackDepth'] > 13 ? 'text-white' : 'text-black' ?>"> Biggest Crack:</span>
                                      <span class="<?= $info['CrackDepth'] > 13 ? 'text-white' : 'text-success' ?>">
                                          <?= $info['CrackDepth'] > 13 ? $info['CrackDepth'] : '0' ?> M deep
                                      </span>
                                  </p>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                          <div class="card-style mb-30 <?= $info['StrainOnBridge'] > 70 ? 'bg-danger' : '' ?>">
                              <div class="card-body" style="height: 250px;">
                                  <h5 class="card-title">Strain On Bridge Details</h5>
                                  <p class="card-text">
                                  <span class="<?= $info['CrackDepth'] > 70 ? 'text-white' : 'text-black' ?>"> Max weight:</span>  <span class="<?= $info['StrainOnBridge'] > 70 ? 'text-white' : 'text-success' ?> title">50 tons</span>
                                  </p>
                                  <p class="card-text">
                                  <span class="<?= $info['CrackDepth'] > 70 ? 'text-white' : 'text-black' ?>"> Current weight:</span>  <span class="<?= $info['StrainOnBridge'] > 70 ? 'text-white' : ($info['StrainOnBridge'] < 30 ? 'text-success' : ($info['StrainOnBridge'] < 70 ? 'text-success' : 'text-danger')) ?>">
                                      <?= ($info['StrainOnBridge'] < 30) ? 0 : $info['StrainOnBridge'] ?> tons
                                  </span>
                                  </p>
                                  <p class="card-text">
                                  <span class="<?= $info['CrackDepth'] > 70 ? 'text-white' : 'text-black' ?>"> status:</span> <span class="<?= $info['StrainOnBridge'] > 70 ? 'text-white' : ($info['StrainOnBridge'] > 30 ? 'text-danger' : 'text-success') ?>">
                                          <?= $info['StrainOnBridge'] > 70 ? 'Temporarily closed' : 'Safe to use' ?>
                                      </span>
                                  </p>
                              </div>
                          </div>
                      </div>



            </div>
            <!-- ========== Additional Cards end ========== -->


            <div class="tabs-wrapper">
                              <div class="row">
                                <div class="col-lg-12">
                                  <div class="tab-style-2 card-style mb-30">
                                    <div class="tab-content" id="nav-tabContent2">
                                      <div class="tab-pane fade show active" id="news">
                                        


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
                                  

                              <?php else : ?>
                                  <h2>No data available</h2>
                              <?php endif; ?>





                          
                        </div>
                      </section>

                                </div>
                              </div>
                            </div>
                            
  
  </main>
  
  
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
    threshold: 500 // Specify the threshold value for threadshort
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
  if (point.y > 500) {
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