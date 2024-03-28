<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken{
    
    public function __construct()
    {
       require_once PLUGIN_PATH.'/vendor/autoload.php';
    }
}

// Función para obtener un trabajador por ID
function obtener_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $dni = $request['dni'];
    // Obtener el trabajador de la base de datos
    $trabajador = $wpdb->get_row("SELECT * FROM $tabla_trabajadores WHERE dni = $dni");

    return $trabajador;
}

function obtener_trabajadores($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $sql = "SELECT * FROM $tabla_trabajadores";
    $trabajadores = $wpdb->get_results($sql);

    wp_send_json($trabajadores);
}

// Función signup  trabajador

function signup_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $dni = $request->get_param('dni');
    $nombre = $request->get_param('nombre');
    $apellido = $request->get_param('apellido');
    $usuario = $request->get_param('usuario');
    $email = $request->get_param('email');
    $password = $request->get_param('password');

   

    $iss = $_SERVER['SERVER_NAME'];
    $sub = $usuario;
    $iat = time();
    $exp = time() + (60 * 60 * 24);

    $payload = [
        'iss' => $iss,
        'sub' => $sub,
        'iat' => $iat,
        'exp' => $exp,
    ];
    
    $key = '90481cd8c9b821da4a6f8a6aa72b4867b71986555819865f522111e71052e3ef';

    $jwt = JWT::encode($payload, $key, 'HS256');

    $jwt_token = $jwt;

    //print_r($jwt_token);

    // Insertar el trabajador en la base de datos

    $sql = "insert into $tabla_trabajadores(dni,nombre,apellido,usuario,email,password,token) values ('$dni','$nombre','$apellido','$usuario','$email','$password','$jwt_token')";

   //  var_dump($sql);

    if ($wpdb->query($sql)) {
        wp_send_json([
            'inserted' => true,
            'email' => $email,
            'token' => $jwt_token,
        ]);
    } else {
        wp_send_json(['inserted' => false]);
    }
}

// Función login trabajador

function login_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $email = $request->get_param('email');
    $password = $request->get_param('password');

    $sql = "select token from $tabla_trabajadores where email='$email'";

    if ($wpdb->query($sql)) {
        $query = $wpdb->get_row($sql);
        $token = $query->token;
        wp_send_json([
            'email' => $email,
            'token' => $token,
        ]);
    } else {
        wp_send_json([
            'error' => 'user not found',
        ]);
    }
}

// Función para crear un trabajador
function crear_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $dni = $request->get_param('dni');
    $nombre = $request->get_param('nombre');
    $apellido = $request->get_param('apellido');
    $usuario = $request->get_param('usuario');
    $email = $request->get_param('email');
    $password = $request->get_param('password');
    $fechaini = $request->get_param('fechaini');
    $fechafin = $request->get_param('fechafin');

    // Insertar el trabajador en la base de datos

    $sql = "insert into $tabla_trabajadores(dni,nombre,apellido,usuario,email,password,fechaini,fechafin) values ('$dni','$nombre','$apellido','$usuario','$email','$password','$fechaini','$fechafin')";

    if ($wpdb->query($sql)) {
        wp_send_json(['inserted' => true]);
    } else {
        wp_send_json(['inserted' => false]);
    }
}

// Función para actualizar un trabajador por ID
function actualizar_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $dni = $request->get_param('dni');

    $dni = $request->get_param('dni');
    $nombre = $request->get_param('nombre');
    $apellido = $request->get_param('apellido');
    $usuario = $request->get_param('usuario');
    $email = $request->get_param('email');
    $password = $request->get_param('password');
    $fechaini = $request->get_param('fechaini');
    $fechafin = $request->get_param('fechafin');

    $sql = "update $tabla_trabajadores set nombre='$nombre', apellido='$apellido',
    usuario='$usuario',email='$email',password='$password',fechaini='$fechaini',fechafin='$fechafin'
    where dni='$dni'";

    if ($wpdb->query($sql)) {
        wp_send_json(['updated' => true]);
    } else {
        wp_send_json(['updated' => false]);
    }
}
// Función para eliminar un trabajador por ID
function eliminar_trabajador($request)
{
    global $wpdb;
    $tabla_trabajadores = $wpdb->prefix.'trabajadores';
    $dni = $request->get_param('dni');

    if ($wpdb->delete($tabla_trabajadores, ['dni' => $dni])) {
        wp_send_json(['deleted' => true]);
    } else {
        wp_send_json(['deleted' => false]);
    }
}

// ==============================JORNADAS======================//

// ==========================funciones=========================//

