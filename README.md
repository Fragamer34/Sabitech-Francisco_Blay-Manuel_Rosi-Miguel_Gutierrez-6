# Sabitech-Francisco_Blay-Manuel_Rosi-Miguel_Gutierrez-6

1. Antes de empezar: programas necesarios 

Instala estos programas si aún no los tienes: 

Docker Desktop: ejecuta el backend y MySQL en contenedores. 

Git: descarga el proyecto desde GitHub. 

Node.js (incluye npm): ejecuta el frontend. 

Un navegador web: Google Chrome, Microsoft Edge o Firefox. 

      5.PHP, 

      6.Composer  Docker. 

2. Paso 1: descargar el proyecto 

Abre PowerShell. 

Ve a la carpeta donde quieras guardar el proyecto, por ejemplo: 

cd C:\Users\Usuario\Documents 

Descarga el repositorio (copia la URL HTTPS con el botón Code de GitHub): 

git clone URL_DEL_REPOSITORIO 

Entra en la carpeta del proyecto: 

cd NOMBRE_DEL_REPOSITORIO 

Comprueba que existen las carpetas Backend y Frontend. 

3. Paso 2: revisar la configuración 

Entra en Backend/app. 

Comprueba que existen estos archivos: 

docker-compose.yml (o compose.yaml) 

.env 

Abre .env y revisa los datos de conexión a la base de datos: 

DB_CONNECTION=mysql 
DB_HOST=db 
DB_PORT=3306 
DB_DATABASE=sabitech 
DB_USERNAME=sabitech_user 
DB_PASSWORD=Aula_Sabiteck_2026 

4. Paso 3: iniciar el backend y la base de datos (Terminal 1) 

Todos los comandos de esta sección y de las dos siguientes se ejecutan en la primera terminal, desde Backend/app. 

Accede a la carpeta: 

cd NOMBRE_DEL_REPOSITORIO\Backend\app 

Verifica que Docker y Docker Compose funcionan: 

docker --version 
docker compose version 

Valida la configuración: 

docker compose config 

Construye las imágenes e inicia los contenedores (la primera vez tarda varios minutos): 

docker compose up -d --build 

Comprueba que todo está en marcha: 

docker compose ps 

Deben aparecer dos servicios con estado Up o running: 

 

Servicio 

Función 

web 

Ejecuta Laravel mediante Apache 

db 

Ejecuta MySQL 8.4 

5. Paso 4: preparar Laravel (Terminal 1) 

Limpia las cachés de Laravel: 

docker compose exec web php artisan optimize:clear 

Comprueba que Laravel se conecta a MySQL: 

docker compose exec web php artisan migrate:status 

 

6. Paso 5: crear las tablas (Terminal 1) 

Ejecuta las migraciones: 

docker compose exec web php artisan migrate 

Espera a que termine y verifica el resultado: 

docker compose exec web php artisan migrate:status 

 

 

Con esto se crean las tablas de usuarios, charlas, ponentes, reservas y asistencias. 

7. Paso 6: cargar las charlas de ejemplo (Terminal 1) 

Ejecuta el Seeder para que la aplicación no arranque vacía desde la carpeta de fronted: 

docker compose exec web php artisan db:seed --class=CharlasDemoseeder 

 

Notas: 

Hazlo siempre después de las migraciones. 

8. Paso 7: iniciar el frontend (Terminal 2) 

Abre una segunda terminal de PowerShell y deja la primera abierta. 

Entra en la carpeta del frontend: 

cd NOMBRE_DEL_REPOSITORIO\Frontend 

Comprueba Node.js y npm: 

node --version 
npm --version 

Instala las dependencias (solo la primera vez): 

npm install 

Arranca el servidor de desarrollo: 

npm run dev 

Vite mostrará la dirección del frontend, normalmente http://localhost:5173. 

 

 

No cierres esta terminal mientras uses la aplicación: al cerrarla se detiene el servidor. 

9. Paso 8: abrir y probar la aplicación 

Abre el navegador: 

Aplicación Sabitech (frontend): http://localhost:5173 

Backend Laravel: http://localhost:8080 

 

Para poder consultar en base de datos que se introducen los datos :  docker compose exec db mysql -u sabitech_user -p sabitech .(usuario y contaseña aparacen  en backend\app\compose.yaml) 

10. Lista de comprobación final 

Docker Desktop está instalado y en funcionamiento. 

Git y Node.js (con npm) están instalados. 

El repositorio se ha descargado y existen Backend y Frontend. 

Existe el archivo .env en Backend/app con los datos correctos. 

Los contenedores web y db están en estado Up. 

Laravel se conecta con MySQL (migrate:status funciona). 

Las migraciones se han ejecutado correctamente. 

El Seeder ha creado las charlas de ejemplo. 

Las dependencias del frontend están instaladas. 

Vite se inicia y se abre http://localhost:5173. 

Puedo registrarme, ver charlas, reservar y confirmar asistencia. 

11. Uso diario después del primer despliegue 

El primer despliegue solo se hace una vez. Para los siguientes usos basta con: 

Encender 

Terminal 1, en Backend/app: docker compose start 

Terminal 2, en Frontend: npm run dev 

Apagar 

Terminal 2: pulsa Ctrl + C. 

Terminal 1: docker compose stop (conserva los datos). 

Otras operaciones 

docker compose down detiene y elimina los contenedores, pero conserva los datos de MySQL. 

Nunca uses docker compose down -v salvo que quieras borrar la base de datos y perder toda la información. 