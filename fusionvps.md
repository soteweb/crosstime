# Guía de Fusión de Datos entre VPS: Servidor Temporal vs Servidor Principal

Este documento detalla el procedimiento técnico para unificar los eventos, categorías e inscripciones generados en el nuevo VPS temporal (OVH Cloud) con la base de datos histórica recuperada del VPS anterior (Contabo), sin pérdida de datos ni conflictos de identificadores (IDs).

---

## 1. Contexto del Escenario

* **VPS Anterior (Contabo - Principal/Histórico):** Contiene todo el historial de la plataforma: eventos pasados, categorías, miles de atletas registrados, inscripciones históricas, clubes, páginas CMS (como *"Quiénes somos"*), menús y configuraciones generales.
* **VPS Nuevo (OVH Cloud - Temporal / Actual):** Se configuró tras la caída del servidor anterior para continuar operando de inmediato. En este entorno se albergarán aproximadamente **4 eventos activos** con sus respectivas inscripciones, pagos y asignaciones de dorsales.
* **Objetivo:** Cuando el VPS anterior sea restaurado o se recupere su respaldo SQL completo, anexar estos 4 eventos y sus inscriptos a la base de datos principal sin sobreescribir ni duplicar registros.

---

## 2. El Reto Técnico (¿Por qué no usar un volcado SQL plano?)

1. **Colisión de Claves Primarias (`id`):**
   * En el VPS temporal, los nuevos eventos comenzaron con IDs bajos (`id = 1, 2, 3, 4`).
   * En la base de datos histórica, esos IDs ya están ocupados por eventos de años anteriores.
2. **Integridad Referencial y Relaciones:**
   * Las tablas `event_distances`, `event_categories`, `registrations` y `payments` dependen de claves foráneas (`event_id`, `athlete_id`, `category_id`).
   * Si los IDs de los eventos cambian al fusionarse, todas las relaciones secundarias deben remapearse con exactitud.
3. **Duplicidad de Atletas:**
   * Un atleta que ya estaba en el VPS histórico puede volverse a inscribir en estos 4 eventos nuevos. No debe crearse un duplicado del atleta, sino asociar la nueva inscripción a su perfil preexistente.

---

## 3. Estrategia de Solución: Exportación e Importación Inteligente (JSON)

La técnica más limpia y segura consiste en desacoplar los datos de los IDs numéricos internos mediante un script de Laravel que opere por **Claves de Negocio**:

* **Para Atletas:** Se unifican mediante la Cédula de Identidad (`identity_document`).
* **Para Eventos:** Se identifican mediante su `slug` o `title`.
* **Para Categorías y Distancias:** Se reconstruyen vinculadas al evento en memoria.

---

## 4. Procedimiento Paso a Paso Cuando se Recupere el VPS Anterior

### Fase A: En el VPS Nuevo (Origen de los 4 eventos)
Se ejecuta un comando de exportación que extrae los 4 eventos específicos con toda su descendencia relacional en un archivo JSON estructurado:

```bash
cd /www/wwwroot/itapuacross.com
php artisan events:export-json [ID_EVENTO_1,ID_EVENTO_2,ID_EVENTO_3,ID_EVENTO_4]
```
*(Generará un archivo `export_eventos_nuevos.json` conteniendo el evento, distancias, categorías, atletas relacionados, inscripciones, dorsales y estados de pago)*.

### Fase B: En el VPS Principal Recuperado (Destino)
1. Se transfiere el archivo `export_eventos_nuevos.json` al servidor principal.
2. Se ejecuta el comando de importación y fusión:

```bash
cd /www/wwwroot/itapuacross.com
php artisan events:import-json export_eventos_nuevos.json
```

---

## 5. Lógica de Ejecución del Script de Fusión

El proceso interno ejecutado por Laravel garantiza que:

1. **Atletas (`athletes`):**
   * Busca si existe un atleta con el mismo `identity_document`.
   * Si existe: **no lo duplica**, reutiliza su `id` existente y actualiza datos de contacto si son más recientes.
   * Si no existe: lo crea nuevo con el siguiente `id` disponible en la base de datos principal.

2. **Eventos (`events`):**
   * Inserta cada uno de los 4 eventos al final de la tabla `events` (asignándoles nuevos IDs correlativos, por ejemplo: `#36`, `#37`, `#38`, `#39`).
   * Conserva fechas, descripción, sedes, organizadores y datos bancarios.

3. **Distancias y Categorías (`event_distances`, `event_categories`):**
   * Se crean enlazadas al nuevo ID que recibió el evento correspondiente en el servidor principal.

4. **Inscripciones y Pagos (`registrations`, `payments`):**
   * Se insertan enlazando el nuevo `event_id`, la nueva `category_id` y el `athlete_id` (sea histórico o nuevo).
   * Se preservan el estado del pago, número de comprobante, dorsal asignado (`bib_number`) y fecha de inscripción real.

---

## 6. Recomendaciones Mientras se Opera en el VPS Nuevo

* **Operar con normalidad:** Puedes crear los 4 eventos, habilitar inscripciones públicas, recibir pagos y registrar atletas sin preocupaciones.
* **No alterar la estructura:** No modificar columnas de base de datos directamente en MySQL para mantener 100% la compatibilidad entre ambos esquemas de Laravel.
* **Archivos adjuntos (Comprobantes y Fotos):** Cuando se realice la fusión, copiar la carpeta `storage/app/public` del VPS nuevo al VPS recuperado para trasladar las imágenes de los nuevos eventos y comprobantes de pago subidos.
