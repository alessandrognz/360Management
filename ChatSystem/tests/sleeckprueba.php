<?php

/*
 * Ejemplo de uso de SleekDB en este proyecto.
 *
 * SleekDB es una base de datos documental ligera que guarda los datos
 * en archivos JSON dentro de una carpeta local. Esto sirve muy bien para
 * pruebas, prototipos o pequeñas aplicaciones sin necesidad de MySQL.
 *
 * En este archivo se muestra el flujo básico:
 * 1. Cargar la librería.
 * 2. Crear un store (equivalente a una colección).
 * 3. Insertar documentos.
 * 4. Consultar datos.
 * 5. Actualizar registros.
 * 6. Eliminar registros.
 */

// Cargamos la clase principal de SleekDB desde la carpeta ChatSystem/SleekDB.
require_once __DIR__ . '/../SleekDB/SleekDB.php';

// Importamos el namespace para usar la clase directamente.
use SleekDB\SleekDB;

// Definimos la carpeta donde SleekDB guardará los archivos JSON.
// __DIR__ apunta a la carpeta actual del archivo: ChatSystem/tests.
$dataDir = __DIR__ . '/data/sleekdb_demo';

// Si la carpeta no existe, la creamos para que SleekDB pueda escribir allí.
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

// Creamos un store llamado "demo_usuarios".
// Cada store funciona como una colección de documentos.
$store = new SleekDB('demo_usuarios', $dataDir);

echo "Probando SleekDB..." . PHP_EOL;

// Insertar un documento individual.
// Cada documento es un arreglo asociativo que se guarda como JSON.
$insertado = $store->insert([
    'nombre' => 'Ana',
    'email' => 'ana@example.com',
    'activo' => true,
]);

echo "Documento insertado: " . print_r($insertado, true) . PHP_EOL;

// Insertar varios documentos a la vez.
// Esto es útil cuando se quiere cargar un grupo de registros de forma rápida.
$store->insertMany([
    ['nombre' => 'Luis', 'email' => 'luis@example.com', 'activo' => true],
    ['nombre' => 'Marta', 'email' => 'marta@example.com', 'activo' => false],
]);

// Consultar documentos con una condición.
// where('activo', '=', true) filtra los registros donde el campo activo sea true.
// fetch() devuelve todos los resultados que coinciden.
$usuariosActivos = $store->where('activo', '=', true)->fetch();

echo "Usuarios activos: " . count($usuariosActivos) . PHP_EOL;

// Recorremos los resultados y los mostramos en pantalla.
foreach ($usuariosActivos as $usuario) {
    echo "- {$usuario['nombre']} ({$usuario['email']})" . PHP_EOL;
}

// Actualizar un documento existente.
// Se busca por nombre y se cambia el campo email.
$store->where('nombre', '=', 'Ana')->update(['email' => 'ana.nueva@example.com']);

// Volvemos a buscar a Ana para comprobar el cambio.
$ana = $store->where('nombre', '=', 'Ana')->first();

echo "Email actualizado de Ana: {$ana['email']}" . PHP_EOL;

// Eliminar un documento que cumpla la condición.
$store->where('nombre', '=', 'Luis')->delete();

echo "Se eliminó a Luis." . PHP_EOL;

// Mostrar todos los documentos que quedan en el store.
$restantes = $store->fetch();

echo "Documentos restantes: " . count($restantes) . PHP_EOL;

foreach ($restantes as $usuario) {
    echo "* {$usuario['nombre']} - {$usuario['email']}" . PHP_EOL;
}

echo "Fin de la prueba." . PHP_EOL;
