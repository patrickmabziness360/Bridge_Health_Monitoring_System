//lIbraries
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
              //Varible section

LiquidCrystal_I2C lcd(0x27, 16, 2); // Set the LCD address to 0x27 for a 16 chars and 2 line display
Servo servo; // servo object
int VibrationPin = D3; //vibration sensor pin
int ServoPin = D4; //servo motor pin 

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



void setup() {
  Serial.begin(9600);
  //configuring the lcd 
  lcd.init(); // Initialize the LCD
  lcd.backlight();// Turn on the backlight
  lcd.clear();// Clear the display
  Wire.setClock(10000);
  
  //servo configurations 
  servo.attach(ServoPin); //D4
  servo.write(0);

  //Vibration pin configuration
  pinMode(VibrationPin,INPUT);//vibration sensor pin
}

void loop() {
  //----------------------------------------------------------------------------
        //This is the logic section
   lcd.clear(); //clearing the LCD
   lcd.print("BRIDGE MONITORING"); //print message on the screen 
   lcd.setCursor(0, 1); //Set the cursor where you want the lcd to be printing 
   delay(200); //delay 

int Vibration=digitalRead(VibrationPin); //reading the value from the vibration sensor 
    
    if(Vibration == 1){
       
        lcd.setCursor(0, 1);
        Serial.println(" Alert Viberation"); //serial monitor for debug 
        servo.write(90); //lotating the servo motor 90 degrees
        lcd.print("NOT SAFE TO USE"); // printing message on the LCD 
        delay(200);
    }
    else
      {
        
        lcd.setCursor(0, 1);
        Serial.println(" No Vibrations");
        servo.write(0);
        lcd.print("SAFE TO USE");
        delay(200);
      }

  //----------------------------------------------------------------------------------
  
}
