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
      
      <?php

      // $closest_bridge = $conn->query("SELECT LTP.*, LTPF.Caption,LTPF.AttachmentName,d.RoadStatus,d.BridgeStatus FROM bridge.tblBridge LTP INNER JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID  INNER JOIN bridge.tblBridgeSensorData d
      // ON LTP.BridgeID=D.BridgeID ORDER BY d.CreatedAt DESC LIMIT 1;");

    $closest_bridge = $conn->query("SELECT LTP.Name,LTP.Location,LTP.BridgeID,LTPF.Caption,LTPF.AttachmentName,d.Water_level,d.CrackDepth,d.VibrationLevels,d.RoadStatus,d.BridgeStatus,d.StrainOnBridge,d.CreatedAt,d.Tilt
                                    FROM bridge.tblBridge LTP INNER JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID 
                                    INNER JOIN bridge.tblBridgeSensorData d ON LTP.BridgeID = d.BridgeID WHERE LTP.BridgeID =D.BridgeID ORDER BY d.CreatedAt DESC LIMIT 1;");


      $nearestBridgesQuery = "SELECT LTP.Name,LTP.BridgeID,LTP.Location, LTPF.Caption, LTPF.AttachmentName, d.RoadStatus, d.BridgeStatus,d.CreatedAt
                              FROM bridge.tblBridge LTP INNER JOIN bridge.tblBridgeImages LTPF ON LTP.BridgeID = LTPF.BridgeID
                              LEFT JOIN (
                                  SELECT BridgeID, RoadStatus, BridgeStatus, MAX(CreatedAt) AS MaxCreatedAt
                                  FROM bridge.tblBridgeSensorData
                                  GROUP BY BridgeID
                              ) AS maxSensorData ON LTP.BridgeID = maxSensorData.BridgeID
                              LEFT JOIN bridge.tblBridgeSensorData d ON maxSensorData.BridgeID = d.BridgeID AND maxSensorData.MaxCreatedAt = d.CreatedAt
                              ORDER BY d.CreatedAt DESC
                              LIMIT 3;";
      $nearestBridges = $conn->query($nearestBridgesQuery);

      
      ?>