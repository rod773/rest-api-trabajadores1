<?php

/*
Plugin Name: REST API trabajadores 1
Description: Este plugin agrega un endpoint a la API REST de WordPress para manipular datos de la tabla de trabajadores.
Version: 1.0
Author: Rodrigo
*/

include 'functions_trabajadores.php';

define('PLUGIN_PATH', plugin_dir_path(__FILE__));

function crear_tabla_trabajadores()
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    // Definir la estructura de la tabla
    $sql = "CREATE TABLE $tabla_trabajadores (
        dni VARCHAR(255) NOT NULL,
        nombre VARCHAR(255),
        apellido VARCHAR(255),
        usuario VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        password VARCHAR(50),
        token VARCHAR(255),
        fechaini VARCHAR(50),
        fechafin VARCHAR(50),
        isadmin integer(50) default 0,
        PRIMARY KEY (dni)
    )";
    // Incluir el archivo necesario para ejecutar dbDelta()
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    // Crear o modificar la tabla en la base de datos
    dbDelta($sql);
}

function crear_tabla_jornadas()
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';

    // Definir la estructura de la tabla
    $sql = "CREATE TABLE $tabla_jornadas (
        id INT NOT NULL,
        dniTrabajador VARCHAR(255) NOT NULL,
        fecha VARCHAR(50),
        horaInicio VARCHAR(50),
        pausaInicio VARCHAR(50),
        pausaFin VARCHAR(50),
        horaFin VARCHAR(50),
        PRIMARY KEY (id)
        
    )";
    // Incluir el archivo necesario para ejecutar dbDelta()
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    // Crear o modificar la tabla en la base de datos
    dbDelta($sql);
}

function handle_preflight()
{
    $origin = '*';

    header('Access-Control-Allow-Origin: '.$origin);
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, X-WP-Nonce, Content-Type, Accept, Authorization');
    if ('OPTIONS' == $_SERVER['REQUEST_METHOD']) {
        status_header(200);
        exit;
    }
}

register_activation_hook(__FILE__, 'crear_tabla_trabajadores');
register_activation_hook(__FILE__, 'crear_tabla_jornadas');

add_action('init', 'handle_preflight');

add_action('rest_api_init', 'registrar_endpoint_rest_trabajadores');