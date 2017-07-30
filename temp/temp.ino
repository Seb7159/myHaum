#include <Arduino.h>                   // the libraries 
#include <SoftwareSerial.h>
#include <ESP8266_Simple.h>
#include <LiquidCrystal.h> 
#include <DHT.h> 

#define DEBUG 0                         // if DEBUG is 1, the arduino will print data on Serial 

#define PHOTOpin A0                     // analog photoresistor 
#define GASpin A7                       // analog gas 
#define PIRsensorPin 24                 // digital PIR  
#define pBlu 3                          // digital blue LED 
#define pGrn 4                          // digital green LED
#define pRed 5                          // digital red LED 
#define ESP8266_SSID  "H218N"           // WiFi SSID
#define ESP8266_PASS  "certificat"      // WiFi Password
#define RXpinSEND 12                    // ESP-01 wifiSEND module TX
#define TXpinSEND 13                    // ESP-01 wifiSEND module RX 
#define RELAYbutton 15                  // digital switch 1
#define ALARMbutton 14                  // digital switch 2 
#define DHTpin 21                       // digital temp sensor  
#define BUZZpin 22                      // digital buzzer 

#define REFRESHupload 30                // seconds to upload data to database / to be changed to 15 min

LiquidCrystal lcd(11, 10, 6, 7, 8, 9);  // actual pins for LCD displaying content 
ESP8266_Simple wifiSEND( RXpinSEND, TXpinSEND); 
DHT dht( DHTpin, DHT11); 

byte doo[8] = {                         // set 7 frames of an animation in a LCD square when loading                 
  B00000,
  B00000,
  B00000,
  B00000,
  B00000,
  B10000,
  B11000,
};
byte re[8] = {
  B00000,
  B00000,
  B00000,
  B10000,
  B11000,
  B11100,
  B11110,
};
byte mi[8] = {
  B00000,
  B00000,
  B10000,
  B11000,
  B11100,
  B11100,
  B11111,
};
byte fa[8] = {
  B00000,
  B10000,
  B11000,
  B11100,
  B11110,
  B11111,
  B11111,
};
byte sol[8] = {
  B10000,
  B11000,
  B11100,
  B11110,
  B11111,
  B11111,
  B11111,
};
byte la[8] = {
  B11100,
  B11110,
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
};
byte si[8] = {
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
};










void setup(){
  lcd.createChar(1,doo);                  // create the animation frames by the micro-controller 
  lcd.createChar(2,re); 
  lcd.createChar(3,mi); 
  lcd.createChar(4,fa); 
  lcd.createChar(5,sol); 
  lcd.createChar(6,la); 
  lcd.createChar(7,si);
  
  lcd.begin(16, 2);                   
  lcd.setCursor(0,0); 
  lcd.print("Loading...             "); 
  
  int loadTime = 0;                       // begin animation 
  loadScreen(loadTime); loadTime++;  
  pinMode(pRed, OUTPUT);                  // set pin layout for arduino sending input and obtaining output 
  pinMode(pGrn, OUTPUT); 
  pinMode(pBlu, OUTPUT); 
  pinMode(PIRsensorPin,INPUT); 
  pinMode(ALARMbutton,INPUT); 
  pinMode(RELAYbutton,INPUT); 
  pinMode(BUZZpin, OUTPUT); 
  Serial.begin(9600); 
  wifiSEND.begin(9600); loadScreen(loadTime); loadTime++;                  // start WiFi module 
  

  lcd.setCursor(0,0); lcd.print("Resetting...             ");
  for( loadTime = 2 ; loadTime < 6; ++loadTime)
    loadScreen(loadTime); 

  char bufferr[300]; 
  wifiSEND.sendCommand(F("AT"), bufferr, sizeof(bufferr)); delay(50); 
  if(DEBUG) 
    Serial.println(bufferr);

  for( loadTime = 6 ; loadTime < 7; ++loadTime)
    loadScreen(loadTime); 

    
  wifiSEND.sendCommand(F("AT+CWJAP?"), bufferr, sizeof(bufferr));
  delay(100); 
  if(DEBUG){ 
    Serial.println(bufferr);
    Serial.println(ESP8266_SSID);     
  }

  for( loadTime = 7 ; loadTime < 10; ++loadTime)
    loadScreen(loadTime); 

  if(bufferr[0]!=ESP8266_SSID[0] || bufferr[1]!=ESP8266_SSID[1] || bufferr[2]!=ESP8266_SSID[2]){          // test if the ESP is already connected to previous WiFi 
  wifiSEND.sendCommand(F("AT+RST"), bufferr, sizeof(bufferr));                 // reset WiFi module 
  delay(2000); 
  wifiSEND.setupAsWifiStation(ESP8266_SSID, ESP8266_PASS, &Serial);            // connect to the WiFi with the set ssid and password 
  delay(5000); 
  }
  
  lcd.setCursor(0,0); lcd.print("Connecting...             "); 
  for( loadTime = 10 ; loadTime < 16; ++loadTime)
    loadScreen(loadTime); 
  lcd.setCursor(0,1); 
  lcd.print(" Ready!            ");
  delay(800); 
}








