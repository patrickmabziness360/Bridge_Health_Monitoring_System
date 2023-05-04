#include <Arduino_BuiltIn.h>
#include <LiquidCrystal.h>

//initialise the library with the numbers of the interface pins 
LiquidCrystal lcd(7, 6, 5, 4, 3, 2); 
 
int resval = 0;  // holds the value
int respin = A3; // sensor pin used
  
void setup() { 
 
  // set up the LCD's number of columns and rows: 
  lcd.begin(16, 2); 
  
  // Print a message to the LCD. 
  lcd.print("  WATER LEVEL : "); 
} 
  
void loop() { 
  // set the cursor to column 0, line 1 
  lcd.setCursor(0, 1); 
    
  resval = analogRead(respin); //Read data from analog pin and store it to resval variable
   
  if (resval<=100){ lcd.println("   Empty  "); } 
  else if (resval>100 && resval<=300){ lcd.println("     Low    "); }
  else if (resval>300 && resval<=330){ lcd.println("   Medium   "); } 
  else if (resval>330){ lcd.println("    High    "); }
  delay(1000); 
}
