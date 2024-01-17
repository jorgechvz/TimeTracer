#include <HTTPClient.h>
#include <WiFi.h>
#include <DHT.h>

const char *ssid = "GUSTAVO";
const char *password = "gustavoraul99";

// Configuración del sensor DHT11
#define DHTPIN 15 // Pin digital conectado al sensor DHT11
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

float temperature;
float humidity;
String distritos[] = {"Selva Alegre", "Cercado", "Cayma", "Cerro Colorado", "Yanahuara", "Mariano Melgar", "Miraflores", "Paucarpata", "Socabaya", "Hunter", "Jacobo Hunter", "Alto Selva Alegre", "José Luis Bustamante y Rivero", "Tiabaya", "Uchumayo", "Yura", "Characato", "La Joya", "Mollebaya", "Polobaya", "Quequeña", "Sabandía", "Sachaca", "Tingo", "Yarabamba"};

void
setup()
{
  delay(10);
  Serial.begin(115200);

  WiFi.begin(ssid, password);

  Serial.print("Conectando...");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }

  Serial.println("Conectado con éxito, mi IP es: ");
  Serial.println(WiFi.localIP());

  // Inicializar el sensor DHT11
  dht.begin();
}

void loop()
{
  if (WiFi.status() == WL_CONNECTED)
  {
    // Lectura del sensor DHT11
    temperature = dht.readTemperature();
    humidity = dht.readHumidity();

    // Seleccionar un distrito al azar
    int index = random(0, sizeof(distritos) / sizeof(distritos[0]));
    String distrito_aleatorio = distritos[index];

    // Construir los datos a enviar
    String datos_a_enviar = "temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&distrito=" + distrito_aleatorio;

    HTTPClient http;
    http.begin("http://192.168.18.53/sistema/recibe_data.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int codigo_respuesta = http.POST(datos_a_enviar);

    if (codigo_respuesta > 0)
    {
      Serial.println("Código HTTP --> " + String(codigo_respuesta));

      if (codigo_respuesta == 200)
      {
        String cuerpo_respuesta = http.getString();
        Serial.println("El servidor respondió (^_^) (^_^) (^_^) (^_^) ");
        Serial.println(cuerpo_respuesta);
      }
    }
    else
    {
      Serial.print("Error enviando POST, código: ");
      Serial.println(codigo_respuesta);
    }

    http.end();
  }
  else
  {
    Serial.println("Error en la conexión WIFI");
  }

  delay(10000);
}
