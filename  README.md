# UDECUserInfoAPI

Este plugin de tipo API permite buscar usuarios en Moodle por correo electrónico, nombre de usuario o campos personalizados denominado identificación.

## Instalación

1. Copia la carpeta `UDECUserInfoAPI` en el directorio `local` de tu instalación de Moodle.
2. Ve a Administración del sitio > Notificaciones para completar la instalación del plugin.
3. Configura el servicio web y la clave del servicio según sea necesario.

## Uso

Puedes hacer solicitudes a la API usando el siguiente formato:



## Implementación de la API Personalizada en Moodle
**Descripción**
Este documento describe cómo interactuar con la API personalizada en Moodle para buscar usuarios por nombre de usuario, utilizando un token de autenticación para acceder a los servicios web de Moodle en formato JSON.

### URL de la API

~~~
{{url}}?wstoken={{token}}&moodlewsrestformat=json&search=elaverde&wsfunction=UDECUserInfoAPI_get_users
~~~

### Parámetros de la URL
**{{url}}:** La URL base de tu instalación de Moodle donde está alojado el servicio web.

**{{token}}:** El token de autenticación generado para el usuario que realiza la solicitud. Este token debe tener permisos suficientes para acceder a la función UDECUserInfoAPI_get_users.

**moodlewsrestformat=json:** Especifica el formato de respuesta JSON para la solicitud.

**search=elaverde:** Parámetro obligatorio para buscar usuarios con nombre de usuario que contenga "elaverde". Puedes ajustar este parámetro según tus necesidades de búsqueda.

**wsfunction=UDECUserInfoAPI_get_users:** Especifica la función de la API personalizada que deseas invocar para obtener usuarios por nombre de usuario.

## Métodos y Autenticación
**Método HTTP:** GET

**Autenticación:** Utiliza un token de acceso ({{token}}) que debe ser proporcionado como parte de la URL para autenticar la solicitud. Asegúrate de que este token tenga los permisos adecuados para ejecutar la función UDECUserInfoAPI_get_users.

## Respuesta
La API responderá con una lista de usuarios que coincidan con el criterio de búsqueda proporcionado en formato JSON.

**Ejemplo de Uso**
**Ejemplo de solicitud:**

~~~
GET {{url}}?wstoken={{token}}&moodlewsrestformat=json&search=elaverde&wsfunction=UDECUserInfoAPI_get_users
~~~

**Respuesta esperada:**

```json
{
    {
    "users": [
        {
            "id": 3,
            "username": "elaverde",
            "firstname": "Edilson",
            "lastname": "Laverde",
            "fullname": "Edilson Laverde",
            "email": "elaverde@hotmail.com",
            "department": "",
            "institution": "test",
            "idnumber": "123456",
            "firstaccess": 1718852813,
            "lastaccess": 1718860199,
            "auth": "manual",
            "suspended": false,
            "confirmed": true,
            "lang": "en",
            "theme": "",
            "timezone": "America/Bogota",
            "mailformat": 1,
            "trackforums": 0,
            "description": "<p>test</p>\r\n<p></p>",
            "descriptionformat": 1,
            "city": "fusa",
            "country": "CO",
            "profileimageurlsmall": "http://localhost:8200/user/pix.php/3/f2.jpg",
            "profileimageurl": "http://localhost:8200/user/pix.php/3/f1.jpg",
            "customfields": [
                {
                    "type": "text",
                    "value": "123456",
                    "displayvalue": "123456",
                    "name": "identificacion",
                    "shortname": "identificacion"
                }
            ]
        }
    ],
    "warnings": []
}
}
```
## Consideraciones
Asegúrate de tener permisos adecuados y un token válido para realizar solicitudes a la API personalizada en Moodle.
Los parámetros de búsqueda (search) y la función (wsfunction) pueden variar según la implementación específica de tu API personalizada.