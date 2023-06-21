<?php

include '../common/db_connect.php';


$api_key= $VibrationLevels = $StrainOnBridge = $Water_Level = $Accelerometer = $crackDepth = $RoadStatus = $BridgeStatus = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    
        // Select the bridge ID based on the API key
        $select_bridge = $conn->query("SELECT bridgeID,api_key_value FROM bridge.tblbridge WHERE api_key_value = '".$api_key."'");

        if ($select_bridge->num_rows > 0) {

            $row = $select_bridge->fetch_assoc();
            $bridgeID = $row["bridgeID"];
            $api_key_value = $row["api_key_value"];


            if($api_key == $api_key_value) {

                // $bridgeID = test_input($_POST["BridgeID"]);
                 $VibrationLevels = test_input($_POST["vibration"]);
                 $StrainOnBridge = test_input($_POST["strain"]);
                 $Water_Level = test_input($_POST["waterlevel"]);
                 $Accelerometer = test_input($_POST["accelometer"]);
                 $crackDepth = test_input($_POST["crackDepth"]);
                 $RoadStatus = test_input($_POST["roadStatus"]);
                 $BridgeStatus = test_input($_POST["bridgeStatus"]);
                 $Tilt =test_input($_POST["tilt"]);
                 

            // Insert the data into the tblbridgesensordata
            $insert_data = $conn->query("INSERT INTO bridge.tblbridgesensordata (bridgeID,VibrationLevels, StrainOnBridge, Water_Level, 
                                        Tilt,CrackDepth, RoadStatus,BridgeStatus)
            VALUES ('" . $bridgeID . "', '" . $VibrationLevels . "', '" . $StrainOnBridge . "','" . $Water_Level . "', 
                    '" . $Tilt . "', '" . $crackDepth . "', '" . $RoadStatus . "', '" . $BridgeStatus . "')");

            if ($insert_data === TRUE) {
                echo "New record created successfully";
            } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            }
      
    }
    else {
        echo "Wrong API Key provided.";
    }

    }
}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}