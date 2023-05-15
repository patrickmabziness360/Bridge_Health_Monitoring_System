<?php


$servername = "localhost";

// REPLACE with your Database name
$dbname = "bridge";
// REPLACE with Database user
$username = "root";
// REPLACE with Database user password
$password = "";

// Keep this API Key value to be compatible with the ESP8266 code provided in the project page. 
// If you change this value, the ESP8266 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $sensor = $location = $value1 = $value2 = $distance = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $bridgeID = test_input($_POST["BridgeID"]);
        $Cordinates = test_input($_POST["Cordinates"]);
        $Vibrations = test_input($_POST["Vibrations"]);
        $Strain = test_input($_POST["Strain"]);
        $Water_Level = test_input($_POST["Water_Level"]);
        $Accelerometer = test_input($_POST["Accelerometer"]);
        $Crack = test_input($_POST["Crack"]);
        $RoadStatus = test_input($_POST["RoadStatus"]);
        $BridgeStatus = test_input($_POST["BridgeStatus"]);
        $CreatedAt = test_input($_POST["CreatedAt"]);
        
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO tblBridgeSensorData (BridgeID,Cordinates,Vibrations,Strain,Water_Level,Accelerometer,Crack,RoadStatus,BridgeStatus,CreatedAt)
        VALUES ('" . $bridgeID . "', '" . $Cordinates . "', '" . $Vibrations . "','" . $Strain . "', '" . $Water_Level . "', '" . $Accelerometer . "',
        '" . $Crack . "', '" . $RoadStatus . "', '" . $BridgeStatus . "','" . $CreatedAt . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
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