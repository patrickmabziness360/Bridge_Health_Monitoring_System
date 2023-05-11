<!DOCTYPE html>
<html lang="zxx">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>BHMS</title>
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="<?= $relative_path; ?>assets/vendors/mdi/css/materialdesignicons.min.css" />
  <link rel="stylesheet" href="<?= $relative_path; ?>assets/vendors/aos/dist/aos.css/aos.css" />

  <!-- End plugin css for this page -->
  <link rel="shortcut icon" href="<?= $relative_path; ?>assets/images/Skylabs.JPG" />

  <!-- inject:css -->
  <link rel="stylesheet" href="<?= $relative_path; ?>assets/css/style.css">
  <!-- endinject -->
</head>

<body>
  <div class="container-scroller">
    <div class="main-panel">
      <!-- partial:partials/_navbar.html -->
      <!-- <header id="header">
        <div class="container">
          <nav class="navbar navbar-expand-lg navbar-light">
            <div class="navbar-top">
              <div class="d-flex justify-content-between align-items-center">
                <ul class="navbar-top-left-menu">
                  <li class="nav-item">
                    <a class="nav-link">Group 14</a>
                  </li>

                </ul>
                <ul class="navbar-top-right-menu">
                  <li class="nav-item">
                    <a href="<?= $relative_path; ?>#" class="nav-link"><i class="mdi mdi-magnify"></i></a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= $relative_path; ?>admin/login.php"  class="nav-link">Login</a>
                  </li><!-- 
                    <li class="nav-item">
                      <a href="<?= $relative_path; ?>#" class="nav-link">Subscribe</a>
                    </li> -->
                <!-- </ul>
              </div>
            </div>
            <div class="navbar-bottom">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <a class="navbar-brand text2" href="<?= $relative_path; ?>#">
                    Bridge Health Monitoring System</a>
                </div>
                <div> -->
                  <!-- <button class="navbar-toggler" type="button" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="navbar-collapse justify-content-center collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-lg-flex justify-content-between align-items-center">
                      <li>
                        <button class="navbar-close">
                          <i class="mdi mdi-close"></i>
                        </button>
                      </li>
                    </ul>
                  </div>
                </div> -->

              <!-- </div>
            </div>
          </nav>
        </div>
      </header> -->
      <?php


      $closest_bridge = $conn->query("SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName FROM bridge.tblBridge LTP INNER JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID  ORDER BY CreatedAt DESC LIMIT 1;");

      $nearestBridgesQuery = "SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName FROM bridge.tblBridge LTP INNER JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID ORDER BY LTP.CreatedAt DESC LIMIT 4;";
      $nearestBridges = $conn->query($nearestBridgesQuery);

      $recent_posts = $conn->query($nearestBridgesQuery);

      ?>