function obtener_jornadas($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';

    $sql = "SELECT * FROM $tabla_jornadas";
    $jornadas = $wpdb->get_results($sql);

    wp_send_json($jornadas);
}

// Función para crear un jornada
function crear_jornada($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';

    $jornada = [
    'id' => $request->get_param('id'),
    'dniTrabajador' => $request->get_param('dniTrabajador'),
    'fecha' => $request->get_param('fecha'),
    'horaInicio' => $request->get_param('horaInicio'),
    'pausaInicio' => $request->get_param('pausaInicio'),
    'pausaFin' => $request->get_param('pausaFin'),
    'horaFin' => $request->get_param('horaFin'),
    ];

    if ($wpdb->insert($tabla_jornadas, $jornada)) {
        wp_send_json(['inserted' => true]);
    } else {
        wp_send_json(['inserted' => false]);
    }
}

// Función para eliminar un jornada por ID
function eliminar_jornada($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';
    $id = $request->get_param('id');

    $sql = "delete from $tabla_jornadas where id=$id";

    if ($wpdb->query($sql)) {
        wp_send_json(['deleted' => true]);
    } else {
        wp_send_json(['deleted' => false]);
    }
}

function actualizar_jornada($request)
{
    global $wpdb;
    $tabla_jornadas = $wpdb->prefix.'jornadas';
    $id = $request->get_param('id');
    $newid = $request->get_param('newid');
    $dniTrabajador = $request->get_param('dniTrabajador');
    $fecha = $request->get_param('fecha');
    $horaInicio = $request->get_param('horaInicio');
    $pausaInicio = $request->get_param('pausaInicio');
    $pausaFin = $request->get_param('pausaFin');
    $horaFin = $request->get_param('horaFin');

    if ($id === $newid) {
        $sql = "update $tabla_jornadas set 
    dniTrabajador='$dniTrabajador',
    fecha='$fecha',
    horaInicio='$horaInicio',
    pausaInicio = '$pausaInicio',
    pausaFin = '$pausaFin',
    horaFin='$horaFin' where id=$id";
    } else {
        $sql = "update $tabla_jornadas set id=$newid,
    dniTrabajador='$dniTrabajador',
    fecha='$fecha',
    horaInicio='$horaInicio',
    pausaInicio = '$pausaInicio',
    pausaFin = '$pausaFin',
    horaFin='$horaFin' where id=$id";
    }

    $result = $wpdb->query($sql);

    // print_r($result);

    if ($result) {
        wp_send_json(['updated' => true]);
    } else {
        wp_send_json(['updated' => false]);
    }
}

// =========================AUTH============================//

function generar_token($request)
{
    global $wpdb;

    $dni = $request->get_param('dni');

    $usuario = $request->get_param('usuario');

    $email = $request->get_param('email');

    $arr = ['alg' => 'HS256', 'typ' => 'JWT'];
    $arr2 = json_encode($arr);
    $encoded_header = urlsafeB64Encode($arr2);

    $iss = $_SERVER['SERVER_NAME'];
    $sub = $usuario;
    $iat = time();
    $exp = time() + (60 * 60 * 24);

    $arr3 = [
        'iss' => $iss,
        'sub' => $sub,
        'iat' => $iat,
        'exp' => $exp,
    ];
    $arr33 = json_encode($arr3);
    $encoded_payload = urlsafeB64Encode($arr33);

    $segments = [];

    $segments[] = $encoded_header;

    $segments[] = $encoded_payload;

    $header_payload = implode('.', $segments);

    $secret_key = '90481cd8c9b821da4a6f8a6aa72b4867b71986555819865f522111e71052e3ef';

    $signature = urlsafeB64Encode(hash_hmac('sha256', $header_payload, $secret_key, true));

    $segments[] = $signature;

    $jwt_token = implode('.', $segments);

    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $sql = "update $tabla_trabajadores set token='$jwt_token' where dni='$dni'";

    // var_dump($sql);

    if ($wpdb->query($sql)) {
        wp_send_json([
            'token' => $jwt_token,
            'dni' => $dni,
            'usuario' => $usuario,
            'email' => $email,
            'saved' => true,
        ]);
    } else {
        wp_send_json([
            'token' => $jwt_token,
            'dni' => $dni,
            'usuario' => $usuario,
            'email' => $email,
            'saved' => false,
        ]);
    }
}

