# Evaluacion
Proyecto: Catálogo de Productos
Este proyecto es un sistema de gestión de productos desarrollado en Laravel y Docker.
________________________________________
Requisitos Previos
1.	Docker y Docker Compose instalados.
2.	Git para clonar el repositorio.
________________________________________
Pasos para Configurar y Ejecutar

1. Clonar el Repositorio

En CMD o terminal favorita:
git clone https://github.com/Pandabueno/Evaluacion.git
cd Evaluacion

2. Configurar el Archivo .env
Duplica el archivo .env.example y renobralo como .env. Configura los datos de conexión de tu base de datos, por ejemplo:

DB_CONNECTION=mysql

DB_HOST=db

DB_PORT=3306

DB_DATABASE=laravel

DB_USERNAME=laravel

DB_PASSWORD=laravel



4. Levantar los Contenedores con Docker
Desde el directorio del proyecto, ejecuta:

En CMD o terminal favorita:
docker-compose up -d

Esto levantará:

•	Un contenedor para la aplicación Laravel.

•	Un servidor web Nginx.

•	Un contenedor MySQL para la base de datos.


4. Instalar Dependencias
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
1.	Abre tu navegador y accede a http://localhost o http://localhost/products
2.	Agrega, edita o elimina productos en la interfaz.
________________________________________

Problemas Comunes
1.	Contenedores No Corren: Verifica los logs:

En CMD o terminal favorita:
docker logs laravel_app

2.	Errores de Permisos: Asegúrate de dar permisos correctos:

En CMD o terminal favorita:
chmod -R 775 storage bootstrap/cache



Características Clave
1. Listado de Productos
•	Actualización Dinámica: Los productos se cargan dinámicamente mediante AJAX, sin necesidad de recargar la página.
•	Paginación: El listado incluye paginación dinámica implementada con AJAX.
•	Ordenamiento: Al hacer clic en el encabezado de la columna "Fecha de Creación", los productos se ordenan de forma ascendente o descendente.
2. Agregar Productos
•	Modal de Creación: Al hacer clic en "Agregar Producto", se abre un modal con un formulario para ingresar los datos del producto:
o	Código de producto.
o	Nombre del producto.
o	Cantidad.
o	Precio.
o	Fecha de Ingreso.
o	Fecha de Vencimiento.
o	Fotografía.
•	Validaciones en JavaScript:
o	El código debe ser único, compuesto solo de letras y números (sin caracteres especiales).
o	El nombre del producto debe contener solo letras (sin caracteres especiales).
o	La fotografía debe estar en formato JPG o PNG, con un peso máximo de 1.5 MB.
o	La fecha de vencimiento debe ser mayor a la fecha de ingreso.
3. Editar Productos
•	Modal de Edición: Al hacer clic en el icono de editar, se abre un modal pre-rellenado con la información del producto.
•	Validaciones: Las mismas validaciones de la creación del producto se aplican aquí.
4. Eliminar Productos
•	Confirmación: Al hacer clic en el icono de eliminar, se muestra un modal de confirmación para evitar eliminaciones accidentales.
•	Actualización Dinámica: El listado se actualiza dinámicamente tras eliminar un producto.
________________________________________
Archivos y Carpetas Relevantes
Frontend
•	resources/views:
o	Contiene las vistas Blade de Laravel 8.
•	public/css/style.css:
o	Estilos personalizados.
•	public/js/app.js:
o	Lógica de AJAX y validaciones en JavaScript.
Backend
•	app/Http/Controllers/ProductController.php:
o	Controlador para gestionar las operaciones CRUD.
•	routes/web.php:
o	Rutas principales del proyecto.
•	database/migrations:
o	Migraciones para la base de datos.
________________________________________
Validaciones
1. JavaScript
•	Código de Producto: Letras y números solamente.
•	Nombre: Solo letras.
•	Cantidad: Mayor a 0.
•	Precio: Mayor o igual a 0.
•	Fotografía: Formato JPG o PNG; máximo 1.5 MB.
•	Fecha: Formato DD/MM/YYYY; vencimiento mayor a ingreso.
2. Backend (Laravel)
•	Código de Producto: Validación única en la base de datos.
•	Requerimientos: Todos los campos son obligatorios.
•	Archivos: Fotografía valida (máximo 1.5 MB, extensión correcta).



