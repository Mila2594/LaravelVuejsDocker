
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

## Estructura del Proyecto

```File
my-project/ 
├── backend/ 
│ ├── app/ 
│ ├── bootstrap/ 
│ ├── config/ 
│ ├── database/ 
│ ├── public/ 
│ ├── resources/ 
│ ├── routes/ 
│ ├── storage/ 
│ ├── tests/ 
│ ├── .env 
│ ├── composer.json 
│ ├── composer.lock 
│ ├── DockerFile 
│ └── artisan 
├── frontend/ 
│ ├── public/ 
│ ├── src/ 
│ ├── .env 
│ ├── package.json 
│ ├── package-lock.json 
│ ├── DockerFile 
│ └── nginx.conf 
├── docker-compose.yml 
└── README.md
```

## Configuración del Entorno

### Backend

El backend está configurado en el directorio **"backend"**. Utiliza un Dockerfile para construir la imagen del contenedor.

#### Dockerfile del Backend

```Dockerfile
# Usar una imagen base con PHP y Composer
FROM php:8.2-cli

# Instalar extensiones requeridas
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar los archivos del proyecto
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Crear el enlace simbólico
RUN php artisan storage:link

# Configurar permisos
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
&& chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto donde correrá la aplicación
EXPOSE 8000

# Comando para iniciar el servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

```

### Frontend

El frontend está configurado en el directorio **"frontend"**. Utiliza un Dockerfile para construir la imagen del contenedor.

#### Dockerfile del Frontend

```Dockerfile
# Etapa de compilación
FROM node:18 as build

# Configuración del directorio de trabajo
WORKDIR /app

# Copiar archivos necesarios
COPY package*.json ./
COPY . .

# Instalar dependencias y compilar el proyecto
RUN npm install
RUN npm run build

# Etapa final
FROM nginx:stable-alpine

# Configurar NGINX
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copiar archivos compilados al directorio de NGINX
COPY --from=build /app/dist /usr/share/nginx/html

# Exponer el puerto 80
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]


```

## Configuración y ejecución

Clonar el repositorio: Clona el repositorio a tu máquina local, utiliza los siguiente comandos:

- git clone <URL_DEL_REPOSITORIO>
- cd <directorio_del_proyecto>

## Antes de comenzar

Antes de levantar los contenedores, asegúrate de que Docker esté correctamente instalado y en ejecución en tu máquina.

###**1. Verifica que Docker esté activo:**

    - Windows y macOS:Comprueba que Docker Desktop esté instalado y en ejecución. Puedes buscar el icono de Docker en la barra de tareas (Windows) o en el menú superior (macOS). Si no está activo, abre Docker Desktop antes de continuar.
    - Linux (por ejemplo, Ubuntu):Asegúrate de que el servicio Docker esté en funcionamiento. Puedes verificarlo ejecutando el comando:
        * sudo systemctl status docker
        * sudo systemctl start docker

  ###**2. Requisitos de software**
    - Docker: Consulta la Guía de instalación de Docker.
    - Docker Compose: Consulta la Guía de instalación de Docker Compose.


## Uso
Para iniciar los servicios del frontend y backend, utiliza Docker Compose. Asegúrate de estar en el directorio raíz del proyecto donde se encuentra el archivo docker-compose.yml.


- docker-compose up -d

Esto construirá las imágenes y levantará los contenedores definidos en el archivo docker-compose.yml.

### docker-compose.yml

```File
version: "3.8"

services:
  frontend:
    build:
      context: ./frontend
      dockerfile: DockerFile
    ports:
      - "80:80"
    networks:
      - app_network

  backend:
    build:
      context: ./backend
      dockerfile: DockerFile
    ports:
      - "8000:8000"
    networks:
      - app_network
    volumes:
      - ./backend/storage:/var/www/storage
      - ./backend/bootstrap/cache:/var/www/bootstrap/cache

networks:
  app_network:
    driver: bridge

```

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
