<?php

/*
Plugin Name: REST API trabajadores
Description: Este plugin agrega un endpoint a la API REST de WordPress para manipular datos de la tabla de trabajadores.
Version: 1.0
Author: Rodrigo
*/

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RestApiTrabajadores{

   public function __construct(){
    require_once(plugin_dir_path(__FILE__)."/vendor/autoload.php");
   }

    
}