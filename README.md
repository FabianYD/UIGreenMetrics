# Green Metric 🌿

<div style="background-color: #4CAF50; padding: 20px; border-radius: 5px; color: white;">
Sistema de gestión para métricas ambientales desarrollado con Laravel y PostgreSQL.
</div>

## 🌱 Requisitos Previos

- PHP >= 8.1
- Composer
- PostgreSQL >= 14
- Node.js y NPM (para assets)

## 🌿 Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/tu-usuario/UIGreenMetrics.git
cd UIGreenMetrics
```

2. Instalar dependencias de PHP:
```bash
composer install
```

3. Copiar el archivo de configuración:
```bash
cp .env.example .env
```

4. Configurar la base de datos en el archivo `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
DB_DATABASE=nombre_de_tu_base_de_datos
```

5. Generar la clave de la aplicación:
```bash
php artisan key:generate
```

6. Crear la base de datos y ejecutar las migraciones:
```bash
php artisan db:create
php artisan migrate --seed
```

## 🔐 Credenciales por Defecto

Después de ejecutar los seeders, podrás acceder con las siguientes credenciales:

- **Email:** admin@admin.com
- **Contraseña:** 123456

## 🗄️ Estructura de la Base de Datos

El sistema utiliza PostgreSQL y cuenta con las siguientes tablas principales:

- `gm_wec_campus`: Gestión de campus universitarios
- `gm_wec_facultad`: Facultades de la universidad
- `gm_wec_periodos`: Períodos de medición
- `gm_wec_usuarios`: Gestión de usuarios
- `gm_wec_roles`: Roles y permisos
- Y más tablas relacionadas con métricas ambientales

## 💻 Desarrollo

Para desarrollo local, puedes usar el servidor de Laravel:

```bash
php artisan serve
```

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.
