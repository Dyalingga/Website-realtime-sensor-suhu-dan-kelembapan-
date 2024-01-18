#include <AES.h>
#include <AESLib.h>
#include <AES_config.h>
#include <base64.h>

#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"

const char *ssid = "SamsungA31";  
const char *password = "madara123";

#define DHTPIN D5
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// we need to install AESLib and ESP8266 additional board.

AES aes;
byte cipher[1000];
char b64[1000];

// msg: message need to be encrypted.
// key_str: secrete key, 16 bytes
// iv_str:  initial vector, 16 bytes
String do_encrypt(String msg, String key_str, String iv_str) {

  byte iv[16];
  // copy the iv_str content to the array.
  memcpy(iv,(byte *) iv_str.c_str(), 16);

  // use base64 encoder to encode the message content. It is optional step.
  int blen=base64_encode(b64,(char *)msg.c_str(),msg.length());

  // calculate the output size:
  aes.calc_size_n_pad(blen);
  // custom padding, in this case, we use zero padding:
  int len=aes.get_size();
  byte plain_p[len];
  for(int i=0;i<blen;++i) plain_p[i]=b64[i];
  for(int i=blen;i<len;++i) plain_p[i]='\0';

  // do AES-128-CBC encryption:
  int blocks = len / 16;
  aes.set_key ((byte *)key_str.c_str(), 16) ;
  aes.cbc_encrypt (plain_p, cipher, blocks, iv);

  // use base64 encoder to encode the encrypted data:
  base64_encode(b64,(char *)cipher,len);
  //Serial.println("Encrypted Data output: "+String((char *)b64));
  return String((char *)b64);
}

void setup() {
  dht.begin();
  delay(1000);
  Serial.begin(115200);
  WiFi.mode(WIFI_OFF); 
  delay(1000);
  WiFi.mode(WIFI_STA); 
  WiFi.begin(ssid, password);    
  Serial.println("");
  Serial.print("Connecting");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP()); 
}

void loop() {
  HTTPClient http;

  String temperature, humidity, getData, Link;
  String baseURL, datasend, otpESP;

  temperature = dht.readTemperature();
  humidity = dht.readHumidity();

  Serial.println(temperature);
  Serial.println(humidity);

  baseURL = "http://lutanedukasi.co.id/skripsi_dipa/input.php?encrypted=";
  otpESP = "74138";

  datasend = "suhu=" + temperature + "&kelembaban=" + humidity + "&otpesp=" + otpESP;
  
  String msg= datasend;
  String key_str="aaaaaaaaaaaaaaaa";// 16 bytes
  String iv_str="aaaaaaaaaaaaaaaa"; //16 bytes
  String encrypted_data=do_encrypt(msg,key_str,iv_str);

  // getData = "?suhu=" + temperature + "&kelembaban=" + humidity + "&otpesp=34653"; 
  //Link = "http://192.168.252.218/skripsi_dipa/input.php" + getData;

  Link = baseURL + encrypted_data ;
  //Link="http://192.168.252.218/skripsi_dipa/test.php";
  http.begin(Link);    
  
  int httpCode = http.GET();           
  String payload = http.getString();

  Serial.println(httpCode);   
  Serial.println(payload);
  Serial.println(Link);
  Serial.println ("Encrypted Data: " +  encrypted_data);
  //Serial.println("besaran_data: " + http.getSize());

  http.end(); 
  
  delay(300000);  
}


  
 
  

  
