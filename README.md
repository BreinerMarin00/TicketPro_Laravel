# TicketPro - Versión Laravel

Sistema web de gestión de tickets de soporte técnico. Este repositorio contiene la
migración al framework **Laravel 13** de dos módulos del proyecto TicketPro, que
originalmente se desarrolló con PHP, JavaScript y CSS sin framework.

Evidencia **GA7-220501096-AA3-EV01** - Codificación de módulos del software stand-alone, web y móvil.

## Módulos implementados

| Módulo | Descripción | Rutas |
|---|---|---|
| Inicio de sesión | Acceso con usuario o correo, opción "Recordarme", validación y mensajes de error, cierre de sesión. | `GET /login`, `POST /login`, `POST /logout` |
| Pantalla principal | Panel de tickets con contadores, filtros, creación de tickets en ventana modal, cambio de estado y eliminación. | `GET /dashboard`, `POST /tickets`, `PATCH /tickets/{id}/estado`, `DELETE /tickets/{id}` |

## Tecnologías

- PHP 8.3 o superior
- Laravel 13 (patrón MVC, Eloquent ORM, Blade, middleware `auth` y `guest`)
- SQLite (por defecto) o MySQL
- HTML, CSS y JavaScript (mismo diseño del proyecto original)
- PHPUnit para pruebas automáticas
- Laravel Pint para el estándar de codificación (PSR-12)
- Git para el control de versiones

## Estructura principal

```
app/
  Http/Controllers/
    AuthController.php        -> Módulo de inicio de sesión
    DashboardController.php   -> Pantalla principal
    TicketController.php      -> Crear, cambiar estado y eliminar tickets
  Http/Requests/
    LoginRequest.php          -> Validación del login
    StoreTicketRequest.php    -> Validación de nuevo ticket
  Models/
    Usuario.php               -> Tabla usuarios
    Ticket.php                -> Tabla tickets
database/
  migrations/                 -> Estructura de la base de datos
  seeders/DatabaseSeeder.php  -> Usuario y tickets de prueba
resources/views/
  layouts/app.blade.php       -> Plantilla base
  auth/login.blade.php        -> Vista de inicio de sesión
  dashboard.blade.php         -> Vista del panel principal
public/css, public/js         -> Estilos y scripts
routes/web.php                -> Rutas de la aplicación
tests/Feature/                -> Pruebas de los dos módulos
```

## Instalación

```bash
git clone <url-del-repositorio> TicketPro-Laravel
cd TicketPro-Laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Abrir http://127.0.0.1:8000 e ingresar con:

- **Usuario:** `admin` (o `admin@ticketpro.com`)
- **Contraseña:** `admin123`

### Usar MySQL en lugar de SQLite

En el archivo `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticketpro_laravel
DB_USERNAME=root
DB_PASSWORD=
```

Luego crear la base de datos vacía y ejecutar `php artisan migrate --seed`.

## Pruebas y estándar de código

```bash
php artisan test        # 14 pruebas de los módulos de login y panel
vendor/bin/pint --test  # verifica el estándar de codificación
```
