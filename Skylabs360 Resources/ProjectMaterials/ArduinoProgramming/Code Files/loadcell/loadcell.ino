#include <Arduino.h>
#include "HX711.h"
#include <LiquidCrystal_I2C.h>

// HX711 circuit wiring
const int LOADCELL_DOUT_PIN = 8;
const int LOADCELL_SCK_PIN = 7;

HX711 scale;
LiquidCrystal_I2C lcd(0x20,16,2);
void setup() {

  
  lcd.init();// set up the LCD's number of columns and rows:
  
  lcd.backlight();//turn the backlight on
  lcd.print("BHMS");// Print a message to the LCD.
  delay(5);
  lcd.clear();
  

  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);

            
  scale.set_scale(36.059);
  //scale.set_scale(-471.497);                      // this value is obtained by calibrating the scale with known weights; see the README for details
  scale.tare();               // reset the scale to 0


}

void loop() {
  lcd.clear();
  lcd.print("Weight in KG:\t");
  lcd.print(scale.get_units()/1000),1;
  // lcd.print("\t| average:\t");
  // lcd.print(scale.get_units(10), 5);

  delay(5);
}
