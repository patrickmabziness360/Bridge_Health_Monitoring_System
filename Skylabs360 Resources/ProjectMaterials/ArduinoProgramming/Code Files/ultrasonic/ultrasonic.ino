
#include <LiquidCrystal_I2C.h>
LiquidCrystal_I2C lcd(0x20,16,2);
// defines pins numbers
const int trigPin = 10; // pin on the arduinowhere the trigger pin is connected
const int echoPin = 9;// pin on the arduino where the echo pin is connected

// defines variables
long duration; // variable where the the reflection time of the ultrasound is stored
int distance; // variable where the distance of the measured object is stored


void setup()
{
    
  lcd.init();// set up the LCD's number of columns and rows:
  
  lcd.backlight();//turn the backlight on
  lcd.print("BHMS");// Print a message to the LCD.
  delay(500);
  lcd.clear();

         pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
           pinMode(echoPin, INPUT); // Sets the echoPin as an Input
        
         }
     
     void loop()
 {
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
//distance= microsecondsTocentimeters(duration);

// Prints the distance on the Serial Monitor
lcd.print("Distance: ");
lcd.println(distance);
delay (100);
lcd.clear();

 }