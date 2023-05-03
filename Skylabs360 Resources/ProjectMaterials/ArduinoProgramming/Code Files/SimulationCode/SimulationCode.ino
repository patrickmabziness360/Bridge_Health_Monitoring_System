 
// include the library code:
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//variable declaration

Servo servo;
int VibrationPin = 2;
int ServoPin = 6;
int Waterlevelvalues = 0;  // holds the value
int WaterLevelSensorPin = A3; // sensor pin used

// defines pins numbers
const int trigPin = 10; // pin on the arduinowhere the trigger pin is connected
const int echoPin = 9;// pin on the arduino where the echo pin is connected

long duration; // variable where the the reflection time of the ultrasound is stored
int distance; // variable where the distance of the measured object is stored

LiquidCrystal_I2C lcd(0x20,16,2); // initialize the library by associating any needed LCD interface pin, with the arduino pin number it is connected to

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

void setup() {
  //initialization of pins
  pinMode(VibrationPin,INPUT);//vibration sensor pin


  lcd.init();// set up the LCD's number of columns and rows:
  
  lcd.backlight();//turn the backlight on
  lcd.print("BHMS");// Print a message to the LCD.
  delay(500);
  lcd.clear();

  //servo configurations 
  servo.attach(ServoPin); //D6
  servo.write(0);

  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input

  delay(500);
}

void loop() {
   lcd.setCursor(0, 0);
   lcd.print("BHMS");
   lcd.setCursor(0, 1);
   lcd.print("Bridge Monitoring");
   delay(500);


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
    

   int viberation=digitalRead(VibrationPin);//reading the value from the vibration sensor 

   Waterlevelvalues = analogRead(WaterLevelSensorPin); //Read data from analog pin and store it to resval variable
   
    //The logic 
    if(viberation == 1 || Waterlevelvalues>330 || distance >500 )
    {
       
        lcd.setCursor(0, 1);
        servo.write(90);
        lcd.print("Not Safe");
        delay(500);
    }
    else
      {
        
        lcd.setCursor(0, 1);
        servo.write(0);
        lcd.print("Safe");
        delay(500);
      }

}

