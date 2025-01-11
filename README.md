
# Proyecto: Aplicación Dockerizada

Este proyecto es una aplicación dividida en dos servicios, Frontend y Backend, cada uno corriendo en su propio contenedor Docker. El frontend está basado en una aplicación web que usa NGINX como servidor, y el backend está construido con Laravel.

## Requisitos
- Docker: Debes tener instalado Docker en tu máquina para poder construir y ejecutar los contenedores.

- Docker Compose: Se utiliza Docker Compose para orquestar los servicios.

## Tecnologías utilizadas

### Frontend:
- Node.js (v18)
- NGINX (para servir los archivos estáticos compilados)

### Backend:
- PHP (v8.2)
- Laravel (Framework PHP)
- Composer (gestor de dependencias PHP)

### Docker:
- Docker y Docker Compose para la contenedorización y orquestación.

## Configuración del Entorno

### Backend

El backend está configurado en el directorio **"backend"**. Utiliza un Dockerfile para construir la imagen del contenedor.

### Frontend

El frontend está configurado en el directorio **"frontend"**. Utiliza un Dockerfile para construir la imagen del contenedor.


## Configuración y ejecución

Clonar el repositorio: Clona el repositorio a tu máquina local, utiliza los siguiente comandos:

- git clone <URL_DEL_REPOSITORIO>
- cd <directorio_del_proyecto>

## Antes de comenzar

Antes de levantar los contenedores, asegúrate de que se cumplan los siguientes requisitos:Antes de levantar los contenedores, asegúrate de que Docker esté correctamente instalado y en ejecución en tu máquina.

#### **Verifica que Docker esté activo:**

- Windows y macOS:
Comprueba que Docker Desktop esté instalado y en ejecución. Puedes buscar el icono de Docker en la barra de tareas (Windows) o en el menú superior (macOS). Si no está activo, abre Docker Desktop antes de continuar.

- Linux (por ejemplo, Ubuntu):
Asegúrate de que el servicio Docker esté en funcionamiento. Puedes verificarlo ejecutando el comando:
  * sudo systemctl status docker
  
  Si el servicio no está activo, inícialo con:
  * sudo systemctl start docker

  
#### **Requisitos de software**
  - Docker: Consulta la [Guía de instalación de Docker](https://docs.docker.com/get-docker/).
  - Docker Compose: Consulta la [Guía de instalación de Docker Compose](https://docs.docker.com/compose/install/).


## Uso
Para iniciar los servicios del frontend y backend, utiliza Docker Compose. Asegúrate de estar en el directorio raíz del proyecto donde se encuentra el archivo docker-compose.yml.


- docker-compose up -d

Esto construirá las imágenes y levantará los contenedores definidos en el archivo docker-compose.yml.

### Acceso a la Aplicación

- El frontend estará disponible en http://localhost.
- El backend estará disponible en http://localhost:8000.

## Comandos utiles

Detener contenedores: Para detener los contenedores sin eliminar los volúmenes:

- docker-compose down

Eliminar contenedores, redes y volúmenes:

- docker-compose down -v


## Licencia
Este proyecto está bajo la Licencia **MIT**. Consulta el archivo LICENSE para más detalles.
