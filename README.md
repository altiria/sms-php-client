![](http://static.altiria.com/wp-content/themes/altiria/images/logo-altiria.png)


# Altiria, cliente envío de SMS con PHP

 ![](https://img.shields.io/badge/version-1.0.2-blue.svg)

Altiria SMS PHP es el cliente de envío de SMS que simplifica al máximo la integración del API SMS para PHP de Altiria.
- **Envíos de SMS individuales**
  - sencillos
  - concatenados
  - confirmación de entrega
  - remitente personalizado
- **Consultas de crédito**

Esta librería hace uso de **composer** y cumple con las especificaciones **PSR-4**.

## Uso

Es necesario tener una cuenta de envío con Altiria. Si todavía no tienes una,

[Regístrate para crear una cuenta de prueba](https://www.altiria.com/free-trial/)

[Documentación de la API](https://www.altiria.com/api-envio-sms/)

## Requisitos

- php: ^7.0.0
- [composer](https://getcomposer.org/)

## Instalación

La forma recomendada de instalar el cliente Altiria para PHP es a través de Composer. Puedes hacerlo de dos maneras:

### A través de línea de comandos

<pre>
composer require altiria/sms-php-client
</pre>

### Editando el fichero composer.json y actualizando el proyecto

En este caso, añadir el siguiente fragmento al fichero composer.json.

<pre>
"require": {
	"altiria/sms-php-client": "1.0.2"
}
</pre>

A continuación, actualizar el proyecto ejecutando la siguiente instrucción por línea de comandos.

<pre>
composer install
</pre>

## Ejemplos de uso

### Envío de SMS

A continuación se describen cada una de las posibilidades de uso de la librería para realizar envíos de SMS.

#### Ejemplo básico

Se trata de la opción más sencilla para realizar un envío de SMS.

```php
use \AltiriaSmsPhpClient\AltiriaClient;
use \AltiriaSmsPhpClient\AltiriaModelTextMessage;
use \AltiriaSmsPhpClient\Exception\GeneralAltiriaException;

try {
    //Personaliza las credenciales de acceso
    $client = new AltiriaClient('user@mydomain.com', 'mypassword');
    $textMessage = new AltiriaModelTextMessage('346XXXXXXXX', 'Mensaje de prueba');
    $client-> sendSms($textMessage);
    echo '¡Mensaje enviado!';
} catch (GeneralAltiriaException $exception) {
    echo 'Mensaje no aceptado:'.$exception->getMessage();
}
```

#### Ejemplo básico con timeout personalizado

Permite fijar el tiempo de respuesta en milisegundos. Si se supera se lanzará una **ConnectionException**.
Por defecto el tiempo de respuesta es de 10 segundos, pero puede ser ajustado entre 1 y 30 segundos.

```php
use \AltiriaSmsPhpClient\AltiriaClient;
use \AltiriaSmsPhpClient\AltiriaModelTextMessage;

try {
    //Personaliza las credenciales de acceso
    $client = new AltiriaClient('user@mydomain.com', 'mypassword', false, 5000);
    $textMessage = new AltiriaModelTextMessage('346XXXXXXXX', 'Mensaje de prueba');
    $client-> sendSms($textMessage);
    echo '¡Mensaje enviado!';
} catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
    echo 'Mensaje no aceptado:'.$exception->getMessage();
    echo 'Código de error: '.$exception->getStatus();
} catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
    echo 'Error en la petición:'.$exception->getMessage();
} catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
    if ($exception->getMessage().strpos('RESPONSE_TIMEOUT', 0) != -1) {
        echo 'Tiempo de respuesta agotado: '.$exception->getMessage();
    } else {
        echo 'Tiempo de conexión agotado: '.$exception->getMessage();
    }
}
```

#### Ejemplo básico con remitente

Se trata de la opción más sencilla para realizar un envío de SMS añadiendo remitente. En este caso, se ilustra cómo realizar una autentificación mediante APIKEY, donde "XXXXXXXXXX" es el parámetro **apiKey** y "YYYYYYYYYY" el parámetro **apiSecret**.

```php
use \AltiriaSmsPhpClient\AltiriaClient;
use \AltiriaSmsPhpClient\AltiriaModelTextMessage;

try {
    //Personaliza las credenciales de acceso
    $client = new AltiriaClient('XXXXXXXXXX', 'YYYYYYYYYY', true);
    $textMessage = new AltiriaModelTextMessage('346XXXXXXXX', 'Mensaje de prueba', 'miRemitente');
    $client-> sendSms($textMessage);
    echo '¡Mensaje enviado!';
} catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
    echo 'Mensaje no aceptado:'.$exception->getMessage();
    echo 'Código de error: '.$exception->getStatus();
} catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
    echo 'Error en la petición:'.$exception->getMessage();
} catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
    if ($exception->getMessage().strpos('RESPONSE_TIMEOUT', 0) != -1) {
        echo 'Tiempo de respuesta agotado: '.$exception->getMessage();
    } else {
        echo 'Tiempo de conexión agotado: '.$exception->getMessage();
    }
}
```
#### Ejemplo con todos los parámetros

Se muestra un ejemplo utilizando todo los parámetros mediante setters.

```php
use \AltiriaSmsPhpClient\AltiriaClient;
use \AltiriaSmsPhpClient\AltiriaModelTextMessage;

try {
    //Personaliza las credenciales de acceso
    $client = new AltiriaClient('user@mydomain.com', 'mypassword');
    $client->setConnectTimeout(1000);
    $client->setTimeout(5000);
    $client->setDebug(true);
    $textMessage = new AltiriaModelTextMessage('346XXXXXXXX', 'Mensaje de prueba');
    $textMessage->setSenderId('miRemitente');
    $textMessage->setAck(true);
    $textMessage->setIdAck('idAck');
    $textMessage->setConcat(true);
    $textMessage->setEncoding('unicode');
    $textMessage->setCertDelivery(true);
    $client-> sendSms($textMessage);
    echo '¡Mensaje enviado!';
} catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
    echo 'Mensaje no aceptado:'.$exception->getMessage();
    echo 'Código de error: '.$exception->getStatus();
} catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
    echo 'Error en la petición:'.$exception->getMessage();
} catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
    if ($exception->getMessage().strpos('RESPONSE_TIMEOUT', 0) != -1) {
        echo 'Tiempo de respuesta agotado: '.$exception->getMessage();
    } else {
        echo 'Tiempo de conexión agotado: '.$exception->getMessage();
    }
}
```
### Consulta de crédito

Ejemplos de consulta del crédito de SMS en la cuenta de Altiria.

#### Ejemplo básico

```php
use \AltiriaSmsPhpClient\AltiriaClient;

try {
    //Personaliza las credenciales de acceso
    $client = new AltiriaClient('user@mydomain.com', 'mypassword');
    $credit = $client-> getCredit();
    echo 'Crédito disponible: '.$credit;
} catch (\AltiriaSmsPhpClient\Exception\AltiriaGwException $exception) {
    echo 'Solicitud no aceptada:'.$exception->getMessage();
    echo 'Código de error: '.$exception->getStatus();
} catch (\AltiriaSmsPhpClient\Exception\JsonException $exception) {
    echo 'Error en la petición:'.$exception->getMessage();
} catch (\AltiriaSmsPhpClient\Exception\ConnectionException $exception) {
    if ($exception->getMessage().strpos('RESPONSE_TIMEOUT', 0) != -1) {
        echo 'Tiempo de respuesta agotado: '.$exception->getMessage();
    } else {
        echo 'Tiempo de conexión agotado: '.$exception->getMessage();
    }
}
```

## Licencia

La licencia de esta librería es de tipo MIT. Para más información consultar el fichero de licencia.

## Ayuda

Utilizamos la sección de problemas de GitHub para tratar errores y valorar nuevas funciones.
Para cualquier problema durante la intergración contactar a través del email soporte@altiria.com.
