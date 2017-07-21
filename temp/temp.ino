#include <Arduino.h> 
#include <SoftwareSerial.h>
#include <ESP8266_Simple.h>
#include <LiquidCrystal.h> 
#include <DHT.h> 

#define DEBUG 1 

#define PHOTOpin A0    // analog photoresistor 
#define GASpin A7      // analog gas 
#define PIRsensorPin 24// digital PIR  
#define pBlu 3         // digital blue LED 
#define pGrn 4         // digital green LED
#define pRed 5         // digital red LED 
#define ESP8266_SSID  "H218N"
#define ESP8266_PASS  "certificat"
#define RXpinSEND 12   // ESP-01 wifiSEND module TX
#define TXpinSEND 13   // ESP-01 wifiSEND module RX 
#define RELAYbutton 15 // digital switch 1
#define ALARMbutton 14 // digital switch 2 
#define DHTpin 21      // digital temp sensor  
#define BUZZpin 22     // digital buzzer 

#define REFRESHupload 30 //30*60 second to upload data to server / to be changed to 15 min

LiquidCrystal lcd(51, 50, 6, 7, 8, 9); 
ESP8266_Simple wifiSEND( RXpinSEND, TXpinSEND); 
DHT dht( DHTpin, DHT11); 

byte smiley[8] = {
  B00000,
  B11011,
  B00000,
  B00000,
  B10001,
  B01110,
  B00000,
};
byte doo[8] = {
  B10000,
  B10000,
  B10000,
  B10000,
  B10000,
  B10000,
  B10000,
};
byte re[8] = {
  B11000,
  B11000,
  B11000,
  B11000,
  B11000,
  B11000,
  B11000, 
};
byte mi[8] = {
  B11100,
  B11100,
  B11100,
  B11100,
  B11100,
  B11100,
  B11100,
};
byte fa[8] = {
  B11110,
  B11110,
  B11110,
  B11110,
  B11110,
  B11110,
  B11110,
};
byte sol[8] = {
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
  B11111,
};

void setup(){
  lcd.createChar(0,smiley); 
  lcd.createChar(1,doo); 
  lcd.createChar(2,re); 
  lcd.createChar(3,mi); 
  lcd.createChar(4,fa); 
  lcd.createChar(5,sol); 
  lcd.begin(16, 2); 
  lcd.setCursor(0,0); 
  lcd.print("Loading...             "); 
  int loadTime = 0; 
  loadScreen(loadTime); loadTime++;  
  pinMode(pRed, OUTPUT); 
  pinMode(pGrn, OUTPUT); 
  pinMode(pBlu, OUTPUT); 
  pinMode(PIRsensorPin,INPUT); 
  pinMode(ALARMbutton,INPUT); 
  pinMode(RELAYbutton,INPUT); 
  pinMode(BUZZpin, OUTPUT); 
  Serial.begin(9600); 
  wifiSEND.begin(9600); loadScreen(loadTime); loadTime++; 
  

  lcd.setCursor(0,0); lcd.print("Resetting...             ");
  for( loadTime = 2 ; loadTime < 10; ++loadTime)
    loadScreen(loadTime); 



  
  char bufferr[300]; 
  wifiSEND.sendCommand(F("AT"), bufferr, sizeof(bufferr)); delay(50); 
  if(DEBUG) 
    Serial.println(bufferr);
    
  wifiSEND.sendCommand(F("AT+CWJAP?"), bufferr, sizeof(bufferr));
  delay(2000); 
  if(DEBUG){ 
    Serial.println(bufferr);
    Serial.println(ESP8266_SSID);     
  }

  if(bufferr[0]!=ESP8266_SSID[0] || bufferr[1]!=ESP8266_SSID[1] || bufferr[2]!=ESP8266_SSID[2]){ 
  wifiSEND.sendCommand(F("AT+RST"), bufferr, sizeof(bufferr)); 
  delay(1000); 
  wifiSEND.setupAsWifiStation(ESP8266_SSID, ESP8266_PASS, &Serial); 
  delay(5000); 
  }
  
  lcd.setCursor(0,0); lcd.print("Connecting...             "); 
  for( loadTime = 10 ; loadTime < 16; ++loadTime)
    loadScreen(loadTime); 
  lcd.setCursor(0,1); 
  lcd.print(" Ready!            ");
  delay(800); 
}

int alarm=0,lightBulb=0; 
int r=0; 
unsigned long WIFIupload = -REFRESHupload ,LASTmotion = 0; 

