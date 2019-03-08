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
    if (empty($id)) {
        $datos = $objeto->getAll();
        $http->setHttpHeaders(200, new Response("Lista $controller",$datos));
    } else {
        $objeto->loadById($id);
        $http->setHttpHeaders(200, new Response("Lista $controller",$objeto->serialize()));
    }
} else if ($verb == "POST") {
    $raw=file_get_contents("php://input");
    $datos=json_decode($raw);
    foreach($datos as $c=>$v){
        $objeto->$c=$v;
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
} else if($verb == "DELETE") {
    if (empty($id)) {
        $http->setHttpHeaders(400, new Response("Bad request"));
        die();
    }
    $objeto->load($id);
    $objeto->delete();
}