
//lIbraries
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>
#include <ESP8266WiFi.h>
#include <Adafruit_MPU6050.h>
#include <Adafruit_Sensor.h>
#include "HX711.h"

#ifdef ESP32
  #include <WiFi.h>
  #include <HTTPClient.h>
#else
  #include <ESP8266WiFi.h>
  #include <ESP8266HTTPClient.h>
  #include <WiFiClient.h>
#endif

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
              //Varible section
// Network credentials
const char* ssid     = "SKYLABS360(MABZINESS)";
const char* password = "Tazie25800p!";

// URL path for sending posted data
const char* serverName = "http://192.168.0.6/OurProject/BridgeHelathMonitoringSystem/admin/post-sensor-values.php";

//api key for each microcontroller 
String apiKeyValue = "tPmAT5Ab3j7F9";

LiquidCrystal_I2C lcd(0x27, 16, 2); // Set the LCD address to 0x27 for a 16 chars and 2 line display
Servo servo; // servo object
//int VibrationPin = D4; //vibration sensor pin
int ServoPin = D3; //servo motor pin 
int Passive_buzzer = D8; // BUZZER 
const int trigPin = 12;
const int echoPin = 14;

//define sound velocity in cm/uS
#define SOUND_VELOCITY 0.034
long duration;
float distanceCm;

String roadStatus;
String bridgeStatus;

//accelerometer values 
Adafruit_MPU6050 mpu;
// Scaling factor for roll, pitch, and yaw values
const float ANGLE_SCALE = 10.0;

// Variables to store initial yaw, pitch, and roll values
float initialYaw = 0.0;
float initialPitch = 0.0;
float initialRoll = 0.0;

bool isFirstReading = true;
 String tiltLevel;

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// HX711 circuit wiring
const int LOADCELL_DOUT_PIN = D0;
const int LOADCELL_SCK_PIN = D7;

 HX711 scale;

WiFiClient client;

void setup() {
Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  
  //configuring the lcd 
  lcd.init(); // Initialize the LCD
  lcd.backlight();// Turn on the backlight
  lcd.clear();// Clear the display
  Wire.setClock(10000);
  
  //servo configurations 
  servo.attach(ServoPin); //D4
  servo.write(0);

  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input

  pinMode (Passive_buzzer,OUTPUT) ;// this is the pin mode for the buzzer

  while (!Serial)
    delay(10); // will pause Zero, Leonardo, etc until serial console opens

  // Try to initialize!
  if (!mpu.begin()) {
    Serial.println("Failed to find MPU6050 chip");
    while (1) {
      delay(10);
    }
  }
  Serial.println("MPU6050 Found!");

  mpu.setAccelerometerRange(MPU6050_RANGE_8_G);
  mpu.setGyroRange(MPU6050_RANGE_500_DEG);
  mpu.setFilterBandwidth(MPU6050_BAND_5_HZ);

//values for loadcell
  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);       
  scale.set_scale(-379.319);
  scale.tare();               // reset the scale to 0

  // Serial.println("");
  // delay(100);

}

void loop() {
                // The logic section 
                //LCD clearing 
//=========================================================================================
   lcd.clear(); //clearing the LCD
   lcd.print("BRIDGE MONITORING"); //print message on the screen 
   lcd.setCursor(0, 1); //Set the cursor where you want the lcd to be printing 
   //delay(200); //delay 
//=========================================================================================   

             // ultrasonic sensor section 
//-------------------------------------------------------------------------------------------
  digitalWrite(trigPin, LOW);// Clears the trigPin
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);// Sets the trigPin on HIGH state for 10 micro seconds
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);// Reads the echoPin, returns the sound wave travel time in microseconds
  distanceCm = duration * SOUND_VELOCITY/2; // Calculate the distance 

  Serial.print("Distance (cm): "); // Prints the distance on the Serial Monitor
  Serial.println(distanceCm);
//-----------------------------------------------------------------------------------------------------

                 //water level section 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  int waterlevel=analogRead(A0)-19; // Water Level Sensor output pin connected A0
 if (waterlevel < 0) {
    waterlevel = 0; // Set weight to 0 if it is negative
  }
  Serial.print("Water level: ");// See the Value In Serial Monitor
  Serial.println(waterlevel);  
  //Serial.println(waterlevel); 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

                //Accelerometer section
//______________________________________________________________________________________________________________  
  sensors_event_t a, g, temp;/* Get new sensor events with the readings */
  mpu.getEvent(&a, &g, &temp);

  float roll = atan2(a.acceleration.y, a.acceleration.z) * 180.0 / PI; // Calculate roll and pitch angles using accelerometer
  float pitch = atan(-a.acceleration.x / sqrt(a.acceleration.y * a.acceleration.y + a.acceleration.z * a.acceleration.z)) * 180.0 / PI;

 float yaw = g.gyro.z; // Calculate yaw angle using gyroscope

 roll *= ANGLE_SCALE;// Scale the roll, pitch, and yaw values
 pitch *= ANGLE_SCALE;
 yaw *= ANGLE_SCALE;

