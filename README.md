# CEMOM Odontología - Sitio web (scaffold)

Proyecto simple en PHP/HTML/CSS/JS con autenticación, panel de administración y CRUD para contenido.

Requisitos:
- XAMPP (Apache + MySQL / MariaDB)
- PHP 7.4+

Instalación rápida:
1. Copia este proyecto dentro de `htdocs` (ya está en esa ruta si usas XAMPP).
2. Abre http://localhost/phpmyadmin y crea una base de datos llamada `cemom_db` (puedes usar otro nombre y actualizar `includes/config.php`).
3. Ejecuta `install.php` abriendo http://localhost/webcemom/install.php en tu navegador. Esto creará las tablas y el usuario admin.
4. Accede a http://localhost/webcemom/login.php

Credenciales admin (creadas por `install.php`):
- Email: luciolombi1980@gmail.com
- Contraseña: Lucio009

Notas:
- Las páginas principales requieren login. El panel `admin/` sólo lo puede ver el admin.
- `install.php` debe ejecutarse solo una vez. Después elimínalo o protégelo.
