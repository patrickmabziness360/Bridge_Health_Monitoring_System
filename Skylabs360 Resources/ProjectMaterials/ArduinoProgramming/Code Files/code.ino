#include <ESP8266WiFi.h> // ESP8266WiFi.h library

const char* ssid     = "Model Finishing School";// replace subscribe with your WiFi SSID(Name)
const char* password = "mfs2988009";//replace with Your Wifi Password name
const char* host = "api.thingspeak.com";
const char* writeAPIKey = "JJ6CPL5Z7ZKE1116"; //copy yout ThingSpeak channel API Key.



void setup() {
 Serial.println("Connecting to ");
       Serial.println(ssid);
//  Connect to WiFi network
  WiFi.begin(ssid, password);
while (WiFi.status() != WL_CONNECTED) {
delay(500);
    Serial.print(".");
  }
   Serial.println("");
   Serial.println("WiFi connected");

  pinMode(D0,INPUT);//moist
  pinMode(D1,INPUT);//vib
    pinMode(D5,OUTPUT);//buz
   


// Initialize sensor
 Serial.begin(115200);


}
void loop() {



int viberation=digitalRead(D1);
int water=digitalRead(D0);
 int x =analogRead(A0);


// Serial.println(viberation);
 // Serial.println( x);
  //  Serial.println(water);

if(viberation == 1)
{
   
    digitalWrite(D5,HIGH);
    Serial.println(" Alert Viberation");
}
 else
  {
     digitalWrite(D5,LOW);
     Serial.println("Safe");
  }
if(water==0)
{   
 
    digitalWrite(D5,HIGH);
     Serial.println("High Moisture");
}
 else
  {
     digitalWrite(D5,LOW);
     Serial.println("Safe");
  }
  
if(x<460)
{digitalWrite(D5,HIGH);
  Serial.println("High Tilt");
  }
  else
  {
     digitalWrite(D5,LOW);
     Serial.println("Safe");
  }
  WiFiClient client;
const int httpPort = 80;
if (!client.connect(host, httpPort)) {
return;
  }
  String url = "/update?key=";
  url+=writeAPIKey;
  url+="&field1=";
  url+=String(viberation);
  url+="&field2=";
  url+=String(water);
    url+="&field3=";
  url+=String(x);
  url+="\r\n";
// Request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
"Host: " + host + "\r\n" + 
"Connection: close\r\n\r\n");
client.stop();
delay(1000);

}#include <ESP8266WiFi.h> // ESP8266WiFi.h library

const char* ssid     = "Model Finishing School";// replace subscribe with your WiFi SSID(Name)
const char* password = "mfs2988009";//replace with Your Wifi Password name
const char* host = "api.thingspeak.com";
const char* writeAPIKey = "JJ6CPL5Z7ZKE1116"; //copy yout ThingSpeak channel API Key.



void setup() {
 Serial.println("Connecting to ");
       Serial.println(ssid);
//  Connect to WiFi network
  WiFi.begin(ssid, password);
while (WiFi.status() != WL_CONNECTED) {
delay(500);
    Serial.print(".");
  }
   Serial.println("");
   Serial.println("WiFi connected");

  pinMode(D0,INPUT);//moist
  pinMode(D1,INPUT);//vib
  pinMode(D5,OUTPUT);//buz
   


// Initialize sensor
 Serial.begin(115200);


}
void loop() {



int viberation=digitalRead(D1);
int water=digitalRead(D0);
 int x =analogRead(A0);


// Serial.println(viberation);
 // Serial.println( x);
  //  Serial.println(water);

if(viberation == 1)
{
   
    digitalWrite(D5,HIGH);
    Serial.println(" Alert Viberation");
}
 else
  {
     digitalWrite(D5,LOW);
     Serial.println("Safe");
  }
if(water==0)
{   
 
    digitalWrite(D5,HIGH);
     Serial.println("High Moisture");
}
 else
  {
     digitalWrite(D5,LOW);
     Serial.println("Safe");
  }
  
if(x<460)
{digitalWrite(D5,HIGH);
  Serial.println("High Tilt");
  }
  else
  {
     digitalWrite(D5,LOW);
     Serial.println("Safe");
  }
  WiFiClient client;
const int httpPort = 80;
if (!client.connect(host, httpPort)) {
return;
  }
  String url = "/update?key=";
  url+=writeAPIKey;
  url+="&field1=";
  url+=String(viberation);
  url+="&field2=";
  url+=String(water);
    url+="&field3=";
  url+=String(x);
  url+="\r\n";
// Request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
"Host: " + host + "\r\n" + 
"Connection: close\r\n\r\n");
client.stop();
delay(1000);

}