// Store the initial sensor readings
if (isFirstReading) {
    initialYaw = yaw;
    initialPitch = pitch;
    initialRoll = roll;
    isFirstReading = false;
  }

  float yawDiff = yaw - initialYaw;// Calculate the differences
  float pitchDiff = pitch - initialPitch;
  float rollDiff = roll - initialRoll;

  Serial.print("dif Yaw- y-axis: ");
  Serial.print(abs(yawDiff));
  Serial.print(" dif Pitch- x-axis: ");
  Serial.print(abs(pitchDiff));
  Serial.print(" dif Roll- z-axis: ");
  Serial.println(abs(rollDiff));
  // delay(7000);
//__________________________________________________________________________________________________

              //Scale section  
//===================================================================================================
   // Serial.print("one reading:\t");
   // float weightReading = scale.get_units();
   // Serial.print(weightReading, 1);
    //Serial.print("Average:\t");
    //float averageReading = scale.get_units(10);
    //Serial.println(averageReading, 5);
    //float Weight = averageReading; 
    //scale.power_down();             // put the ADC in sleep mode
    //delay(500);
    //scale.power_up();
    //Serial.print("one reading:\t");
    //float weightReading = scale.get_units();
   // Serial.print(weightReading, 1);
    //Serial.print("\t| average:\t");
    float averageReading = scale.get_units(10);
    //Serial.println(averageReading, 5);
    float Weight = averageReading;
    scale.power_down();             // put the ADC in sleep mode
    //delay(500);
    scale.power_up();

  

//===================================================================================================

                //logic checking section
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  if(distanceCm > 13.0){
    BridgeNotSafe();

 }else if(waterlevel > 500){
    BridgeNotSafe();
 }
 else if(Weight > 170.0){
    OverWeight();

 }
// else if((abs(rollDiff) >= 5 && abs(rollDiff) < 27) || (abs(pitchDiff) >= 5 && abs(pitchDiff) < 11) || (abs(yawDiff) >= 2 && abs(yawDiff) < 10)) {
//     tiltLevel = "LITTLE TILT";
//     BridgeSafe();
//   } else if ((abs(rollDiff) >= 27 && abs(rollDiff) < 56) || (abs(pitchDiff) >= 11 && abs(pitchDiff) < 20) || (abs(yawDiff) >= 10 && abs(yawDiff) < 20)) {
//     tiltLevel = "MEDIUM TILT";
//     BridgeSafe();
//   } 
  else if (abs(rollDiff) >5 || abs(pitchDiff) > 7 || abs(yawDiff) > 7) {
    tiltLevel = "HIGH TILT";
    BridgeNotSafe();
  }else{
    BridgeSafe();
  }


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 
                  //Sending data to the dashboard 
//-----------------------------------------------------------------------------------------------------------------------------------------------------
  if(WiFi.status()== WL_CONNECTED){ //Check WiFi connection status
    WiFiClient client;
    HTTPClient http;
    
    http.begin(client, serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
     String httpRequestData = "api_key=" + apiKeyValue + "&tilt=" + String(tiltLevel)
                               + "&crackDepth=" + String(distanceCm) + "&waterlevel=" + String(waterlevel)+ "&strain=" + 
                               String(Weight) + "&roadStatus=" + String(roadStatus) + "&bridgeStatus=" + String(bridgeStatus) + "";// Prepare your HTTP POST request data
    
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);
    
    int httpResponseCode = http.POST(httpRequestData); // Send HTTP POST request
    if (httpResponseCode == HTTP_CODE_OK) {
      String response = http.getString();
      Serial.println("Response: " + response);// Print the response from the PHP file

    }else {
     Serial.println("Error: " + http.errorToString(httpResponseCode));
    }
    http.end();// Free resources
  }
  else {
    Serial.println("WiFi Disconnected");
  }
  
  delay(2000); 

  //----------------------------------------------------------------------------------
}

                //Not safe function
//_________________________________________________________________________________________________
void BridgeNotSafe(){
        roadStatus ="CLOSED";
        bridgeStatus="NOT SAFE TO USE";
       // Serial.println(tiltLevel);

        lcd.setCursor(0, 1);
        servo.write(90); //lotating the servo motor 90 degrees
        lcd.print("NOT SAFE TO USE"); // printing message on the LCD 

        tone(Passive_buzzer, 1046) ; // SI note ...
        delay (1000); 
        noTone(Passive_buzzer) ; //Turn off the pin attached to the tone()
        delay(200);
  }
//________________________________________________________________________________________________

                //Bridge safe section
//==============================================================================================
  void BridgeSafe(){
        roadStatus ="OPENED";
        bridgeStatus="SAFE TO USE";
        tiltLevel = "NO TILT";

        lcd.setCursor(0, 1);
        servo.write(0);
        lcd.print("SAFE TO USE");
        //delay(100);      
  }
//======================================================================================================


  void OverWeight(){
        roadStatus ="CLOSED";
        bridgeStatus="NOT SAFE TO USE";
       
        lcd.setCursor(0, 1);
        servo.write(90); //lotating the servo motor 90 degrees
        lcd.print("EXCEEDED MAX LOAD"); // printing message on the LCD 

        tone(Passive_buzzer, 1046) ; // SI note ...
        delay (1000); 
        noTone(Passive_buzzer) ; //Turn off the pin attached to the tone()
        delay(200);
  }
