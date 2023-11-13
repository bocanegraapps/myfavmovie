My Fav Movie Symfony FullStack Project
======================================

Esto es una aplicación creada en symfony para el track de PHP de Hack a Boss impartido por Guillermo Maquieira para EMAIS para demostrar los conceptos adquiridos en PHP durante ese track para la certificación del mismo.

¿Qué requerimientos necesitas para hacer funcionar el proyecto?
---------------------------------------------------------------

  * PHP 8.2;
  * Extensión PDO-Mysql activada en php.ini
  * Extensión CURL activada en php.ini
  * Se incluye un fichero php.ini que se ha usado con el proyecto a modo de ejemplo para configurar vuestro entorno php, está en la carpeta ETC
  * Composer
  * Mysql Community Server

Preparar el entorno de desarrollo para hacer funcionar el proyecto
------------------------------------------------------------------

* Bajar toda la distribución PHP 8.2 correspondiente a tu sistema operativo.
* Instalar composer correspondiente a tu sistema operativo.
* Clonar el respositorio en una carpeta, entrar en esa carpeta y hacer un composer update para instalar las dependencias necesarias.
* Instalar MySql community server correspondiente a tu sistema operativo.
* Con una herramienta adecuada, abrir el fichero database.sql (dentro de la carpeta etc) con el fin de crear la base de datos local necesaria.
* Configurar el fichero .env con los datos necesarios para la conexión a la base de datos MySQL (comentar cualquier otro controlador de BD)
    * DATABASE_URL="mysql://{usuarioBd}:{passBd}@127.0.0.1:3306/myfavmovie"
* Instalar SYMFONY CLI 
* Lanzar el servidor de pruebas con SYMFONY SERVE, si no quieres instalar SYMFONY CLI, usar el comando $ php -S localhost:8000 -t public/
* Url 127.0.0.1:8000/es para probar la aplicación.

El proyecto ya viene con una clave API de TMDB para funcionar.

Funcionamiento
--------------
* Hacer click en el botón en el centro de la pantalla para entrar a la lista de películas favoritas (vacía en un primer momento).
* Usar la lupa de búsqueda en la parte superior derecha de la pantalla para acceder al formulario de búsqueda de películas, escribir un título y pulsar intro para ver resultados
* En la lista haz click en "añadir" a aquella película que quieras añadir a tu lista de favoritos.
* En la lista de favoritos podrás valorar tu película favorita o eliminarla de la lista

Enlaces útiles
--------------
* https://symfony.com/doc/current/best_practices.html
* https://symfony.com/doc/current/setup.html#technical-requirements
* https://symfony.com/doc/current/setup/web_server_configuration.html
* https://symfony.com/download
* https://symfony.com/book
* https://getcomposer.org/
