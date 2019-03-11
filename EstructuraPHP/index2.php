<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 8/3/19
 * Time: 13:19
 */
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 6/2/19
 * Time: 12:16
 */

require_once 'Http.php';
require_once 'Response.php';

/** @var String $verb Recoge el método con el que han enviado la petición */
$verb = $_SERVER['REQUEST_METHOD'];

/** @var String $controller Tabla con la que vamos a trabajar */
$controller = filter_input(INPUT_GET, "controller");
/** @var String $operacion Parámetro opcional si queremos realizar una operación especial */
$operacion = filter_input(INPUT_GET, "operacion");
/** @var Number $id Parámetro opcional con el valor identificador de una tabla */
$id = filter_input(INPUT_GET, "id");

$http = new HTTP();

/** Comprobamos que el valor que recogemos a través de $controller es una clase que exista, en caso contrario devolveríamos un error */
if (empty($controller) || !file_exists($controller . ".php")) {
    $http->setHttpHeaders(400, new Response("El controlador no existe", $controller));
    die();
}

/** Añadimos la clase y creamos un objeto de la misma */
require $controller . ".php";
$objeto = new $controller;

/** Utilizaremos un switch para cargar las funciones de los diferentes métodos que recibiremos */
switch ($verb) {
    case("GET"):

        /** Si el parámetro $operacion ha recibido un valor cargaremos la operación con su case correspondiente */
        if (!empty($operacion)) {
            switch ($operacion) {
                case ("logIn"):

                    if (get_class($objeto) == "Usuario" || get_class($objeto) == "Centro") {

                        /** @var String $email Parámetro que recibiremos a través de la petición */
                        $email = filter_input(INPUT_GET, "email");
                        /** @var String $password Parámetro que recibiremos a través de la petición */
                        $password = filter_input(INPUT_GET, "password");
                        /** @var Object $datos Utilizaremos al función logIn */
                        $datos = $objeto->logIn($email, $password);

                        /** Si los datos eran correctos devolveremos un token, en caso contrario devolveremos un error */
                        if (!empty($datos)) {
                            //Aquí tendríamos que devolver el token cuando este implementado

                            $u = new $controller;
                            $u->loadById($datos[0]['id_usuario']);
                            $u->setId_Token(bin2hex(random_bytes(50)));
                            $u->updateOrInsert();


                            $http->setHttpHeaders(200, new Response("Datos de inicio de sesión correctos", $u->serialize()));
                        } else {
                            $http->setHttpHeaders(400, new Response("Datos de inicio de sesión incorrectos"));
                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logIn", $controller));

                    }
                    break;
                default:
                    $http->setHttpHeaders(400, new Response("La operación indicada no existe"));
            }
        } else {
            /** En caso de recibir una petición GET sin el parámetro opcional $id devolveremos todos los registros de la clase, en caso contrario solo devolveremos el registro del $id indicado */
            if (!empty($id)) {

                $objeto->loadById($id);
                $http->setHttpHeaders(200, new Response("Registro $id", $objeto->serialize()));

            } else {

                $datos = $objeto->getAll();
                $http->setHttpHeaders(200, new Response("Lista $controller", $datos));

            }
        }
        break;

    case("POST"):
        if (!empty($operacion)) {
            switch ($operacion) {
                case("registro-usuario"):

                    break;
                default:
                    $http->setHttpHeaders(400, new Response("La operación indicada no existe"));
            }
        } else {

            $raw = file_get_contents("php://input");
            /** Comprobaremos que nos han pasado algo a través de la petición, en caso contrario devolveremos un error */
            if (!empty($raw)) {

                $datos = json_decode($raw);
                foreach ($datos as $campo => $valor) {
                    $objeto->$campo = $valor;
                }

                $objeto->updateOrInsert();
                $http->setHttpHeaders(200, new Response(get_class($objeto) . " creado", $objeto->serialize()));
                // $http->setHttpHeaders(400, new Response("Ha ocurrido un error al crear"));

            } else {
                $http->setHttpHeaders(400, new Response("No has enviado datos"));
            }

        }
        break;

    case("PUT"): //Nos permite poner un campo NOT NULL a NULL preguntar!

        if (!empty($id)) {

            /** Cargaremos los datos del objeto si no existe un registro con el $id indicado devolveremos un error */
            try {
                $objeto->loadById($id);
            } catch (Exception $ex) {
                $http->setHttpHeaders(400, new Response($ex->getMessage()));
                die();
            }

            $raw = file_get_contents("php://input");
            /** Comprobaremos que nos han pasado algo a través de la petición, en caso contrario devolveremos un error */
            if (!empty($raw)) {

                $datos = json_decode($raw);
                foreach ($datos as $campo => $valor) {
                    $objeto->$campo = $valor;
                }

                $objeto->updateOrInsert();
                $http->setHttpHeaders(200, new Response(get_class($objeto) . " actualizado", $objeto->serialize()));
                // $http->setHttpHeaders(400, new Response("Ha ocurrido un error al actualizar"));

            } else {
                $http->setHttpHeaders(400, new Response("No has enviado datos"));
            }
        } else {
            $http->setHttpHeaders(400, new Response("Error", "El identificador introducido no existe"));
        }

        break;
    case("DELETE"):

        if (!empty($id)) {

            /** Cargaremos los datos del objeto si no existe un registro con el $id indicado devolveremos un error */
            try {
                $objeto->loadById($id);
            } catch (Exception $ex) {
                $http->setHttpHeaders(400, new Response($ex->getMessage()));
                die();
            }

            $objeto->delete();
            $http->setHttpHeaders(200, new Response("Registro borrado correctamente"));

        } else {
            $http->setHttpHeaders(400, new Response("El identificador introducido no existe"));
        }

        break;
    default:
        $http->setHttpHeaders(405, new Response("El método no es válido", $verb));
}