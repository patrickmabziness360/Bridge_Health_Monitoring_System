import serial
import requests
import re
# Set up serial port
ser = serial.Serial('COM1', 9600) # Replace 'COM3' with the appropriate port name on your system

# ThingSpeak channel details
channel_id = '2151389'
write_api_key = '64D5HRFCLRY9AX6V'

while True:
    # Read data from serial port
    data = ser.readline().strip().decode('utf-8')

    # Extract numeric values using regular expressions
    distance = re.findall(r"Distance: (\d+)", data)
    vibration = re.findall(r"Vibration: (\d+)", data)
    water_level = re.findall(r"Water Level: (\d+)", data)
    weight = re.findall(r"Weight: (\d+)", data)

    if distance and vibration and water_level and weight:
        distance = int(distance[0])
        vibration = int(vibration[0])
        water_level = int(water_level[0])
        weight = int(weight[0])

        # Prepare payload for ThingSpeak
        url = f'https://api.thingspeak.com/update.json?api_key={write_api_key}'
        payload = {
            'field1': distance,
            'field2': vibration,
            'field3': water_level,
            'field4': weight,
        }

        # Send data to ThingSpeak
        response = requests.post(url, json=payload)

        print('Data sent to ThingSpeak. Response:', response.text)
    else:
        print('Invalid data format:', data)
