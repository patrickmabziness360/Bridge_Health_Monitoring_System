 
// include the library code:
#include <Wire.h>
#include <LiquidCrystal_I2C.h>


// initialize the library by associating any needed LCD interface pin
// with the arduino pin number it is connected to
LiquidCrystal_I2C lcd(0x20,16,2); 

void setup() {
  // set up the LCD's number of columns and rows:
  lcd.init();
  // Print a message to the LCD.
  lcd.backlight();
  lcd.print("Skylabs360");
  delay(1500);
  lcd.clear();
}

void loop() {
   lcd.setCursor(0, 0);
   lcd.print("Skylabs360");
   lcd.setCursor(0, 1);
   lcd.print("Bridge Monitoring");
   delay(500);
   lcd.setCursor(0, 1);
   lcd.print("       ");
   delay(500);
}

