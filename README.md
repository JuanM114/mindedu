Instrucciones para descargar y ejecutar el proyecto:

1.	Ingresar al repositorio de GitHub y seleccionar la opción Code y despues Download ZIP.
2.	Extraer el archivo descargado y copiar la carpeta del proyecto dentro del directorio htdocs de XAMPP.
3.	Abrir el panel de control de XAMPP e iniciar los servicios Apache y MySQL.
4.	Ingresar a phpMyAdmin (http://localhost/phpmyadmin/) y crear una base de datos llamada “mindedu”.
5.	Importar el archivo mindedu.sql incluido en el proyecto para crear automáticamente las tablas y registros necesarios para el funcionamiento de la aplicación.
6.	Verificar la configuración de conexión a la base de datos en el archivo config/database.php, ajustando los parámetros según la configuración local de MySQL.
7.	Configurar una clave API válida de Gemini en el archivo config/openai.php.
Importante: Por motivos de seguridad, la clave API no se incluye en el repositorio. Debe reemplazarse manualmente por una clave válida antes de ejecutar las funcionalidades del asistente de inteligencia artificial.
8.	Abrir el navegador e ingresar a:
http://localhost/MindEdu/
(si se conserva el nombre original de la carpeta descargada).
9.	Para acceder al panel administrativo ingresar a:
http://localhost/MindEdu/admin/login_admin.php
Credenciales de administrador:
Correo: admin@admin.com
Contraseña: 123
