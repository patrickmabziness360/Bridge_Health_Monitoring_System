#include <Arduino_BuiltIn.h>
 
// include the library code:
#include <Wire.h>
#include <LiquidCrystal_I2C.h>


// initialize the library by associating any needed LCD interface pin
// with the arduino pin number it is connected to
LiquidCrystal_I2C lcd(0x20,16,2); 
 
int resval = 0;  // holds the value
int respin = A3; // sensor pin used

void setup() {
  
  lcd.init();
  lcd.backlight();
  // Print a message to the LCD. 
  lcd.print("  WATER LEVEL : ");
   
  
}

void loop() {

  // set the cursor to column 0, line 1 
  lcd.setCursor(0, 1); 
    
  resval = analogRead(respin); //Read data from analog pin and store it to resval variable
   
  if (resval<=100){ lcd.println("     Empty    "); } 
  else if (resval>100 && resval<=300){ lcd.println("       Low      "); }
  else if (resval>300 && resval<=330){ lcd.println("     Medium     "); } 
  else if (resval>330){ lcd.println("      High      "); }
  delay(1000); 

  
}


  

  

