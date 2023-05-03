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
  delay(500);
  lcd.clear();
  

  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);

  // lcd.println("Before setting up the scale:");
  // lcd.print("read: \t\t");
  // lcd.println(scale.read());      // print a raw reading from the ADC

  // lcd.print("read average: \t\t");
  // lcd.println(scale.read_average(20));   // print the average of 20 readings from the ADC

  // lcd.print("get value: \t\t");
  // lcd.println(scale.get_value(5));   // print the average of 5 readings from the ADC minus the tare weight (not set yet)

  // lcd.print("get units: \t\t");
  // lcd.print(scale.get_units(5), 1);  // print the average of 5 readings from the ADC minus tare weight (not set) divided
  //           // by the SCALE parameter (not set yet)
            
  scale.set_scale(36.059);
  //scale.set_scale(-471.497);                      // this value is obtained by calibrating the scale with known weights; see the README for details
  scale.tare();               // reset the scale to 0

  // lcd.print("After setting up the scale:");

  // lcd.print("read: \t\t");
  // lcd.print(scale.read());                 // print a raw reading from the ADC

  // lcd.print("read average: \t\t");
  // lcd.print(scale.read_average(20));       // print the average of 20 readings from the ADC

  // lcd.print("get value: \t\t");
  // lcd.print(scale.get_value(5));   // print the average of 5 readings from the ADC minus the tare weight, set with tare()

  // lcd.print("get units: \t\t");
  // lcd.print(scale.get_units(5), 1);        // print the average of 5 readings from the ADC minus tare weight, divided
  //           // by the SCALE parameter set with set_scale

  // lcd.print("Readings:");
}

void loop() {
  lcd.clear();
  lcd.print("Weight in KG:\t");
  lcd.print(scale.get_units()/1000, 1);
  lcd.print("\t| average:\t");
  lcd.print(scale.get_units(10), 5);

  delay(5);
}
