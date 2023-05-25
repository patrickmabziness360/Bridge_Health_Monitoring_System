//lIbraries
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>
#include <ESP8266WiFi.h>

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
const char* password = "Tazie25800p";

// URL path for sending posted data
const char* serverName = "http://192.168.0.6/OurProject/BridgeHelathMonitoringSystem/admin/post-sensor-values.php";

//api key for each microcontroller 
String apiKeyValue = "tPmAT5Ab3j7F9";

LiquidCrystal_I2C lcd(0x27, 16, 2); // Set the LCD address to 0x27 for a 16 chars and 2 line display
Servo servo; // servo object
int VibrationPin = D3; //vibration sensor pin
int ServoPin = D4; //servo motor pin 
int Passive_buzzer = D8; // BUZZER 
const int trigPin = 12;
const int echoPin = 14;

//define sound velocity in cm/uS
#define SOUND_VELOCITY 0.034
long duration;
float distanceCm;

String roadStatus;
String bridgeStatus;

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

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

  //Vibration pin configuration
  pinMode(VibrationPin,INPUT);//vibration sensor pin

   //ultrasonic configurations
  
  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input

  pinMode (Passive_buzzer,OUTPUT) ;// this is the pin mode for the buzzer
}

void loop() {
  //----------------------------------------------------------------------------
        //This is the logic section
   lcd.clear(); //clearing the LCD
   lcd.print("BRIDGE MONITORING"); //print message on the screen 
   lcd.setCursor(0, 1); //Set the cursor where you want the lcd to be printing 
   delay(200); //delay 

   int Vibration=digitalRead(VibrationPin); //reading the value from the vibration sensor 
    
   //ThingSpeak.writeField(2148181, 1, Vibration, thinkspeak_write_api_key);

   // Clears the trigPin
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  // Sets the trigPin on HIGH state for 10 micro seconds
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  
  // Reads the echoPin, returns the sound wave travel time in microseconds
  duration = pulseIn(echoPin, HIGH);
  
  // Calculate the distance
  distanceCm = duration * SOUND_VELOCITY/2;  

  //if(distanceCm > 8.0){

  Serial.print("vibration: ");
  Serial.println(Vibration);

  // Prints the distance on the Serial Monitor
  Serial.print("Distance (cm): ");
  Serial.println(distanceCm);

  //this is for the water level
  int waterlevel=analogRead(A0); // Water Level Sensor output pin connected A0
  Serial.print("Water level: ");
  Serial.println(waterlevel);  // See the Value In Serial Monitor

  if( Vibration != 1 ){
    Serial.println(" Alert Viberation"); //serial monitor for debug 
    BridgeNotSafe();

  }else if(distanceCm > 8.0){
    BridgeNotSafe();

 }else if(waterlevel > 740){
    BridgeNotSafe();

 }else{
        roadStatus ="OPENED";
        bridgeStatus="SAFE TO USE";

        lcd.setCursor(0, 1);
        servo.write(0);
        Serial.print(" No Vibration: ");
        lcd.print("SAFE TO USE");
        delay(200);
  }


  //Check WiFi connection status
  if(WiFi.status()== WL_CONNECTED){
    WiFiClient client;
    HTTPClient http;
    
    // Your Domain name with URL path or IP address with path
    http.begin(client, serverName);
    
    // Specify content-type header
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Prepare your HTTP POST request data
     String httpRequestData = "api_key=" + apiKeyValue + "&vibration=" + String(Vibration)
                        + "&crackDepth=" + String(distanceCm) + "&waterlevel=" + String(waterlevel)
                       + "&strain=" + String(waterlevel) + "&accelometer=" + String(waterlevel) + "&roadStatus=" + String(roadStatus) + "&bridgeStatus=" + String(bridgeStatus) + "";
    

    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);
    
    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);
 
    if (httpResponseCode == HTTP_CODE_OK) {
      // Print the response from the PHP file
      String response = http.getString();
      Serial.println("Response: " + response);
    }
    else {
     Serial.println("Error: " + http.errorToString(httpResponseCode));
    }
    // Free resources
    http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }
  //Send an HTTP POST request every 30 seconds
  delay(30000); 

  //----------------------------------------------------------------------------------
}

 //not safe function
  void BridgeNotSafe(){
        roadStatus ="CLOSED";
        bridgeStatus="NOT SAFE TO USE";

        lcd.setCursor(0, 1);
        servo.write(90); //lotating the servo motor 90 degrees
        lcd.print("NOT SAFE TO USE"); // printing message on the LCD 

        tone(Passive_buzzer, 1046) ; // SI note ...
        delay (1000); 
        noTone(Passive_buzzer) ; //Turn off the pin attached to the tone()
        delay(200);

       
  }
