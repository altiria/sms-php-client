![](http://static.altiria.com/wp-content/themes/altiria/images/logo-altiria.png)


# Altiria, cliente SMS PHP

 ![](https://img.shields.io/badge/version-1.0.0-blue.svg)

Altiria SMS PHP es un cliente que simplifica al máximo la integración de nuestro API para PHP. Por el momento, esta librería abarca las funciones más básicas:
- **Envíos de SMS individuales** con las siguientes características:
  - sencillos
  - concatenados
  - certificación de entrega con o sin identificador
  - certificado digital de entrega
  - uso de remitente
  - seleccionar codificación
- **Consultas de crédito**

Esta librería hace uso de **composer** y cumple con las especificaciones **PSR-4**.

## Requisitos

- php: ^7.0.0
- [composer](https://getcomposer.org/)

## Instalación

La forma recomendada de instalar el cliente Altiria para PHP es a través de Composer .

### A través de línea de comandos

<pre>
composer require altiria/sms-php-client
</pre>

### Editando el fichero composer.json y actualizando el proyecto

En este caso, añadir el siguiente fragmento al fichero composer.json.

<pre>
"require": {
	"altiria/sms-php-client": "1.0.0"
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
try {
    $client = new AltiriaClient('user@mydomain.com', 'mypassword');
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

#### Ejemplo básico con timeout personalizado

Permite fijar el tiempo de respuesta en milisegundos. Si se supera se lanzará una **ConnectionException**.
Por defecto el tiempo de respuesta es de 10 segundos, pero puede ser ajustado entre 1 y 30 segundos.

```php
try {
    $client = new AltiriaClient('user@mydomain.com', 'mypassword', 5000);
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

Se trata de la opción más sencilla para realizar un envío de SMS añadiendo remitente.

```php
try {
    $client = new AltiriaClient('user@mydomain.com', 'mypassword');
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
try {
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

A continuación se describen cada una de las posibilidades de uso de la librería para consultar el crédito.

#### Ejemplo básico

Este ejemplo no incluye los parámetros opcionales.

```php
try {
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

#### Ejemplo con todos los parámetros

Este ejemplo incluye los parámetros opcionales.

```php
try {
    $client = new AltiriaClient('user@mydomain.com', 'mypassword', 5000);
    $client->setDebug(true);
    $client->setConnectTimeout(1000);
    // $client->setTimeout(5000); Se puede asignar aquí o en el constructor
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