void loop()
{  
  Serial.println("Starting new cycle"); 
  
  if(DEBUG) Serial.print("Gas value is "); 
  if(DEBUG) Serial.println(analogRead(GASpin)); 
  if(analogRead(GASpin)>500){
      lcd.setCursor(0,0); 
      lcd.print(" WARNING! Gas"); 
      lcd.setCursor(0,1); 
      lcd.print("  limit exceeded!"); 
      tone(BUZZpin, 2000, 500); 

      char buffer[250]; 

      memcpy(buffer, 0, sizeof(buffer));     
      strncpy_P(buffer, PSTR("/get/getgas.php?p=abc"), sizeof(buffer)-1);
     
      strncpy_P(buffer+strlen(buffer), PSTR("&lastg="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(analogRead(GASpin), buffer+strlen(buffer), 10); 
        
      Serial.print("Requesting GAS");
      Serial.print(buffer);
      Serial.print(": ");
      
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
      Serial.println("OK");
      Serial.println(buffer);
    }
    else
      if(httpResponseCode < 100) 
        wifiSEND.debugPrintError((byte)httpResponseCode, &Serial);
      else{
        Serial.print("HTTP Status ");
        Serial.println(httpResponseCode);
      }     
   
     delay(1000); 
  }
  else
    noTone(BUZZpin); 

 
  if(digitalRead(ALARMbutton)){
    alarm++; alarm=alarm%2; 
    Serial.print("Alarm is "); Serial.println(alarm);  
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
          Serial.print("Alarm is "); Serial.println(alarm);  
          break; 
        }
      }
      if(alarm){
        lcd.setCursor(0,0); 
        lcd.print("House is now"); 
        lcd.setCursor(0,1); 
        lcd.print("      ARMED!          "); 
        setColor(255,0,0); 
        }
      else{
        lcd.setCursor(0,0); 
        lcd.print("House is now"); 
        lcd.setCursor(0,1); 
        lcd.print("    UNARMED!           "); 
        setColor(0,0,255); 
      }
    }
    else{
      lcd.setCursor(0,0); 
      lcd.print("House is now"); 
      lcd.setCursor(0,1); 
      lcd.print("    UNARMED!          "); 
      setColor(0,0,255); 
    } 
    delay(2000); 
    setColor(0,0,0); 
  }
  

  if(digitalRead(RELAYbutton)){
    lightBulb++; lightBulb=lightBulb%2; 
    Serial.print("The light bulb is "); Serial.println(lightBulb); 
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
  
  
  if(digitalRead(PIRsensorPin)){ // motion sense 
    if(DEBUG) Serial.println("Somebody is in this area!"); 
    LASTmotion=millis()/1000; 
    
    if(alarm==1){
      char buffer[250]; 
    
      memcpy(buffer, 0, sizeof(buffer));     
      strncpy_P(buffer, PSTR("/get/getmotion.php?p=abc"), sizeof(buffer)-1);
     
      strncpy_P(buffer+strlen(buffer), PSTR("&lastm="), sizeof(buffer)-strlen(buffer)-1);
      ltoa(millis()/1000, buffer+strlen(buffer), 10); 
        
      Serial.print("Requesting MOTION");
      Serial.print(buffer);
      Serial.print(": ");
      
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
        Serial.println("OK");
        Serial.println(buffer);
      }
      else
        if(httpResponseCode < 100) 
          wifiSEND.debugPrintError((byte)httpResponseCode, &Serial);
        else{
          Serial.print("HTTP Status ");
          Serial.println(httpResponseCode);
        }
    }  
  }


  if(DEBUG){ 
    Serial.println("Brightness: "); // brightness   
    Serial.println(analogRead(PHOTOpin));  
    Serial.println(); 
    Serial.print("Temperature = "); // DHT 
    Serial.println(dht.readTemperature());
    Serial.print("Humidity = ");
    Serial.println(dht.readHumidity()); 
    Serial.println(); 
  }
  
  if(r==0){
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
    lcd.print(analogRead(GASpin)); lcd.print("  ");
    lcd.write(byte(0)); lcd.print("               ");
  }


  if(WIFIupload+REFRESHupload<millis()/1000){

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
        
      Serial.print("Requesting UPLOAD");
      Serial.print(buffer);
      Serial.print(": ");
      
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
        Serial.println("OK");
        Serial.println(buffer);
      }
      else
        if(httpResponseCode < 100) 
          wifiSEND.debugPrintError((byte)httpResponseCode, &Serial);
        else{
          Serial.print("HTTP Status ");
          Serial.println(httpResponseCode);
        }
    WIFIupload=millis()/1000; 
  }

  
  if(DEBUG) 
    for(int i=0;i<30;i++) Serial.println();   
  delay(2000);
}

void setColor(int red, int green, int blue)
{
  digitalWrite(pRed, red);
  digitalWrite(pGrn, green);
  digitalWrite(pBlu, blue);  
}

void loadScreen(int j){
  for(int i=1;i<=5;i++){
    lcd.setCursor(j,1);
    lcd.write(byte(i));  
    delay(140-j*5); 
  }
}

