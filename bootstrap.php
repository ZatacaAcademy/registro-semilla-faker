<?php

use Faker\Generator;

// ¡RECUERDA EJECUTAR ESTE FICHERO AÑADIÉNDOLO A LA PROPIEDAD 'bootstrap' DE <phpunit> EN EL FICHERO DE CONFIGURACIÓN PHPUNIT.XML! (ej: bootstrap="tests/Utils/bootstrap.php")

require_once __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$consoleKernel = $app->make(Kernel::class);
$consoleKernel->bootstrap();

$semilla = random_int(1, 1000000);
$faker = $app->make(Generator::class);
$faker->seed($semilla);
registrarSemillaFaker($semilla);

function registrarSemillaFaker(int $semilla): void
{
    // Asegurarse de que el directorio existe
    $directorioLog = storage_path('logs/faker');
    if (!is_dir($directorioLog)) {
        mkdir($directorioLog, 0777, true);
    }

    // Agregar la nueva información de la semilla al final del archivo
    $nuevaLinea = "Día y hora: " . date('d-m-Y H:i:s') . ", Semilla de Faker: " . $semilla . "\n";
    file_put_contents($directorioLog . '/last_faker_seeds.log', $nuevaLinea, FILE_APPEND);
}


/*
// A DIFERENCIA DEL ANTERIOR CÓDIGO, SÓLO REGISTRA LAS ÚLTIMAS 30 SEMILLAS

$semilla = random_int(1, 1000000);
$faker = $app->make(Generator::class);
$faker->seed($semilla);
registrarSemillaFaker($semilla);

function registrarSemillaFaker(int $semilla): void
{
    $directorioLog = storage_path('logs/faker');
    $archivoLog = $directorioLog . '/last_faker_seeds.log';
    $maximoLineas = 30;
    $fechaHoraActual = date('d-m-Y H:i:s');

    // Asegurarse de que el directorio existe
    if (!is_dir($directorioLog)) {
        mkdir($directorioLog, 0777, true);
    }

    // Verificar si el archivo de log existe y leer su contenido
    $lineas = file_exists($archivoLog) ? file($archivoLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    // Eliminar la primera línea si se excede el número máximo de líneas
    if (count($lineas) >= $maximoLineas) {
        array_shift($lineas);
    }

    // Añadir la nueva información de la semilla al final de las líneas
    $lineas[] = "Día y hora: {$fechaHoraActual}, Semilla de Faker: {$semilla}";

    // Escribir las líneas actualizadas de nuevo en el archivo
    file_put_contents($archivoLog, implode("\n", $lineas));
}
*/
