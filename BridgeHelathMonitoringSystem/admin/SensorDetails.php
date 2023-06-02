<?php
session_start();
$relative_path = '../';
include ('../common/db_connect.php');




if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $bridge_information = $conn->query("SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName FROM bridge.tblBridge LTP LEFT JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID WHERE LTP.BridgeID =$id;");
    $resultw = $bridge_information -> fetch_assoc();

  }
$sql = "SELECT ID, VibrationLevels, StrainOnBridge, Water_Level, Accelerometer,CrackDepth,CreatedAt FROM bridge.tblBridgeSensorData WHERE BridgeID =$id;";

$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'CreatedAt');
$value1 = json_encode(array_reverse(array_column($sensor_data, 'Water_Level')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'VibrationLevels')), JSON_NUMERIC_CHECK);
$value3 = json_encode(array_reverse(array_column($sensor_data, 'StrainOnBridge')), JSON_NUMERIC_CHECK);
$CreatedAt = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);


$result->free();
$conn->close();
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
    
    
    <!-- ========== table components start ========== -->
    <section class="table-components">
      <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="title mb-30">
                <h2> <?=$resultw['Name'];?> Details</h2>
                <h4>Location : <?=$resultw['Location'];?></h4>
                
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
                    
                    <div id="chart-waterleve" class="container"></div>
                    <div id="chart-humidity" class="container"></div>
                    <div id="chart-pressure" class="container"></div>

                  
                  </div>
                  
                  
                </div>
              </div>
            
            </div>
          </div>
        </div>
      </div>
    
    </section>
  
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

var value1 = <?php echo $value1; ?>;
var value2 = <?php echo $value2; ?>;
var value3 = <?php echo $value3; ?>;
var CreatedAt = <?php echo $CreatedAt; ?>;

var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart-waterleve' },
  title: { text: 'WATER LEVELS' },
  series: [{
    showInLegend: false,
    data: value1
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
  },
  xAxis: { 
    type: 'datetime',
    categories: CreatedAt
  },
  yAxis: {
    title: { text: 'Water level ' }
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart-humidity' },
  title: { text: 'Vibrations' },
  series: [{
    showInLegend: false,
    data: value2
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: CreatedAt
  },
  yAxis: {
    title: { text: 'Vibrations' }
  },
  credits: { enabled: false }
});


var chartP = new Highcharts.Chart({
  chart:{ renderTo:'chart-pressure' },
  title: { text: 'Strain' },
  series: [{
    showInLegend: false,
    data: value3
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#18009c' }
  },
  xAxis: {
    type: 'datetime',
    categories: CreatedAt
  },
  yAxis: {
    title: { text: 'Pressure (hPa)' }
  },
  credits: { enabled: false }
});

</script>
</body>


</html>