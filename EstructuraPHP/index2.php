<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 8/3/19
 * Time: 13:19
 */

switch ($verb) {
    case("GET"):

        break;

    case("POST"):

        break;

    case("PUT"):

        if(!empty($id)) {

            $objeto->loadById($id);

            if(!empty($objeto)) {

                $raw = file_get_contents("php://input");
                $datos = json_decode($raw);
                foreach ($datos as $campo => $valor) {
                    $objeto->$campo = $valor;
                }

            } else {
                $http->setHttpHeaders(400, new Response("Error", "No existe un registro con este identificador"));
            }

        } else {
            $http->setHttpHeaders(400, new Response("Error", "El identificador introducido no existe"));
        }

        break;
    case("DELETE"):

        if(!empty($id)) {

            $objeto->loadById($id);

            if(!empty($objeto)) {

                $objeto->delete();
                $http->setHttpHeaders(200, new Response("Success", "Borrado correctamente"));

            } else {
                $http->setHttpHeaders(400, new Response("Error", "No existe un registro con este identificador"));
            }

        } else {
            $http->setHttpHeaders(400, new Response("Error", "El identificador introducido no existe"));
        }

        break;
    default:

        $http->setHttpHeaders(405, new Response("El método no es válido"));
}