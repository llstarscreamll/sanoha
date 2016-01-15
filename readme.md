# Sanoha Web System

Sanoha Web System es un aplicación construida con el framework [Laravel 5.1], la cual está programada según los requerimientos de los ingenieros y supervisores del área técnica de la empresa [Sanoha LTDA] en los procesos de gestión de reporte de labores mineras, novedades y órdenes de trabajo.

## Contenido
- [Instalación](#instalación)
    - [Clonar repositorio](#clonar-repositorio)
    - [Configurar permisos de carpetas](#permisos-de-carpetas)
    - [Instalar dependencias Backend](#instalación-de-dependencias-backend)
    - [Instalar dependencias Frontend](#instalación-de-dependencias-frontend)
    - [Configurar variables de entorno](#variables-de-entorno)
    - [Configurar base de datos](#configuración-de-base-de-datos)
        - [Migraciones](#migraciones)
        - [Sin backup de datos](#seeders)
            - [Seeders](#seeders)
            - [Creación de usuario admin](#usuario-administrador)
        - [Con backup de datos, restaurando datos](#restaurar-base-de-datos)
    - [Configuración del Servidor Web](#configuración-del-servidor-web)
        - [Nginex](#nginex)
        - [Apache](#apache)
    - [Programador de tareas](#programador-de-tareas)

## Instalación
Para la instalación se supone un servidor con GNU/Linux [Ubuntu Server] 14.04 x64 bits debidamente configurado con los siguientes paquetes:

- [LAMP] o [LEMP] stack, con:
    - PHP >= 5.5.9
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension
- [Composer]
- [Git]
- [Node.js]
    - [Bower]

### Clonar Repositorio
Para la instalación del proyecto se debe clonar este repositorio con [Git], lo haremos en una carpeta llamada `Test` en `/var/www/`:

```bash
$ cd /var/www
$ git clone https://github.com/llstarscreamll/sanoha.git Test
```

Leer más sobre el [uso de Git].

### Permisos de Carpetas
Modificamos los permisos de la carpeta `storage`:


```bash
$ sudo chmod -R 775 storage
```

Leer más sobre [permisos en Ubuntu].

### Instalación de Dependencias Backend
Instalamos las dependencias con [Composer] entrando en la carpeta del proyecto:

```bash
$ cd Test
$ composer install
```

Leer más sobre [uso de Composer].

### Instalación de Dependencias Frontend
Instalamos las dependencias con [bower] estando en la carpeta del proyecto:

```bash
    $ bower install
```

Leer más sobre [bower].

### Variables de Entorno
Se debe configurar el archivo `.env` con las variables requeridas por la aplicación, para esto podemos seguir el archivo de ejemplo `.env.example`:

```bash
APP_ENV=local
APP_DEBUG=true
APP_KEY=generate_key_with_artisan

DB_DEFAULT=mysql
DB_HOST=localhost
DB_DATABASE=dbName
DB_USERNAME=dbUser
DB_PASSWORD=dbPassword
DB_BACKUP_FOLDER=path/to/backup/destination/folder/

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=123456
MAIL_USERNAME=user@domain.com
MAIL_PASSWORD=mailPassword
```

Los nombres de las variables son descriptivos, se debe asignar el correspondiente valor a cada una.

Leer más sobre [configuración de variables de entorno] en Laravel.

### Configuración de Base de Datos
Luego de ejecutar las [migraciones](#migraciones), se debe elegir uno de dos caminos para terminar de configurar la base de datos:

- [Sin backup](#seeders), creando sólamente los datos base de la aplicación, como los permisos, roles, centros de costo, subcentros de costo, áreas, cargos, tipos de labores mineras y tipos de novedades, como no hay datos de usuarios se debe [crear el usuario administrador](#usuario-administrador) manualmente.
- [Con backup](#restaurar-base-de-datos), importando datos de una instalación anterior del sistema al motor de base de datos mysql, con los datos que hayan sido creados en esa instalación como información de empleados, reportes de labores mineras, reportes de novedades, órdenes de trabajo, etc.

#### Migraciones
Corremos las [migraciones] de la base de datos:

```
    $ php artisan migrate
```

Leer más sobre [migraciones] en Laravel.

#### Seeders
Si no tenemos un backup de una base de datos de una instalación previa del sistema, corremos los [seeders] de la base de datos con los datos base de la aplicación:

```bash
    $ php artisan db:seed
```

Leer más sobre los [seeders] en Laravel.

> Si existe un backup de la base de datos de una instalación anterior, omitir este paso e ir al paso [Restaurar Base de Datos](#restaurar-base-de-datos).

#### Usuario Administrador
Creamos el usuario administrador del sistema con [tinker]:

```bash
    $ php artisan tinker
    Psy Shell v0.6.1 (PHP 5.5.9-1ubuntu4.13 — cli) by Justin Hileman
    >>> \sanoha\Models\User::create(
    ... [
    ...     'name'          =>  'Travis',
    ...     'lastname'      =>  'Orbin',
    ...     'email'         =>  'travis.orbin@example.com',
    ...     'password'      =>  bcrypt('123456789'),
    ...     'activated'     =>  1,
    ...     'area_id'       =>  null,
    ...     'area_chief'    =>  false,
    ...     'created_at'    =>  date('Y-m-d H:i:s'),
    ...     'updated_at'    =>  date('Y-m-d H:i:s'),
    ...     'deleted_at'    =>  null
    ... ])->attachRole(2); # 2 es el id del rol admin
    => null
    >>> exit
```

Luego de la configuración del servidor web puedes añadir centros de costo, empleados o área al usuario a través de la interfaz de usuario.

Leer más sobre [tinker] en Laravel.

> Si existe un backup de la base de datos de una instalación anterior, omitir este paso e ir al paso [Restaurar Base de Datos](#restaurar-base-de-datos).

#### Restaurar Base de Datos

Si existe un backup de la base de datos de una previa instalación del sistema, se debe hacer el proceso de [restaurar base de datos en mysql] a una tabla previamente configurada, tener en cuenta que si el archivo fue generado automáticamente por el sistema, se debe descomprimir primero.

Subimos el backup que tenemos en el disco local, a la carpeta deseada en el disco del servidor remoto mediante el comando [scp]:

```bash
    $ scp backupFile user@123.123.1.123:/home/user/downloads
```

Donde `backupFile` es el archivo que tiene el backup de la base de datos, `user` es el usuario con el que inicia sesión en el servidor con ip `123.123.1.123` y `:/homes/user/downloads` la ruta de la carpeta de `usuario` del servidor remoto donde queremos copiar el archivo.

Ahora nos iniciamos sesión en le servidor remoto:

```bash
    $ ssh user@123.123.1.123
```

Se pedirá digitar la contraseña de dicho usuario, luego vamos a la carpeta donde copiamos el archivo y restauramos los datos en la base de datos `Test`:

```bash
    $ cd /home/user/downloads
    $ mysql -u db_user -p Test < backupFile
```

Dónde `Test` es el nombre de la base de datos, `db_user` es el usuario de la base de datos y `backupFile` es el archivo del backup de la base de datos que copiamos en el servidor remoto. Con esto ya deben estar listos los datos de la aplicación en una instalación anterior.

Leer más sobre como [restaurar base de datos en mysql].

### Configuración del Servidor Web

#### Nginex
Ahora configuramos la carpeta `public`, para el caso de servidores [LEMP]:

```bash
    $ sudo nano /etc/nginx/sites-available/default
```

Debemos dejar el archivo de la siguiente forma:

```bash
    server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    root /var/www/Test/public;
    index index.php index.html index.htm;

    server_name server_domain_or_IP;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

Guardamos y cerramos el archivo con `CTRL + x`, y reiniciar nginex.

```bash
    $ sudo service nginx restart
```

Aprende más sobre cómo [configurar host virtual en Nginx].

#### Apache
Para el caso de servidores [LAMP]:

```bash
    $ sudo nano ../etc/apache2/sites-available/000-default
```

Añadir las siguientes líneas al archivo:

```bash
DocumentRoot /var/www/Test/public
<Directory /var/www/Test/public>
 Options Indexes FollowSymLinks MultiViews
 AllowOverride All
 Order allow,deny
 allow from all
</Directory>
```

Guardamos y cerramos el archivo con `CTRL + x`, y reiniciar apache:

```bash
    $ sudo service apache2 reload
```

Aprende más sobre cómo [configurar host virtual en apache].

### Programador de Tareas
Para configurar el programador de tareas digitamos el siguiente comando con permisos de superusuario:

```bash
    $ sudo crontab -e
```

 Y añadimos las siguiente linea al final del archivo:
 
 ```bash
    * * * * * php /var/www/Test/artisan schedule:run >> /dev/null 2>&1
 ```
 
 Donde `/var/www/Test/artisan` es la ruta a la carpeta donde clonamos el proyecto, guardamos y cerramos el archivo con `Ctrl + x` e iniciamos el servicio `cron` con el siguiente comando:
 
 ```bash
    $ sudo cron start
 ```
 
 Aprende más sobre las [tareas programadas] del framework.

[configuración de variables de entorno]: <https://laravel.com/docs/5.1/installation#environment-configuration>
[configurar host virtual en Nginx]: <https://www.digitalocean.com/community/tutorials/how-to-set-up-nginx-virtual-hosts-server-blocks-on-ubuntu-12-04-lts--3>
[configurar host virtual en apache]: <https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts>
[Ubuntu Server]: <http://www.ubuntu.com/download/server>
[permisos en Ubuntu]: <https://help.ubuntu.com/community/FilePermissions>
[Laravel 5.1]: <https://laravel.com>
[tareas programadas]: <https://laravel.com/docs/5.1/scheduling>
[migraciones]: <https://laravel.com/docs/5.1/migrations>
[seeders]: <https://laravel.com/docs/5.1/seeding>
[tinker]: <http://laravel-recipes.com/recipes/280/interacting-with-your-application>
[Composer]: <https://getcomposer.org>
[uso de Composer]: <https://getcomposer.org/doc/00-intro.md>
[Git]: <https://git-scm.com/downloads>
[uso de Git]: <https://git-scm.com/doc>
[LEMP]: <https://www.digitalocean.com/community/tutorial_series/introduction-to-nginx-and-lemp-on-ubuntu-14-04>
[LAMP]: <https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu>
[Node.js]: <https://nodejs.org/en/>
[Bower]: <http://bower.io/>
[restaurar base de datos en mysql]: <http://webcheatsheet.com/sql/mysql_backup_restore.php>
[scp]: <https://kb.iu.edu/d/agye>
[Sanoha LTDA]:<http://sanoha.com/>
