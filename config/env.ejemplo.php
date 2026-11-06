<?php
declare(strict_types=1);
// Copiar a env.php; en hosting completar con sus datos, fuera de Git.
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'puerto' => getenv('DB_PORT') ?: '3306',
    'base' => getenv('DB_NAME') ?: 'bodega',
    'usuario' => getenv('DB_USER') ?: 'root',
    'clave' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
];
