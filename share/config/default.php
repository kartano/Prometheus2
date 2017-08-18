<?php
/**
 * Default configuration file.
 * Anything in this file is really considered SITE SPECIFIC.
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @version         1.0.0           2017-08-16 2017-08-16 Prototype
 */

$config = [
    'app' => [
        'name'  => 'Prometheus2',
        'debug' => true
    ],
    'log' => [
        'class' => 'dblogger',
        'table' => 'prom2_log'
    ],
    'db'  => [
        'host'      => 'localhost',
        'user'      => 'root',
        'pass'      => '',
        'catalogue' => 'prom2',
        'port'      => 3306,
        'socket'    => ini_get("mysqli.default_socket")
    ]
];
