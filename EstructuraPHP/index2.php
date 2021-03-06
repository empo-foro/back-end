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

header("Acces-Control-Allow-Headers: X-API-KEY, Origin, X-Request-With, Content-Type, Accept, Access-Control-Request-Method");
header("Acces-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
if($verb == "OPTIONS") {
    die();
}

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
require_once $controller . ".php";
$objeto = new $controller;

/** Utilizaremos un switch para cargar las funciones de los diferentes métodos que recibiremos */
switch ($verb) {
    case("GET"):

        /** Si el parámetro $operacion ha recibido un valor cargaremos la operación con su case correspondiente */
        if (!empty($operacion)) {

            switch ($operacion) {

                case ("getAlumnoByToken") :

                    if(get_class($objeto) == "Usuario") {

                        $id_token=filter_input(INPUT_GET, "id_token");

                        if (!empty($id_token)){

                            $datos= $objeto->getAlumnoByToken($id_token);

                            $http->setHttpHeaders(200, new Response("ID Alumno", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay alumnos disponibles", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("getCountByToken") :

                    if (get_class($objeto) == "Usuario") {

                        $id_token=filter_input(INPUT_GET, "id_token");

                        if (!empty($id_token)){

                            $datos= $objeto->getCountByToken($id_token);

                            $http->setHttpHeaders(200, new Response("Counts", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay registros disponibles", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;
                case ("getUserAsignaturaByToken"):
                    if (get_class($objeto) =="Asignatura") {

                        $id_token=filter_input(INPUT_GET, "id_token");

                        if (!empty($id_token)){
                            require_once 'Usuario.php';
                            $u=new Usuario();
                            $user = $u->checkToken($id_token);
                            $datos = $objeto->getUserAsignaturaByToken($id_token, $user[0]["tipo"]);

                            $http->setHttpHeaders(200, new Response("Listado de asignaturas de un usuario", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay asignaturas disponibles", false));

                        }
                    }

                    else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("getUserRespuestasByToken") :

                    if (get_class($objeto) == "Respuesta") {

                        $id_token = filter_input(INPUT_GET, "id_token");

                        if (!empty($id_token)) {

                            $datos = $objeto->getUserRespuestasByToken($id_token);

                            $http->setHttpHeaders(200, new Response("Listado de respuestas de un usuario", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay respuestas disponibles", false));

                        }
                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("getPostByUserToken") :

                    if (get_class($objeto) == "Post") {

                        $id_token = filter_input(INPUT_GET, "id_token");

                        if (!empty($id_token)) {

                            $datos = $objeto->getPostByUserToken($id_token);

                            $http->setHttpHeaders(200, new Response("Listado de post de un usuario", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay post disponibles", false));

                        }
                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("getUsuarioByToken"):

                    if (get_class($objeto) == "Usuario") {

                        $id_token = filter_input( INPUT_GET, "id_token");

                        if (!empty($id_token)) {

                            $datos = $objeto ->getUsuarioByToken($id_token);

                            $http->setHttpHeaders(200, new Response("Datos usuario", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay usuarios registrados", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                        break;

                case ("etiquetasPost"):

                    if (get_class($objeto) == "Post_vs_Tema") {

                        $id_post = filter_input(INPUT_GET, "id_post");

                        if (!empty($id_post)) {

                            $datos = $objeto->etiquetasPost($id_post);

                            $http->setHttpHeaders(200, new Response("Listado de etiquetas", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay etiquetas disponibles", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("listarUsuarios"):

                    if (get_class($objeto) == "Usuario") {

                        $tipo = filter_input(INPUT_GET, "tipo");

                        if (!empty($tipo)) {

                            $datos = $objeto->listarUsuarios($tipo);

                            $http->setHttpHeaders(200, new Response("Listado de usuarios", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay datos disponibles", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }
                    break;

                case ("asignaturaPost"):

                    if (get_class($objeto) == "Post") {

                        $id_post = filter_input(INPUT_GET,"id");

                        if (!empty($id_post)){

                            $datos = $objeto->asignaturaPost($id_post);

                            $http->setHttpHeaders(200, new Response("Listado  de post", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay post disponibles", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("comentariosPost"):

                    if (get_class($objeto) == "Respuesta") {

                        $id_post = filter_input(INPUT_GET, "id_post");

                        if (!empty($id_post)){

                            $datos = $objeto->comentariosPost($id_post);

                            $http->setHttpHeaders(200, new Response("Listado de comentarios", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("No hay comentarios disponibles", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

                    }

                    break;

                case ("logOut"):

                    if (get_class($objeto) == "Usuario" || get_class($objeto) == "Centro") {

                        $id_token = filter_input(INPUT_GET, "id_token");

                        if (!empty($id_token)) {

                            $datos = $objeto->logOut($id_token);

                            $http->setHttpHeaders(200, new Response("Sesión finalizada", true));

                        } else {

                            $http->setHttpHeaders(400, new Response("Parámetro token no recibido", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logOut", $controller));

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
                case("registro-usuarios"):

                    if (!empty($_FILES) && !empty($_POST['tipo'])) {

                        if (realpath($_FILES["fichero"]["tmp_name"])) {

                            require_once "Usuario.php";
                            require_once $_POST["tipo"] . ".php";

                            $array = file($_FILES["fichero"]["tmp_name"]);

                            foreach ($array as $datos) {

                                $dato = explode(";", $datos);
                                $nif = $dato[0];
                                $nombre = $dato[1];
                                $password = $dato[2];
                                $email = $dato[3];

                                $u = new Usuario();
                                $u->nif = $nif;
                                $u->nombre = $nombre;
                                $u->password = $password;
                                $u->tipo = $_POST["tipo"];
                                $u->email = $email;
                                $u->id_centro = 1;

                                if (!empty($_POST['id_curso']) && ($_POST['tipo'] == "Alumno")) {

                                    $u->updateOrInsert();
                                    $type = new Alumno();
                                    $type->id_usuario = $u->id_usuario;
                                    $type->id_curso = $_POST["id_curso"];
                                    $type->updateOrInsert();

                                } elseif (!empty($_POST['id_curso']) && $_POST['tipo'] == "Profesor") {

                                    $u->updateOrInsert();
                                    $type = new Profesor();
                                    $type->id_usuario = $u->id_usuario;

                                } else {
                                    $http->setHttpHeaders(200, new Response("El tipo de usuario es incorrecto"));
                                }
                            }
                        } else {
                            $http->setHttpHeaders(400, new Response("Error"));
                        }
                    } else {
                        $http->setHttpHeaders(400, new Response("No se ha recibido los datos necesarios para un registro"));
                    }

                    break;
                case ("logIn"):

                    if (get_class($objeto) == "Usuario" || get_class($objeto) == "Centro") {

                        $datos = file_get_contents("php://input");
                        $raw = json_decode($datos);

                        $datos = $objeto->logIn($raw->email, $raw->password);

                        /** Si los datos eran correctos devolveremos un token, en caso contrario devolveremos un error */
                        if (!empty($datos)) {

                            $objeto = new $controller;

                            if (get_class($objeto) == "Usuario") {

                                $objeto->loadById($datos[0]['id_usuario']);

                            } elseif (get_class($objeto) == "Centro") {

                                $objeto->loadById($datos['id_centro']);

                            }

                            $objeto->setId_Token(bin2hex(random_bytes(50)));
                            $objeto->updateOrInsert();

                            $http->setHttpHeaders(200, new Response("Datos de inicio de sesión correctos", $objeto->serialize()));

                        } else {
                            $http->setHttpHeaders(400, new Response("Datos de inicio de sesión incorrectos"));
                        }
                    } else {
                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación logIn", $controller));
                    }

                    break;

                case ("checkToken"):

                    if (get_class($objeto) == "Usuario" || get_class($objeto) == "Centro") {

                        $datos = file_get_contents("php://input");
                        $raw = json_decode($datos);

                        $datos = $objeto->checkToken($raw->id_token);

                        if (!empty($datos)) {

                            $http->setHttpHeaders(200, new Response("Token correcto", true));

                        } else {

                            $http->setHttpHeaders(400, new Response("Token incorrecto", false));
                        }

                    } else {
                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación checkToken", $controller));
                    }
                    break;

               case ("registroUsuario"):

                    if (get_class($objeto) == "Usuario") {

                        $datos = file_get_contents("php://input");
                        $raw = json_decode($datos);

                        $usuario = $raw[0];
                        $datosTipo = $raw[1];

                        if($usuario->tipo === "Alumno" || $usuario->tipo === "Profesor") {

                            $result = $objeto->registroUsuario($usuario, $datosTipo);

                            if (!empty($result)) {

                                $http->setHttpHeaders(200, new Response("Registro correcto", $result->serialize()));

                            } else {

                                $http->setHttpHeaders(400, new Response("Registro incorrecto", false));

                            }

                        } else {

                            $http->setHttpHeaders(400, new Response("Tipo de usuario incorrecto tiene que ser Alumno o Profesor", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación registroUsuario", $controller));
                    }

                    break;

                case ("getAsignaturas"):

                    if (get_class($objeto) == "Usuario") {

                        $datos = file_get_contents("php://input");
                        $raw = json_decode($datos);

                        $datos = $objeto->getAsignaturas($raw->id_token);

                        if (!empty($datos)) {

                            $http->setHttpHeaders(200, new Response("Listado de asignaturas", $datos));

                        } else {

                            $http->setHttpHeaders(400, new Response("Este usuario no tiene asignaturas", false));

                        }

                    } else {

                        $http->setHttpHeaders(400, new Response("El controlador indicado no contiene la operación registroUsuario", $controller));

                    }
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

                $objeto->delete();

            } catch (Exception $ex) {
                $http->setHttpHeaders(400, new Response($ex->getMessage()));
                die();
            }

            $http->setHttpHeaders(200, new Response("Registro borrado correctamente"));

        } else {
            $http->setHttpHeaders(400, new Response("El identificador introducido no existe"));
        }

        break;
    default:
        $http->setHttpHeaders(405, new Response("El método no es válido", $verb));
}
/*
 * Cifrar contraseña centros
 * */
