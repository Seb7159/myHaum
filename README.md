# myHaum

A project which adds tons of sensors in one product in order to serve one's home security.

## Getting Started

These instructions will get you a copy of the project up and running on your local Arduino micro-controller for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
Arduino IDE
Arduino Mega 2560 micro-controller
```

### Installing

A step by step series of examples that tell you have to get a development env running

Add the sensors needed (those you can observe in the code)

```
DHT11
MQ2 Gas sensor
Photoresistor
PIR Motion sensor
LCD display
2 buttons
ESP8266-01 WiFi module
Passive buzzer
```

Make sure you edit the WiFi name and password 

```
Default WiFi name: H218N
Default WiFi pass: certificat
```

Hopefully this will work out for you. 

## Running the tests

Test it by keeping it on for as long as you want. 

### Break down into end to end tests

Tests are for finding bugs during the using period of the product.

## Deployment

To deploy this, you need to download the folder with the .ino file, connect your Arduino with the connected sensors to the pins you have in the source code, then Run it by pressing the Arduino IDE button. 

## Built With

* [Arduino](https://www.arduino.cc/) - The micro-controller framework used
* [ESP8266](https://espressif.com/en/products/hardware/esp8266ex/overview) - Used to send data to the SQL server thorugh the ESP8266-01 module

## Versioning

We use [GitHub](http://github.com/) for versioning. For the versions available, see the commits from this project (https://github.com/Seba7159/myHaum). 

## Authors

* **Sebastian Stanici** - *All the work* - [Seba7159](https://github.com/Seba7159)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* One's name who finds bug fixes and contribues will have his name on the contributors list
* Was inspired by all the news following the problems with companies spying on clients, this project is for one's own use and myHaum team will never use your data, keeping it safe and secure 
