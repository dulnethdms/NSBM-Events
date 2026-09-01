<?php
// NSBM EventHub backend configuration. Override these with environment variables in production.
return [
    'host' => getenv('EVENTHUB_DB_HOST') ?: '127.0.0.1',
    'db' => getenv('EVENTHUB_DB_NAME') ?: 'nsbm_eventhub',
    'user' => getenv('EVENTHUB_DB_USER') ?: 'root',
    'pass' => getenv('EVENTHUB_DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];
