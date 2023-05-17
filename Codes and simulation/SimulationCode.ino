 
// include the library code:
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//variable declaration
Servo servo;
int VibrationPin = 2;
int ServoPin = 2;

LiquidCrystal_I2C lcd(0x20,16,2); // initialize the library by associating any needed LCD interface pin, with the arduino pin number it is connected to
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

void setup() {
  //initialization of pins
  pinMode(VibrationPin,INPUT);//vibration sensor pin


  lcd.init();// set up the LCD's number of columns and rows:
  
  lcd.backlight();//turn the backlight on
  lcd.print("BHMS");// Print a message to the LCD.
  delay(1500);
  lcd.clear();

  //servo configurations 
  servo.attach(2); //D4
  servo.write(0);
  delay(2000);
}

void loop() {
   lcd.setCursor(0, 0);
   lcd.print("BHMS");
   lcd.setCursor(0, 1);
   lcd.print("Bridge Monitoring");
   delay(500);

   int viberation=digitalRead(VibrationPin);//reading the value from the vibration sensor 

    //vibration logic 
    if(viberation == 1)
    {
        lcd.clear();
        lcd.setCursor(0, 1);
        servo.write(90);
        lcd.print("Not Safe");
        delay(500);
    }
    else
      {
        lcd.clear();
        lcd.setCursor(0, 1);
        servo.write(0);
        lcd.print("Safe");
        delay(500);
      }

}

