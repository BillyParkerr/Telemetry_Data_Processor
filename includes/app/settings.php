<?php
const APP_ROOT = __DIR__;

$url_root = $_SERVER['SCRIPT_NAME'];
$url_root = implode('/', explode('/', $url_root, -1));
$css_path = $url_root . '/css/homepage.css';

define('CSS_PATH', $css_path);

$settings = [
  "settings" => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'doctrine' => [
        'dev_mode' => true,
        'cache_dir' => APP_ROOT . '/var/doctrine',
        'metadata_dirs' => [APP_ROOT . '/src/model'],

        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'port' => 3306,
            'dbname' => 'telemetry_data_processor_db',
            'user' => 'telemetrydataprocessor_user',
            'password' => 'telemetrydataprocessor_user_pass',
            'charset' => 'utf8mb4'
        ]
    ],

    'mode' => 'development',
    'debug' => true,
    'view' => [
      'template_path' => __DIR__ . '/templates/',
      'twig' => [
        'cache' => false,
        'auto_reload' => true
      ],
    ],
  ]
];

return $settings;
