 
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
  delay(500);
}

void loop() {
   lcd.setCursor(0, 0);
   lcd.print("BHMS");
   lcd.setCursor(0, 1);
   lcd.print("Bridge Monitoring");
   delay(500);

   int viberation=digitalRead(VibrationPin);//reading the value from the vibration sensor 

   Waterlevelvalues = analogRead(WaterLevelSensorPin); //Read data from analog pin and store it to resval variable
   
    //The logic 
    if(viberation == 1 || Waterlevelvalues>330)
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

