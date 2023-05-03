#include <Servo.h>

Servo servo;

void setup() {

servo.attach(6); //D4

servo.write(0);

delay(2000);

}

void loop() {

servo.write(90);

delay(10000);

servo.write(0);

delay(1000);

}