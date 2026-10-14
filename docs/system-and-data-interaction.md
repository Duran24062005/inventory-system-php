Actualmente el proyecto tiene la conexión PostgreSQL, pero todavía no tiene modelos, migraciones ni repositorios. [`Database::connection()`]( /var/www/html/inventory-system/src/Config/Database.php:15) solo crea y devuelve un objeto `PDO`.

La estructura recomendada sería:

```text
database/
└── migrations/
    └── 001_create_products.sql

src/
├── Models/
│   └── Product.php
├── Repositories/
│   └── ProductRepository.php
├── Services/
│   └── ProductService.php
└── Controllers/
    └── ProductController.php
```

El flujo sería:

```text
Ruta
  ↓
Controller
  ↓
Service
  ↓
Repository
  ↓
Database::connection()
  ↓
PostgreSQL
```

## 1. Crear una migración

Por ejemplo, `database/migrations/001_create_products.sql`:

```sql
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    price NUMERIC(12, 2) NOT NULL CHECK (price >= 0),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
```

La aplicación actualmente no tiene sistema de migraciones. Inicialmente puedes ejecutar el archivo con `psql`, usando una base local o una conexión revisada explícitamente:

```bash
psql "$DATABASE_URL" -f database/migrations/001_create_products.sql
```

No conviene ejecutar esto contra una base remota sin confirmar primero el entorno.

## 2. Crear el modelo

`src/Models/Product.php`:

```php
<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Models;

final readonly class Product
{
    public function __construct(
        public ?int $id,
        public string $sku,
        public string $name,
        public float $price,
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            sku: (string) $row['sku'],
            name: (string) $row['name'],
            price: (float) $row['price'],
        );
    }
}
```

El modelo representa un producto. No debería contener consultas SQL.

## 3. Crear el repositorio

`src/Repositories/ProductRepository.php`:

```php
<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Repositories;

use AlexiDg\InventorySystem\Models\Product;
use PDO;

final class ProductRepository
{
    public function __construct(
        private readonly PDO $database,
    ) {
    }

    /**
     * @return Product[]
     */
    public function all(): array
    {
        $statement = $this->database->query(
            'SELECT id, sku, name, price
             FROM products
             ORDER BY id DESC'
        );

        return array_map(
            static fn (array $row): Product => Product::fromRow($row),
            $statement->fetchAll()
        );
    }

    public function create(string $sku, string $name, float $price): Product
    {
        $statement = $this->database->prepare(
            'INSERT INTO products (sku, name, price)
             VALUES (:sku, :name, :price)
             RETURNING id, sku, name, price'
        );

        $statement->execute([
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
        ]);

        return Product::fromRow($statement->fetch());
    }
}
```

El repositorio es el dueño de las consultas SQL. Siempre utiliza consultas preparadas para valores recibidos desde el usuario.

## 4. Crear el controlador

```php
<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Controllers;

use AlexiDg\InventorySystem\Config\Database;
use AlexiDg\InventorySystem\Http\Response;
use AlexiDg\InventorySystem\Repositories\ProductRepository;

final class ProductController
{
    public function index(): Response
    {
        $repository = new ProductRepository(Database::connection());

        return Response::json([
            'products' => $repository->all(),
        ]);
    }
}
```

Después registrarías la ruta en [`public/index.php`]( /var/www/html/inventory-system/public/index.php:44):

```php
use AlexiDg\InventorySystem\Controllers\ProductController;

$router->get('/products', [new ProductController(), 'index']);
```

## 5. Para un CRUD completo

El router actual solo soporta `GET` en [`Router.php`]( /var/www/html/inventory-system/src/Http/Router.php:14). Para crear un CRUD tendrás que añadir:

- `POST /products`
- `PUT /products/{id}`
- `DELETE /products/{id}`

También será necesario añadir al objeto `Request`:

- lectura del cuerpo de la petición;
- decodificación JSON;
- validación;
- parámetros de ruta.

El orden recomendable es:

1. `GET /products`.
2. `GET /products/{id}`.
3. `POST /products`.
4. `PUT /products/{id}`.
5. `DELETE /products/{id}`.
6. Validaciones y manejo de errores.
7. Transacciones para operaciones que cambien varias tablas.

## 6. Inventario y ventas

Para inventario no conviene guardar únicamente un número de stock sin historial. Una estructura más trazable sería:

```text
products
inventory_movements
sales
sale_items
```

Cada entrada o salida crearía un movimiento:

```text
product_id
type: in | out | adjustment
quantity
reason
created_at
```

Una venta debería ejecutarse dentro de una transacción:

```php
$database->beginTransaction();

try {
    // Crear venta.
    // Crear líneas de venta.
    // Registrar salida de inventario.

    $database->commit();
} catch (\Throwable $exception) {
    $database->rollBack();
    throw $exception;
}
```

La idea principal es mantener las responsabilidades separadas:

- `Model`: representa datos.
- `Repository`: consulta PostgreSQL.
- `Service`: coordina reglas y transacciones.
- `Controller`: recibe HTTP y devuelve respuestas.
- `View`: muestra HTML.

Para este proyecto empezaría implementando primero `Product`, `ProductRepository`, la migración y `GET /products`. Después añadiría la creación mediante `POST`.
