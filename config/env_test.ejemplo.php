<?php
declare(strict_types=1);
return [
    'host' => getenv('TEST_DB_HOST') ?: '127.0.0.1',
    'puerto' => getenv('TEST_DB_PORT') ?: '3306',
    'base' => 'bodega_test',
    'usuario' => getenv('TEST_DB_USER') ?: 'root',
    'clave' => getenv('TEST_DB_PASS') !== false ? getenv('TEST_DB_PASS') : '',
];
