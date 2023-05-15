//lIbraries
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>
#include <ThingSpeak.h>
#include <ESP8266WiFi.h>

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
              //Varible section

LiquidCrystal_I2C lcd(0x27, 16, 2); // Set the LCD address to 0x27 for a 16 chars and 2 line display
Servo servo; // servo object
int VibrationPin = D3; //vibration sensor pin
int ServoPin = D4; //servo motor pin 

const int trigPin = 12;
const int echoPin = 14;

//define sound velocity in cm/uS
#define SOUND_VELOCITY 0.034
long duration;
float distanceCm;

const char* thinkspeak_write_api_key ="E0QVN8SP3RECUH2K";
const char* host = "api.thingspeak.com";
//const long channelId = "2148181";

#define SSID "SKYLABS360(MABZINESS)"
#define PASSWORD "Tazie25800p"
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

WiFiClient client;

void setup() {

  ThingSpeak.begin(client);
  WiFi.begin(SSID, PASSWORD);
  Serial.begin(115200); // Starts the serial communication
  Serial.print(WiFi.status());

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

 // Prints the distance on the Serial Monitor
  Serial.print("Distance (cm): ");
  Serial.println(distanceCm);
 ThingSpeak.writeField(2148181, 1, distanceCm, thinkspeak_write_api_key);
  //if(distanceCm > 8.0){
  if(Vibration == 1 || distanceCm > 8.0){
       
        lcd.setCursor(0, 1);
        Serial.println(" Alert Viberation"); //serial monitor for debug 
        servo.write(90); //lotating the servo motor 90 degrees
        lcd.print("NOT SAFE TO USE"); // printing message on the LCD 
        delay(200);

  }else{
        
        lcd.setCursor(0, 1);
        Serial.println(" No Vibrations");
        servo.write(0);
        lcd.print("SAFE TO USE");
        delay(200);
  }

  //----------------------------------------------------------------------------------
  
}
