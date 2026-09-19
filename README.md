# TechStore

Sistema web para la gestión de artículos tecnológicos.

## Tecnologías

- PHP 8.5
- MySQL
- MySQLi
- Bootstrap 5.3
- HTML5
- CSS3

## Funcionalidades

- Registro de usuarios
- Inicio de sesión
- Cierre de sesión
- Gestión de sesiones
- CRUD de artículos
- Validación de datos
- Consultas preparadas
- Protección contra SQL Injection
- Escape de datos contra XSS

## Clonar la aplicación

Clonar la aplicación desde Github

```
https://github.com/marinosu/techstore.git
```
 
Instalar composer desde la siguiente pagina web

```
https://getcomposer.org/
```

Posicionarse en la carpeta raiz del app y seguido instalar las dependencias con el comando

```
composer install
```

## Crear la base de datos

Crear la base de datos local en mysql

```
techstore
```

crear las tablas en la base de datos, que contiene en el proyecto

```
usuarios y articulos
```

## Despliegue de la App

Servidor web interno de php, disponible desde la versión 5.4.0

Ejecutar el comando 

```
php -S localhost:PUERTO
```

por ejemplo el puerto 8086

```
http://localhost:8086/login.php
```

Railway (Plataforma en la nube)

Al generar el dominio en la plataforma

```
https://techstore-production-0ddb.up.railway.app/login.php
```