# Technical-test Backend

Para ver el proyecto es necesario descargar y arrancar 2 contenedores dockers. El primero contiene la base de datos, es un docker mysql. Y el segundo contiene un apache con PHP. Éste tiene la aplicación PHP donde utilizaremos la API. Ésta aplicación PHP está conectada a la Base de datos del otro contenedor docker.

# Dockers

Una vez hemos clonado el proyecto GIT, podemos descargar las imágenes de los docker para vincular los directorios.
Descargamos primero el docker mysql:
>docker pull adriav/alma-mysql:5.7

Una vez tenemos la imagen descargada, podemos correr el docker. -e para crear la pwd del root "adri". -d para que corra en segundo plano.
>docker run -p 3306:3306 --user 501:20 --name adriav/alma-mysql -v "$PWD/db":/var/lib/mysql -e MYSQL_ROOT_PASSWORD=adri -d adriav/alma-mysql:5.7


el --user lo obtenemos de hacer "ls -lnd db" (db es el directorio local), obtenemos lo siguiente (en mi caso):
>drwxr-xr-x@ 2 501  20  64  5 dic 17:05 db/

y de aqui nos interesa el 501:20

Una vez está corriendo el contenedor de mysql, descargamos y arrancamos el de php:
>docker pull adriav/alma-php:latest

Ahora lo arrancamos, con el siguiente comando: ponemos el -p para vincular nuestro puerto 80 con el puerto 80 del docker. Ponemos el --link para linkarlo con el docker del mysql
>docker run -p 80:80 --name adriav/alma-php -v "$PWD/api":/var/www/html --link adriav/alma-mysql -d adriav/alma-php

## API

Una vez están corriendo ambos dockers, podemos acceder al http://localhost/ y si está todo bien, responderá la aplicación PHP de la API.

Para esta prueba, lo ideal es utilizar Postman para hacer las peticiones.

## Auth login

Obligatorio iniciar sesión para hacer cualquier acción de la API.
**POST** localhost/auth
Datos a pasar por post:
>{
    "usuario" : "Adri",   
    "password" : "123456"
}

RESPUESTA CORRECTA:
>"Bienvenido Adri"

## Pacientes

Listar todos los pacientes del usuario logeado
**GET** localhost/pacientes
Respuesta correcta:

    [
	    {
		    "id":  "1",
		    "name":  "Pac1",
		    "surname":  "sur1",
		    "birthdate":  "1990-12-15",
		    "id_doctor":  "1"
	    },
	    {
		    "id":  "4",
		    "name":  "paci2",
		    "surname":  "suri2",
		    "birthdate":  null,
		    "id_doctor":  "1"
	    },
    ]

Obtener información de un paciente
**GET** localhost/pacientes/?id=$idPaciente
ejemplo:  localhost/pacientes/?id=1

    [
	    {
		    "id":  "1",
		    "name":  "Pac1",
		    "surname":  "sur1",
		    "birthdate":  "1990-12-15",
		    "id_doctor":  "1"
	    }
    ]

**POST** localhost/pacientes
Entrada correcta: 

    {
        "name" : "Pac12",
        "surname" : "sur12",
        "birthdate" : "1990-12-06"
    }

Resultado:

    {
    
	    "status":  "ok",
    
		"result":  {
    
		    "pacienteId":  11
    
	    }
    
    }


**PUT** /pacientes
Entrada correcta:

    {
    "id" : "1",
    "name" : "Pac10",
    "surname" : "sur10",
    "birthdate" : "1990-12-15"
    }

Respuesta:

    {"status":"ok","result":{"filasModificadas":1}}

**Nota:** Para que modifique correctamente, los datos entrados deben ser diferentes a los del paciente actual. Si quieres hacer un put con los mismos datos que están en la bd, mostrará un error, porque no tiene sentido que se ejecute esa acción.

**DELETE** /pacientes
Body:

    {
        "id" : "9",
    }

Respuesta correcta:

    {"status":"ok","result":{"registrosEliminados":1}}

## Estructura Base de Datos

Base de datos: hospital
Tablas:
	
- doctores (id, name, password)

- pacientes (id, name, surname, birthdate, id_doctor)

- registro (id, doctor, paciente, action, timestamp)
