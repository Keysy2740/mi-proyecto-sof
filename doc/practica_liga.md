# Documentación técnica: Gestión de Liga de Básquetbol

## 1. Arquitectura general

Aplicación web en **PHP nativo (8.0)** con **MySQL** (XAMPP) y acceso a datos mediante **PDO**. No usa framework: se implementó un patrón **MVC propio** con tres capas.

- **Modelos** (`modelos/`): ejecutan las consultas SQL.
- **Controladores** (`controladores/`): validan las reglas del negocio y llaman a los modelos.
- **Vistas** (archivos `.php` de la raíz): solo muestran HTML.

Flujo de una petición: vista → controlador → modelo → base de datos. La conexión está en `config/database.php` y el esquema en `database/schema.sql`: tablas `usuarios`, `equipos`, `jugadores` y `partidos`, con claves foráneas y `ON DELETE CASCADE`.

```
liga-basquet/
├── config/         conexión a la base de datos
├── controladores/  lógica y validaciones
├── modelos/        consultas SQL
├── includes/       cabecera y pie compartidos
├── database/       esquema SQL
├── doc/            documentación
└── img/            logo e imágenes
```

## 2. Clases implementadas

| Capa | Clases y responsabilidad |
|---|---|
| Modelos | `Equipo`, `Jugador`, `Partido`: CRUD de cada tabla. `Estadistica`: calcula posiciones. `Resumen`: totales, próximos partidos y últimos resultados |
| Controladores | `EquipoControlador`, `JugadorControlador`, `PartidoControlador`, `EstadisticaControlador`, `ResumenControlador` |

Las vistas comparten `includes/cabecera.php` (menú público o privado según la sesión) y `includes/pie.php`, evitando repetir código.

## 3. Funcionalidades

- **Autenticación:** registro e ingreso con sesiones de PHP y cierre de sesión. Las páginas de gestión redirigen al ingreso si no hay sesión.
- **Equipos:** crear, editar, listar y eliminar.
- **Jugadores:** pertenecen a un equipo, con filtro por equipo y número de camiseta único dentro del mismo equipo.
- **Partidos:** pueden estar pendientes (sin puntos) o finalizados. No se permiten empates ni un equipo contra sí mismo.
- **Posiciones:** partidos jugados, ganados, perdidos, puntos a favor, en contra y diferencia. Es pública.
- **Seguridad:** contraseñas cifradas, consultas preparadas, `htmlspecialchars` al mostrar datos y eliminación por POST con confirmación.
- **Portada pública:** próximos partidos, resultados, posiciones, equipos, noticias y páginas Nosotros y Noticias.

## 4. Fragmentos relevantes

La contraseña nunca se guarda en texto plano:

```php
$hash = password_hash($contrasena, PASSWORD_DEFAULT);
password_verify($contrasena, $usuario['contrasena']);
```

La regla del número de camiseta vive en el modelo y se usa desde el controlador:

```php
SELECT id FROM jugadores WHERE equipo_id = ? AND numero = ? AND id <> ?
```

El `id <> ?` evita que, al editar, el jugador choque consigo mismo. Todas las consultas usan parámetros (`?`) contra inyección SQL. Las posiciones unen los partidos como local y como visitante con `UNION ALL`: cada equipo obtiene una fila por partido con sus puntos a favor y en contra, que luego se suman con `COUNT` y `SUM`.

## 5. Problemas encontrados y soluciones

1. **Conexión entre Git y GitHub.** No lograba vincular el repositorio local. Se configuraron `user.name` y `user.email`, se verificó el remoto y se hizo el primer `git push`. Luego se trabajó con ramas, pull requests y merge.
2. **MySQL apagado.** phpMyAdmin mostraba "el equipo de destino denegó expresamente dicha conexión". MySQL no estaba iniciado; se resolvió con **Start** en XAMPP.
3. **Error en posiciones** (`Reference 'pf' not supported`). MySQL no admite alias de sumas dentro de una operación en `ORDER BY`; se repitieron las sumas completas.
4. **Estilos sin actualizar.** El navegador usaba el CSS guardado; se resolvió con recarga forzada (`Ctrl + Shift + R`).

## 6. Decisiones técnicas

- **PHP nativo en lugar de Laravel.** Era solo sugerido; no tenía experiencia con el framework y el PHP de XAMPP (8.0) no cumple los requisitos de las versiones actuales. Se creó una capa propia de modelos y controladores.
- **PDO con consultas preparadas**, por seguridad.
- **Reglas de negocio en controladores**, no en vistas.
- **Limitación conocida:** registro e ingreso consultan la base directamente, sin modelo `Usuario`.

## 7. Ejecución y usuario de prueba

1. Iniciar Apache y MySQL en XAMPP.
2. Importar `database/schema.sql` en phpMyAdmin.
3. Abrir `http://localhost/liga-basquet/`.

**Usuario de prueba** (incluido en el esquema): correo `123mario@gmail.com.pe`, contraseña `Mario123`.

## 8. Herramientas de apoyo

Se usó un asistente de inteligencia artificial para resolver errores y explicar conceptos. Las imágenes provienen de Unsplash y Pexels.

## 9. Conclusión

El problema más difícil fue **conectar Git con GitHub**: no tenía mucha experiencia con control de versiones y me costó configurarlo. Una vez resuelto, el trabajo con ramas y pull requests fue más claro. También aprendí a separar modelo, controlador y vista, lo que permitió reutilizar la lógica en varias páginas.