function verificar_token($request)
{
    global $wpdb;

    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $tabla_jornadas = $wpdb->prefix.'jornadas';

    $authorization = $request->get_headers()['authorization'][0];

    $len = strlen($authorization);

    $recievedJwt = substr($authorization, 7, $len);

    $secret_key = '90481cd8c9b821da4a6f8a6aa72b4867b71986555819865f522111e71052e3ef';

    $jwt_values = explode('.', $recievedJwt);

    $recieved_signature = $jwt_values[2];

    $segments = [];

    $segments[] = $jwt_values[0];

    $segments[] = $jwt_values[1];

    $recievedHeaderAndPayload = implode('.', $segments);

    $resultedsignature = urlsafeB64Encode(hash_hmac(
        'sha256', $recievedHeaderAndPayload, $secret_key, true));

    $sql = "select * from $tabla_trabajadores where token ='$recievedJwt'";

    $verified = strcmp($recieved_signature, $resultedsignature) === 0;

    if ($verified) {
        $results = $wpdb->get_results($sql);
        // $dni = $results[0]['dni'];
        $res = $results[0];
        $dni = $res->dni;
        $sql = "select * from $tabla_jornadas where dniTrabajador='$dni'";

        $jornadasTrabajador = $wpdb->get_results($sql);

        // foreach ($jornadasTrabajador as $result) {
        //     return $result;
        // }
    }

    wp_send_json([
    'verified' => $verified,
    'trabajador' => $res,
    'jornadasTrabajador' => $jornadasTrabajador,
    ]);
}

function leer_token($request)
{
    global $wpdb;

    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $dni = $request->get_param('dni');

    $email = $request->get_param('email');

    $password = $request->get_param('password');

    $sql = "select token from $tabla_trabajadores where dni='$dni'";

    $result = $wpdb->get_results($sql);

    $recievedJwt = $result[0]->token;

    $secret_key = '90481cd8c9b821da4a6f8a6aa72b4867b71986555819865f522111e71052e3ef';

    $jwt_values = explode('.', $recievedJwt);

    $recieved_signature = $jwt_values[2];

    $segments = [];

    $segments[] = $jwt_values[0];

    $segments[] = $jwt_values[1];

    $recievedHeaderAndPayload = implode('.', $segments);

    $resultedsignature = urlsafeB64Encode(hash_hmac(
        'sha256', $recievedHeaderAndPayload, $secret_key, true));

    $verified = strcmp($recieved_signature, $recieved_signature) === 0;

    if ($verified && is_null($recievedJwt) === false) {
        wp_send_json([
            'token' => $recievedJwt,
        ]);
    } else {
        wp_send_json_error(
            'token not verified',
        );
    }
}

function guardar_token($request)
{
    $dni = $request->get_param('dni');

    $token = $request->get_param('token');

    $tabla_trabajadores = $wpdb->prefix.'trabajadores';

    $sql = "update $tabla_trabajadores set token='$token' where dni='$dni'";

    if ($wpdb->query($sql)) {
        wp_send_json([
            'token saved : ' => true,
        ]);
    } else {
        wp_send_json([
            'token saved : ' => false,
        ]);
    }
}

function urlsafeB64Encode(string $input): string
{
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}

function urlsafeB64Decode(string $input): string
{
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }

    return base64_decode(strtr($input, '-_', '+/'));
}

// Función para registrar el endpoint de la API REST
function registrar_endpoint_rest_trabajadores()
{
    register_rest_route('auth/v1', '/token', [
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'generar_token',
    ]);

    register_rest_route('auth/v1', '/validate', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'verificar_token',
    ]);

    register_rest_route('auth/v1', '/leertoken', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'leer_token',
    ]);

    register_rest_route('trabajadores/v1', '/(?P<dni>\d+)', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'obtener_trabajador',
        'args' => [
            'dni' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                },
            ],
        ],
    ]);

    register_rest_route('trabajadores/v1', '/todos', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'obtener_trabajadores',
    ]);

    register_rest_route('trabajadores/v1', '/add', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'crear_trabajador',
    ]);

    register_rest_route('trabajadores/v1', '/signup', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'signup_trabajador',
    ]);

    register_rest_route('trabajadores/v1', '/login', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'login_trabajador',
    ]);

    register_rest_route('trabajadores/v1', '/update', [
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'actualizar_trabajador',
    ]);
    register_rest_route('trabajadores/v1', '/delete', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'eliminar_trabajador',
    ]);

    register_rest_route('jornadas/v1', '/all', [
         'methods' => WP_REST_Server::READABLE,
         'callback' => 'obtener_jornadas',
    ]);

    register_rest_route('jornadas/v1', '/add', [
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'crear_jornada',
    ]);

    register_rest_route('jornadas/v1', '/delete', [
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'eliminar_jornada',
    ]);

    register_rest_route('jornadas/v1', '/update', [
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'actualizar_jornada',
    ]);
}