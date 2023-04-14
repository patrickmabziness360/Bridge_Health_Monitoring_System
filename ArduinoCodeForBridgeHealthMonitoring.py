#include <SoftwareSerial.h>
#include <LiquidCrystal.h>

SoftwareSerial; //RX, TX
LiquidCrystal lcd(2, 3, 4, 5, 6, 7); // Creates an LC object. Parameters: (rs, enable, d4, d5, d6, d7)

#define lm35 A0
#define vibra A1
#define levelsens A2

boolean sendsms1 = false;
boolean sendsms2 = false;
boolean sendsms3 = false;

int temp, value;
int vibrationvalue;
int levelvalue;

byte degree[8] = { 0b00011, 0b00011, 0b00000, 0b00000, 0b00000, 0b00000, 0b00000, 0b00000 };



void setup()

{
  Serial.begin(9600); //Initialise serial to communicate with GSM Modem
  delay(100);
  pinMode(lm35, INPUT);
  pinMode(vibra, INPUT);
  pinMode(levelsens, INPUT);
  pinMode(8,OUTPUT);
  lcd.begin(16, 2);
  digitalWrite(8,LOW);
  delay(500);
  Serial.begin(9600);
  falan();
  singlecon();
  delay(500);
  lcd.createChar(1, degree);
  lcd.setCursor(0, 0);
  lcd.print("Bridge Health");
  lcd.setCursor(0, 1);
  lcd.print("Monitering");
  delay(1000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("B.E PROJECT");
  lcd.setCursor(0, 1);
  lcd.print("By Vaibhav Pawar");
  delay(1000);
  lcd.clear();
  delay(1000);
}
void loop()
{
  value = analogRead(lm35);//LM35 temperature sensor calibration
  temp = (value / 3) + 4;
  vibrationvalue = analogRead(vibra);
  levelvalue = analogRead(levelsens);
  delay(500); //Give enough time for GSM to register on Network
  Serial.print("vibration: ");
  Serial.print(vibrationvalue, 1);
  Serial.print(" hz\n");
  Serial.print("water level: ");
  Serial.print(levelvalue, 1);
  Serial.print(" mm\n");
  Serial.print("temperature value: ");
  Serial.print(temp, 1);
  Serial.print(" *C\n");

  delay(500);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("temperature");
  lcd.setCursor(0, 1);
  lcd.print(temp); // temperature data on LCD
  lcd.print(" *C");
  delay(1000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print(vibrationvalue);
  lcd.setCursor(0, 1);
  lcd.print("vibration"); // Vibration data on LCD
  lcd.write(1);
  lcd.print(" Hz");
  delay(1000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Level");
  lcd.setCursor(0, 1);
  lcd.print(levelvalue); // Level data on LCD
  lcd.print(" mm");
  delay(1000);
  lcd.clear();

  if
  (temp > 25)
  {
    sendsms1 = true; // SMS for temperature high
  }
  else
  {
    sendsms1 = false;
  }
  if (vibrationvalue > 800)
  {
    sendsms2 = true; // SMS for vibration high
  }
  else
  {
    sendsms2 = false;
  }

  if (levelvalue > 600)
  {
    sendsms3 = true; // SMS for Close water level
  }
  else
  { sendsms3 = false;
  }

  if (sendsms1)
  {
    delay(1000);

    Serial.println("AT+CMGF=1"); //To send SMS in Text Mode
    delay(500);
    Serial.println("AT+CMGS=\"+919604724852\"\r"); //Change to destination phone number
    delay(500);
    Serial.print("Very High,");
    Serial.print(" Temperature at bridge ");
    Serial.print(temp);
    Serial.println(" *C");
    Serial.println((char)26); //the stopping character Ctrl+Z
    lcd.setCursor(0, 0);
    lcd.print("Very high ");
    delay(1000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Temperature");
    lcd.setCursor(0, 1);
    lcd.print(temp);
    lcd.print(" C");
    delay(1000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sending SMS");
    delay(1000);
    lcd.clear();
    delay(10);
  }
  if (sendsms2)
  {
    delay(1000);
    Serial.println("AT+CMGF=1");
    delay(500);
    Serial.println("AT+CMGS=\"+919604724852\"\r");
    delay(500);
    Serial.print("high vibration at bridge, ");
    Serial.print(" vibration ");
    Serial.print(vibrationvalue);
    Serial.println(" Hz");
    Serial.println((char)26);
    lcd.setCursor(0, 0);
    lcd.print("Vibration High ");
    delay(1000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("vibration");
    lcd.setCursor(0, 1);
    lcd.print(vibrationvalue);
    lcd.write(1);
    lcd.print("Hz");
    delay(1000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sending SMS");
    delay(1000);
    lcd.clear();
    delay(10);
  }

  if (sendsms3)
  {
    delay(1000);
    Serial.println("AT+CMGF=1");
    delay(500);
    Serial.println("AT+CMGS=\"+919604724852\"\r");
    delay(500);
    Serial.print("High level,");
    Serial.print(" of water,");
    Serial.print(levelvalue);
    Serial.println(" cm");
    Serial.println((char)26);
    lcd.setCursor(0, 0);
    lcd.print("High level");
    delay(1000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("of Water, ");
    lcd.setCursor(0, 1);
    lcd.print(levelvalue);
    lcd.print("  cm");
    delay(1000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sending SMS");
    delay(1000);
    lcd.clear();
    delay(10);
  }
  delay(500);

  falan();
  singlecon();

}
void falan()
{
  Serial.println("Please Wait...");
  Serial.println("AT");
  delay(1000);

  Serial.println("AT+CPIN?");
  delay(1000);

  Serial.println("AT+CREG?");
  delay(1000);

  Serial.println("AT+CIPSHUT");
  delay(1000);

  Serial.println("AT+CIPSTATUS");

  delay(1000);

}
void singlecon()
{
  Serial.println("Sending Data To Cloud...");
  Serial.println("AT+CIPMUX=0");
  delay(1000);

  Serial.print("AT+CSTT=");
  Serial.print('"');
  Serial.print("internet");
  Serial.println('"');
  delay(1000);

  Serial.println("AT+CIICR");
  delay(1000);


  Serial.println("AT+CIFSR");
  delay(1000);


  Serial.print("AT+CIPSTART=");
  Serial.print('"');
  Serial.print("TCP");
  Serial.print('"');
  Serial.print(',');
  Serial.print('"');
  Serial.print("api.thingspeak.com");
  Serial.print('"');
  Serial.print(',');
  Serial.print("80");
  Serial.write(0x0d);
  Serial.write(0x0a);
  delay(2000);

  Serial.println("AT+CIPSEND");
  delay(1000);

  Serial.print("GET");
  Serial.print(' ');
  //gsm.print("http:");
  //gsm.print('/');
  //gsm.print('/');
  //gsm.print("api.thingspeak.com");
  Serial.print('/');
  Serial.print("update?api_key=");
  Serial.print("TW0CD2WM7DXTCBTY");//my API Key
  Serial.print("&field1=");
  Serial.print(vibrationvalue);
  Serial.print("&field2=");
  Serial.print(levelvalue);
  Serial.print("&field3=");
  Serial.print(temp);

  Serial.write(0x0d);
  Serial.write(0x0a);

  Serial.write(0x1a); // the trick is here to send the request. Its Ctrl+Z to start send process.
  delay(1000);



  Serial.println("AT+CIPSHUT");
  Serial.println("AT+CIPCLOSE");
  Serial.write(0x0d);
  Serial.write(0x0a);
  delay(1000);

}