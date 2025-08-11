# Sistema de Gestión de Activos Fijos
<img width="300" height="300" alt="selabi" src="https://github.com/user-attachments/assets/74a2b213-fc92-46cd-b3ed-37a298569ca8" />

Este sistema permite registrar, controlar y reportar los activos fijos de una institución pública o privada, integrando funcionalidades de inventario, movimientos, depreciación y reportes agrupados por partidas presupuestarias.

## 🧩 Características principales

- Registro detallado de activos con codificación institucional
- Control de entradas, salidas y transferencias internas
- Cálculo de depreciación por método lineal
- Reportes agrupados por partida, tipo de activo y ubicación
- Gestión de responsables y ubicaciones físicas
- Exportación de reportes en PDF y Excel

## ⚙️ Tecnologías utilizadas

- Backend: Laravel + PHP-FPM
- Frontend: Vue.js + Axios + Bootstrap 5
- Base de datos: MySQL / PostgreSQL
- Servidor: Nginx + Linux
- Librerías: SweetAlert, DataTables, DomPDF

## 📁 Estructura del proyecto

- `/app`: lógica de negocio y controladores
- `/resources`: vistas y componentes Vue
- `/database`: migraciones y seeders
- `/public`: assets públicos y punto de entrada

## 🚀 Instalación

```bash
git clone https://github.com/usuario/activos-fijos.git
cd activos-fijos
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
