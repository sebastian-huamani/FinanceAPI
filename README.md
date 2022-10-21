<h1 align="center"> Despues de un clone </h1>
<p align="center"> Despues de clonar un repositorio de laravel necesitaremos hacer algunos pasos adicionales para que todo valla bien. </p>



### Comandos:

```sh
- npm install
- composer install
- cp .env.example .env
- php artisan key:generate
```

Para generar la relacion entre la carpeta public/storage/ y la carpeta storage/app/public debes ejecutar el siguiente comando:
```sh
php artisan storage:link
```
y para finalizar actualizamos la base de datos:

```sh
- php artisan migrate --seed
```
<br>


### Modificar añgunos archivos en caso las imagenes no se descargan o se descargen y se eliminen:

el archivo 'vendor\fakerphp\faker\src\Faker\Provider\Image.php modificar'

```sh 
public const BASE_URL = 'https://placehold.jp';  // cambie la URL 
```

```sh
curl_setopt($ch, CURLOPT_FILE, $fp); //línea existente
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//nueva línea
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//nueva línea
$success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;//línea existente
```
