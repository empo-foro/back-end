<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 6/2/19
 * Time: 12:16
 */

require_once 'Http.php';
require_once 'Response.php';

$controller = filter_input(INPUT_GET, "controller"); //Tabla con la que va a trabajar
$operacion = filter_input(INPUT_GET, "operacion");
$id = filter_input(INPUT_GET, "id");

$verb = $_SERVER['REQUEST_METHOD'];
$http = new HTTP();

if (empty($controller) || !file_exists($controller . ".php")) {
    $http = new HTTP();
    $http->setHttpHeaders(400, new Response("Bad request"));
    die();
}

require $controller . ".php";
$objeto = new $controller;

if ($verb == "GET") {
    if ($operacion == "logIn") {

        /*$objeto->login($raw['email'], $raw["password"]);
        $raw = file_get_contents("php://input");
        $json = json_decode($raw);*/

        $email = filter_input(INPUT_GET, "email");
        $password = filter_input(INPUT_GET, "password");

        /*$email = $json->email;
        $password = $json->password;*/

        $datos = $objeto->logIn($email, $password);

        if (!empty($datos)) {

            $http->setHttpHeaders(200, new Response("Success",  "Datos de inicio de sesión correctos"));

        } else {

            $http->setHttpHeaders(400, new Response("Error", "Datos de inicio de sesión incorrectos"));

        }

/*
        if ($raw["tipo"] == $centro) {
            if (!empty($email) && !empty($password)) {
                if (!empty($usuarios = $usuarios_tabla->getAll(["email" => $email, "password" => $password]))) {
                    session_start();
                    foreach ($usuarios as $usuario) {
                        $_SESSION["id"] = $usuario["id_centro"];

                        var_dump($centro);
                    }

                } else {
                    throw new Exception("Email o contraseña incorrecto");
                }
            }
        } else if ($raw["tipo"] == $usuario) {
            if (!empty($email) && !empty($password)) {
                if (!empty($usuarios = $usuarios_tabla->getAll(["email" => $email, "password" => $password]))) {
                    session_start();
                    foreach ($usuarios as $usuario) {
                        $_SESSION["id"] = $usuario["id_usuario"];

                        var_dump($usuario);
                    }
                } else {
                    throw new Exception("Email o contraseña incorrecto");
                }
            }
        } else {
            throw new Exception("No existe este usuario");
        }
    */
    }

} else if ($verb == "POST") {
    $raw = file_get_contents("php://input");
    $datos = json_decode($raw);
    foreach ($datos as $c => $v) {
        $objeto->$c = $v;
    }
    //var_dump($objeto);
    $objeto->updateOrInsert();
    $http->setHttpHeaders(200, new Response("Success"));

} else if ($verb == "PUT") {
    if (empty($id)) {
        $http->setHttpHeaders(400, new Response("Bad request"));
        die();
    }
    $objeto->loadById($id);
    $raw = file_get_contents("php://input");
    $datos = json_decode($raw);
    foreach ($datos as $c => $v) {
        $objeto->$c = $v;
    }
    $objeto->updateOrInsert();
} else if ($verb == "DELETE") {
    if (empty($id)) {
        $http->setHttpHeaders(400, new Response("Bad request"));
        die();
    }
    $objeto->load($id);
    $objeto->delete();
}