int alarm=0,lightBulb=0;                                                   // set booleans for alarm and lightBulb if they are on/off 
unsigned long WIFIupload = -REFRESHupload ,LASTmotion = 0;                 // set timeout for wifi uploading and last motion sensed 







void loop()                                                                // start a new cycle
{  
  if(DEBUG) Serial.println("Starting new cycle");                           


  
  if(DEBUG) Serial.print("Gas value is ");                                 // read gas and alarm if gas levels are too high ( over 45% = gas leaks, over 50% = hazardous environment ) 
  if(DEBUG) Serial.println(analogRead(GASpin)); 
  if(analogRead(GASpin)>450){
      if(analogRead(GASpin)>500){
        lcd.setCursor(0,0); 
        lcd.print(" WARNING! GAS"); 
        lcd.setCursor(0,1); 
        lcd.print("  LEVELS EXCEEDED!"); 
        tone(BUZZpin, 2000, 500); 
      }
      else{
        lcd.setCursor(0,0); 
        lcd.print(" WARNING! Gas"); 
        lcd.setCursor(0,1); 
        lcd.print("  leaks detected!"); 
      }

      char buffer[250];                                                                                  // send WiFi data in case of gas leaks (>25% gas levels)

      memcpy(buffer, 0, sizeof(buffer));     
      strncpy_P(buffer, PSTR("/get/getgas.php?p=abc"), sizeof(buffer)-1);
     
      strncpy_P(buffer+strlen(buffer), PSTR("&lastg="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(analogRead(GASpin), buffer+strlen(buffer), 10); 
        
      if(DEBUG) Serial.print("Requesting GAS");
      if(DEBUG) Serial.print(buffer);
      if(DEBUG) Serial.print(": ");
      
      unsigned int httpResponseCode = 
        wifiSEND.GET
        (
          F("86.123.250.85"),     
          80,                     
          buffer,                 
          sizeof(buffer),         
          F("seba.tm-edu.ro"), 
          2                       
                                   
                                  
        );
  
    if(httpResponseCode == 200 || httpResponseCode == ESP8266_OK){
      if(DEBUG) Serial.println("OK");
      if(DEBUG) Serial.println(buffer);
    }
    else
      if(httpResponseCode < 100) 
        wifiSEND.debugPrintError((byte)httpResponseCode, &Serial);
      else{
        if(DEBUG) Serial.print("HTTP Status ");
        if(DEBUG) Serial.println(httpResponseCode);
      }     
   
     delay(1000); 
  }
  else
    noTone(BUZZpin); 








 
  if(digitalRead(ALARMbutton)){                                                 // event if alarm button(2) is pushed 
    alarm++; alarm=alarm%2; 
    if(DEBUG) Serial.print("Alarm is "); if(DEBUG) Serial.println(alarm);  
    if(alarm){
      
      lcd.setCursor(0,0); lcd.print("House will be         "); 
      lcd.setCursor(0,1); lcd.print(" armed in         "); 
      delay(1000); 
      
      for( int i = 19 ; i>=0 ; --i ){
        lcd.setCursor(12,1);
        if(i<10) lcd.setCursor(11,1);
        lcd.print(i); lcd.print("         "); 

        setColor(40,0,0);
        delay(100); 
        setColor(0,0,0); 
        delay(900);

        if(digitalRead(ALARMbutton)){
          alarm++; alarm=alarm%2; 
          if(DEBUG) Serial.print("Alarm is "); if(DEBUG) Serial.println(alarm);  
          break; 
        }
      }
      if(alarm){
        lcd.setCursor(0,0); 
        lcd.print("House is now       "); 
        lcd.setCursor(0,1); 
        lcd.print("      ARMED!          "); 
        setColor(255,0,0); 
        }
      else{
        lcd.setCursor(0,0); 
        lcd.print("House is now       "); 
        lcd.setCursor(0,1); 
        lcd.print("    UNARMED!           "); 
        setColor(0,0,255); 
      }
    }
    else{
      lcd.setCursor(0,0); 
      lcd.print("House is now       "); 
      lcd.setCursor(0,1); 
      lcd.print("    UNARMED!          "); 
      setColor(0,0,255); 
    } 
    delay(2000); 
    setColor(0,0,0); 
  }
  









  if(digitalRead(RELAYbutton)){                                                                // event if light bulb button(1) is pushed 
    lightBulb++; lightBulb=lightBulb%2; 
    if(DEBUG) Serial.print("The light bulb is "); if(DEBUG) Serial.println(lightBulb); 
    if(lightBulb){
      lcd.setCursor(0,0); 
      lcd.print("Light bulb                "); 
      lcd.setCursor(0,1); 
      lcd.print("         ON!          "); 
      setColor(255,255,255); 
    }
    else{
      lcd.setCursor(0,0); 
      lcd.print("Light bulb          "); 
      lcd.setCursor(0,1); 
      lcd.print("         OFF!     "); 
      setColor(0,0,0); 
    }
    delay(900); 
  }
  






  
  if(digitalRead(PIRsensorPin)){                                                                 // event motion sense 
    if(DEBUG) Serial.println("Somebody is in this area!"); 
    LASTmotion=millis()/1000; 
    
    if(alarm==1){
      char buffer[250]; 
    
      memcpy(buffer, 0, sizeof(buffer));     
      strncpy_P(buffer, PSTR("/get/getmotion.php?p=abc"), sizeof(buffer)-1);
     
      strncpy_P(buffer+strlen(buffer), PSTR("&lastm="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(millis()/1000, buffer+strlen(buffer), 10); 
        
      if(DEBUG) Serial.print("Requesting MOTION");
      if(DEBUG) Serial.print(buffer);
      if(DEBUG) Serial.print(": ");
      
      unsigned int httpResponseCode = 
        wifiSEND.GET
        (
          F("86.123.250.85"),     
          80,                     
          buffer,                 
          sizeof(buffer),         
          F("seba.tm-edu.ro"), 
          2                       
                                   
                                  
        ); 
      
      if(httpResponseCode == 200 || httpResponseCode == ESP8266_OK){
        if(DEBUG) Serial.println("OK");
        if(DEBUG) Serial.println(buffer);
      }
      else
        if(httpResponseCode < 100) 
          wifiSEND.debugPrintError((byte)httpResponseCode, &Serial);
        else{
          if(DEBUG) Serial.print("HTTP Status ");
          if(DEBUG) Serial.println(httpResponseCode);
        }
    }  
  }












  if(DEBUG){                                                 // print data to Serial if DEBUG = 1
    Serial.println("Brightness: "); // brightness   
    Serial.println(analogRead(PHOTOpin));  
    Serial.println(); 
    Serial.print("Temperature = "); // DHT 
    Serial.println(dht.readTemperature());
    Serial.print("Humidity = ");
    Serial.println(dht.readHumidity()); 
    Serial.println(); 
  }






  
  if(lightBulb==0){                                                                                // display different data wheter the light bulb is on or off 
    lcd.setCursor(0,0); 
    lcd.print("  Humid="); lcd.print((int)dht.readHumidity()); lcd.print("%            ");  
    lcd.setCursor(0,1); 
    lcd.print(" Warmth="); lcd.print((int)dht.readTemperature()); lcd.println("'C            ");
  }
  else{
    lcd.setCursor(0,0); 
    lcd.print("Brightness: ");  
    lcd.print(analogRead(PHOTOpin)); 
    lcd.print("               "); 
    lcd.setCursor(0,1); 
    lcd.print("Gas: "); 
    lcd.print(analogRead(GASpin)); lcd.print("               "); 
  }









  if(WIFIupload+REFRESHupload<millis()/1000){                                                // send all the data from all the sensor over WiFi to SQL database  
    char buffer[250]; 
    
      memcpy(buffer, 0, sizeof(buffer));     
      strncpy_P(buffer, PSTR("/get/get.php?p=abc"), sizeof(buffer)-1);
     
      strncpy_P(buffer+strlen(buffer), PSTR("&temp="), sizeof(buffer)-strlen(buffer)-1);
      ltoa((int)dht.readTemperature(), buffer+strlen(buffer), 10); 

      strncpy_P(buffer+strlen(buffer), PSTR("&hum="), sizeof(buffer)-strlen(buffer)-1);
      ltoa((int)dht.readHumidity(), buffer+strlen(buffer), 10); 

      strncpy_P(buffer+strlen(buffer), PSTR("&gas="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(analogRead(GASpin), buffer+strlen(buffer), 10); 

      strncpy_P(buffer+strlen(buffer), PSTR("&light="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(analogRead(PHOTOpin), buffer+strlen(buffer), 10); 

      strncpy_P(buffer+strlen(buffer), PSTR("&ms="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(millis()/1000, buffer+strlen(buffer), 10); 

      strncpy_P(buffer+strlen(buffer), PSTR("&lmdbu="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(LASTmotion, buffer+strlen(buffer), 10); 

      strncpy_P(buffer+strlen(buffer), PSTR("&alarm="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(alarm, buffer+strlen(buffer), 10); 
        
      if(DEBUG) Serial.print("Requesting UPLOAD");
      if(DEBUG) Serial.print(buffer);
      if(DEBUG) Serial.print(": ");
      
      unsigned int httpResponseCode = 
        wifiSEND.GET
        (
          F("86.123.250.85"),     
          80,                     
          buffer,                 
          sizeof(buffer),         
          F("seba.tm-edu.ro"), 
          2                       
        ); 
      
      if(httpResponseCode == 200 || httpResponseCode == ESP8266_OK){
        if(DEBUG) Serial.println("OK");
        if(DEBUG) Serial.println(buffer);
      }
      else
        if(httpResponseCode < 100) 
          wifiSEND.debugPrintError((byte)httpResponseCode, &Serial);
        else{
          if(DEBUG) Serial.print("HTTP Status ");
          if(DEBUG) Serial.println(httpResponseCode);
        }
    WIFIupload=millis()/1000; 
  }





  
  if(DEBUG) 
    for(int i=0;i<30;i++) Serial.println();                   // clean screen of Serial 



  delay(2000);                                                // 2 second pause after cycle is over for avoiding overheating and over-use of electricity 
}                                                             // end of cycle 












void setColor(int red, int green, int blue)                  // function to set rgb color of light bulb 
{
  digitalWrite(pRed, red);
  digitalWrite(pGrn, green);
  digitalWrite(pBlu, blue);  
}








void loadScreen(int j){                                     // function for animation purpose 
  for(int i=1;i<=7;i++){
    lcd.setCursor(j,1);
    lcd.write(byte(i));  
    delay(80-j*3); 
  }
}

