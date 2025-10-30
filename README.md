# 🚀 Proyecto Fullstack de Gestión de Tareas

Aplicación Fullstack desarrollada con **Laravel 11**, **Vue.js 3**, **JWT Authentication** y **PostgreSQL**, que permite gestionar proyectos y tareas con autenticación segura, CRUD completo y separación de frontend y backend.

---

## 🧩 Tecnologías utilizadas

### 🖥️ Backend
- **Framework:** Laravel 11  
- **Autenticación:** JSON Web Tokens (JWT)  
- **Base de datos:** PostgreSQL  
- **ORM:** Eloquent  
- **Servidor local:** Artisan (`php artisan serve`)

### 💻 Frontend
- **Framework:** Vue.js 3 (Composition API)  
- **Compilador:** Vite  
- **Router:** Vue Router  
- **Estado global:** Pinia  
- **Estilos:** Tailwind / CSS personalizado  

---

## ⚙️ Estructura del proyecto

📦 Proyecto-Fullstack
├── backend/ # API Laravel
│ ├── app/
│ ├── database/
│ │ └── migrations/
│ ├── routes/
│ │ └── api.php
│ └── .env
│
└── frontend/ # Interfaz Vue 3
├── src/
│ ├── views/
│ ├── router/
│ │ └── index.js
│ └── stores/
└── vite.config.js

### 📥 Configurar el Backend (Laravel 11)

Instalar dependencias

cd backend
composer install


### ⚙️Configurar variables de entorno

Copia el archivo .env.example


### Generar la clave de aplicación:

php artisan key:generate


### Generar la clave JWT:

php artisan jwt:secret


### 🧱 Migrar la base de datos

php artisan migrate


### ▶️ Iniciar el servidor backend

php artisan serve



### 📥 Configurar el Frontend (Vue 3 + Vite)

Instalar dependencias

cd frontend
npm install


### ▶️ Ejecutar servidor de desarrollo

npm run dev



### 📡 API Endpoints

🔐 Autenticación
| Método | Endpoint    | Descripción         | Autenticación |
| ------ | ----------- | ------------------- | ------------- |
| POST   | `/register` | Registro de usuario | ❌             |
| POST   | `/login`    | Inicio de sesión    | ❌             |
| GET    | `/profile`  | Ver perfil actual   | ✅ JWT         |
| GET    | `/logout`   | Cerrar sesión       | ✅ JWT         |
| POST   | `/refresh`  | Renovar token       | ✅ JWT         |


📁 Proyectos
| Método | Endpoint         | Descripción         | Autenticación |
| ------ | ---------------- | ------------------- | ------------- |
| GET    | `/projects`      | Listar proyectos    | ✅ JWT         |
| POST   | `/projects`      | Crear proyecto      | ✅ JWT         |
| GET    | `/projects/{id}` | Ver un proyecto     | ✅ JWT         |
| PUT    | `/projects/{id}` | Actualizar proyecto | ✅ JWT         |
| DELETE | `/projects/{id}` | Eliminar proyecto   | ✅ JWT         |


✅ Tareas
| Método | Endpoint      | Descripción      | Autenticación |
| ------ | ------------- | ---------------- | ------------- |
| GET    | `/tasks`      | Listar tareas    | ✅ JWT         |
| POST   | `/tasks`      | Crear tarea      | ✅ JWT         |
| GET    | `/tasks/{id}` | Ver tarea        | ✅ JWT         |
| PUT    | `/tasks/{id}` | Actualizar tarea | ✅ JWT         |
| DELETE | `/tasks/{id}` | Eliminar tarea   | ✅ JWT         |



