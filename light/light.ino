#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
const char* ssid = "H218N";
const char* password = "certificat";

// 13 is pin 7 
// 16 is pin 0 

ESP8266WebServer server(80);

const int led = 13;
int onff = 0, door = 0; 

void handleRoot() {
  server.send(200, "text/plain", "Hello Seba!");
}

void handleOffline() {
  onff++; onff%=2;
  if(onff==1)
    server.send(200, "text/plain", "Light ON!");
  else
    server.send(200, "text/plain", "Light OFF!");
  digitalWrite(13, onff);
}

void handleNotFound(){
  digitalWrite(led, 1);
  String message = "File Not Found\n\n";
  message += "URI: ";
  message += server.uri();
  message += "\nMethod: ";
  message += (server.method() == HTTP_GET)?"GET":"POST";
  message += "\nArguments: ";
  message += server.args();
  message += "\n";
  for (uint8_t i=0; i<server.args(); i++){
    message += " " + server.argName(i) + ": " + server.arg(i) + "\n";
  }
  server.send(404, "text/plain", message);
  digitalWrite(led, 0);
}

void setup(){ 
  pinMode(led, OUTPUT);
  digitalWrite(led, 0);
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  Serial.println("");

  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());



  Serial.print("connecting to ");
  Serial.println("seba.tm-edu.ro");
  
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect("seba.tm-edu.ro", httpPort)) {
    Serial.println("connection failed");
    return;
  }
  
  String url = "/";
  url += "get/getIP.php";
  url += "?p=";
  url += "abc";
  url += "&ip=";
  url += String(WiFi.localIP()[3]); 
  
  Serial.print("Requesting URL: ");
  Serial.println(url);
  
  // This will send the request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + "seba.tm-edu.ro" + "\r\n" + 
               "Connection: close\r\n\r\n");
  unsigned long timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println(">>> Client Timeout !");
      client.stop();
      return;
    }
  }

  Serial.println("\n connection stopped"); 





  if (MDNS.begin("esp8266")) {
    Serial.println("MDNS responder started");
  }

  server.on("/", handleRoot);

  server.on("/offline", handleOffline);

  server.on("/inline", [](){
      server.send(200, "text/plain", "this works as well");
  });

  server.onNotFound(handleNotFound);

  server.begin();
  Serial.println("HTTP server started");
}

void loop(){
  server.handleClient();
}
