# CineNocturno con PHP y XAMPP

La versión PHP está en `php-app/`. Las páginas HTML originales se conservan como respaldo.

## Configuración local

1. Inicia Apache y MySQL desde XAMPP.
2. Importa `php-app/sql/cinenocturno.sql` en phpMyAdmin y selecciona la base `cinenocturno`.
3. En `C:\Users\Admin\Downloads\xampp\htdocs\CineNocturno`, inicia el proyecto desde `http://localhost/CineNocturno/`.
4. Abre `http://localhost/CineNocturno/php-app/crear_admin.php`, crea tu cuenta administradora y luego elimina ese instalador.
5. Inicia sesión y abre `http://localhost/CineNocturno/php-app/importar_catalogo.php` una vez para cargar las 32 películas del catálogo anterior. El importador evita duplicados; después elimínalo.
6. Administra las películas desde el enlace **Administrar**. El panel permite CRUD con PDO, validación de entradas, carga de imágenes y PDF, Summernote, AJAX y SweetAlert2.

La aplicación tiene cinco páginas PHP conectadas: Inicio, Catálogo, Fantasía oscura, Recomendaciones y Contacto. Las favoritas se guardan en el navegador. El formulario de Contacto valida y muestra una respuesta de demostración.

Para publicar el sistema completo se requiere un hosting que ejecute PHP y MySQL. Netlify sirve la versión estática, pero no ejecuta PHP.