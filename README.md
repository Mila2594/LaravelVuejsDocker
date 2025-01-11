# 🐳 Aplicación Dockerizada - Cliente y Servidor

Este proyecto es una aplicación cliente-servidor desarrollada utilizando **Laravel** en el backend y **Vue.js** en el frontend. Cada servicio se ejecuta en su propio contenedor Docker, garantizando un entorno de desarrollo aislado, escalable y fácil de gestionar.

---

## 📝 Descripción del proyecto
- **Frontend**: Una aplicación web basada en **Vue.js**, servida con **NGINX** para entregar los archivos estáticos.
- **Backend**: Una API RESTful construida con **Laravel**, ejecutándose en un contenedor Docker optimizado.
- **Infraestructura**: Se utiliza **Docker Compose** para orquestar los servicios y facilitar la configuración y despliegue.

---
## 🔧 Tecnologías utilizadas

### Frontend
- **Vue.js**: Framework para construir interfaces de usuario.
- **Node.js**: (v18) Para gestionar dependencias y herramientas de compilación.
- **NGINX**: Servidor web para distribuir los archivos estáticos.

### Backend
- **PHP**: (v8.2) Lenguaje de programación principal.
- **Laravel**: Framework PHP para desarrollo web.
- **Composer**: Gestor de dependencias para PHP.
  
### Docker
- **Docker**: Para la contenedorización de los servicios.
- **Docker Compose**: Para la orquestación de múltiples contenedores.

---

## 📂 Estructura del proyecto
- `backend/`: Contiene el código y la configuración del servicio backend.
- `frontend/`: Contiene el código y la configuración del servicio frontend.
- `docker-compose.yml`: Archivo de configuración para orquestar los servicios con Docker Compose.

---

## ⚙️ Configuración y ejecución

### 1️⃣ Requisitos previos
- **Docker**: [Instalar Docker](https://docs.docker.com/get-docker/)
- **Docker Compose**: [Instalar Docker Compose](https://docs.docker.com/compose/install/)

#### Antes de comenzar
Asegúrate de que Docker está instalado y activo en tu máquina:
- **Windows/macOS**: Verifica que Docker Desktop esté ejecutándose.
- **Linux**: Comprueba el estado de Docker con:
  ```bash
  sudo systemctl status docker

### 2️⃣ Clonar el repositorio

Clonar el repositorio: Clona el repositorio a tu máquina local, utiliza los siguiente comandos:

- git clone <URL_DEL_REPOSITORIO>
- cd <directorio_del_proyecto>

### 3️⃣ Iniciar los servicios
Para iniciar los servicios del frontend y backend, utiliza Docker Compose. Asegúrate de estar en el directorio raíz del proyecto donde se encuentra el archivo docker-compose.yml.

- docker-compose up -d

Esto construirá las imágenes y levantará los contenedores definidos en el archivo docker-compose.yml.

### 🌐 Acceso a la Aplicación  

- El frontend estará disponible en http://localhost.
- El backend estará disponible en http://localhost:8000.

## 🛠️ Comandos utiles

Detener contenedores: Para detener los contenedores sin eliminar los volúmenes:

- docker-compose down

Eliminar contenedores, redes y volúmenes:

- docker-compose down -v


## 📜 Licencia
Este proyecto está bajo la Licencia **MIT**. Consulta el archivo LICENSE para más detalles.

---


✨ ¡Gracias por visitar este repositorio! 😊
