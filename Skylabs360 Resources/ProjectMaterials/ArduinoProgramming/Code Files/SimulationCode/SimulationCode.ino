 
// include the library code:
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>
#include "HX711.h"
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//variable declaration

Servo servo;
int VibrationPin = 2;
int ServoPin = 6;
int Waterlevelvalues = 0;  // holds the value
int WaterLevelSensorPin = A0; // sensor pin used

// defines pins numbers
const int trigPin = 10; // pin on the arduinowhere the trigger pin is connected
const int echoPin = 9;// pin on the arduino where the echo pin is connected

long duration; // variable where the the reflection time of the ultrasound is stored
int distance; // variable where the distance of the measured object is stored

LiquidCrystal_I2C lcd(0x20,16,2); // initialize the library by associating any needed LCD interface pin, with the arduino pin number it is connected to

// HX711 circuit wiring
const int LOADCELL_DOUT_PIN = 8;
const int LOADCELL_SCK_PIN = 7;
int weight;

HX711 scale;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

void setup() {
  //initialization of pins
  pinMode(VibrationPin,INPUT);//vibration sensor pin


  lcd.init();// set up the LCD's number of columns and rows:
  
  lcd.backlight();//turn the backlight on
  //lcd.print("Bridge Monitoring");// Print a message to the LCD.
  //delay(200);
  lcd.clear();

  //servo configurations 
  servo.attach(ServoPin); //D6
  servo.write(0);

  //utrasonic sensor
  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input
   
   //scale calibulating
  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);
  scale.set_scale(100.059);                      // this value is obtained by calibrating the scale with known weights; see the README for details
  scale.tare();               // reset the scale to 0
  delay(200);
}

void loop() {
  //  lcd.setCursor(0, 2);
  lcd.clear();
   lcd.print("BRIDGE MONITORING");
   lcd.setCursor(0, 1);
   delay(100);


      // Clears the trigPin
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
        
      // Sets the trigPin on HIGH state for 10 micro seconds
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);
        
    // Reads the echoPin, returns the sound wave travel time in microseconds
    duration = pulseIn(echoPin, HIGH);
      
    // Calculating the distance
    distance= duration*0.034/2;
    

   int Vibration=digitalRead(VibrationPin);//reading the value from the vibration sensor 

   Waterlevelvalues = analogRead(WaterLevelSensorPin); //Read data from analog pin and store it to resval variable

   //calculating the weight
   weight=scale.get_units()/1000.1;
   
    //The logic 
    if(Vibration ==1 || Waterlevelvalues>330 || distance>500 || weight>3.00)
    {
       
        lcd.setCursor(0, 1);
        servo.write(90);
        lcd.print("NOT SAFE TO USE");
        delay(100);
    }
    else
      {
        
        lcd.setCursor(0, 1);
        servo.write(0);
        lcd.print("SAFE TO USE");
        delay(100);
      }

}

