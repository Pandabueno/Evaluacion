# Evaluacion
Proyecto: Catálogo de Productos
Este proyecto es un sistema de gestión de productos desarrollado en Laravel y Docker.
________________________________________
#Requisitos Previos
1.	Docker y Docker Compose instalados.
2.	Git para clonar el repositorio.
________________________________________
#Pasos para Configurar y Ejecutar
#1. Clonar el Repositorio

En CMD o terminal favorita:
git clone https://github.com/Pandabueno/Evaluacion.git
cd Evaluacion

#2. Configurar el Archivo .env
Duplica el archivo .env.example y renómalo como .env. Configura los datos de conexión de tu base de datos, por ejemplo:
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel


#3. Levantar los Contenedores con Docker
Desde el directorio del proyecto, ejecuta:

En CMD o terminal favorita:
docker-compose up -d

Esto levantará:
•	Un contenedor para la aplicación Laravel.
•	Un servidor web Nginx.
•	Un contenedor MySQL para la base de datos.

#4. Instalar Dependencias
Accede al contenedor de la aplicación:

En CMD o terminal favorita:
docker exec -it laravel_app bash

Dentro del contenedor, ejecuta:

En CMD o terminal favorita:
composer install
npm install
npm run dev
php artisan key:generate
php artisan migrate
________________________________________
Cómo Usar la Aplicación
1.	Abre tu navegador y accede a http://localhost.
2.	Agrega, edita o elimina productos en la interfaz.
________________________________________

Problemas Comunes
1.	Contenedores No Corren: Verifica los logs:

En CMD o terminal favorita:
docker logs laravel_app

2.	Errores de Permisos: Asegúrate de dar permisos correctos:

En CMD o terminal favorita:
chmod -R 775 storage bootstrap/cache


