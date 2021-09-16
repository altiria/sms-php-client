![](http://static.altiria.com/wp-content/themes/altiria/images/logo-altiria.png)


# Altiria, cliente SMS PHP

Para poder utilizar nuestro servicio es necesario previamente crear una cuenta en Altiria. Es habitual crear una **cuenta de prueba** en la que cargamos una serie de créditos de manera gratuita para que puedas realizar pruebas durante la integración.

Cabe mencionar que este proyecto consta de una **sección de tests** que al ser lanzados pueden suponer un **consumo de créditos**. En concreto, son los test "TestAltiriaSmsPhpClientSendSmsHttp.testOkMandatoryParams" y "TestAltiriaSmsPhpClientSendSmsHttp.testOkAllParams" que al ser lanzados consumirán un mínimo de cuatro créditos. Este consumo puede verse incrementado si se habilitan las características comentadas en el test "TestAltiriaSmsPhpClientSendSmsHttp.testOkAllParams", como son:
- ack/idAck: permite certificar la entrega del SMS previa configuración de una URL de callback por nuestra parte. Contactar con soporte@altiria.com para más información.
- certDelivery: suministra un certificado digital de entrega.

Antes de lanzar los tests es necesario **parametrizar cada suite** con los datos del usuario tal y como se indica en los comentarios del código en la parte superior de cada suite bajo el comentario "configurable parameters". Los parámetros a configurar son los siguientes:
- login: email de la cuenta.
- password: contraseña de la cuenta.
- destination: teléfono destino. Es importante agregar el prefijo internacional y no incluir ningún símbolo ni espacio. Ejemplo: '346XXXXXXXX'.
- sender: (opcional) remitente. Si no quiere ser enviado asignar null como valor.
- debug: si se le asigna el valor true, se mostrará información adicional en el log que puede resultar interesante para depurar.


