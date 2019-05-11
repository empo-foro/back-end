<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 10/12/18
 * Time: 13:04
 */
require_once 'Tabla.php';
require_once 'Centro.php';
require_once 'Profesor.php';
require_once 'Alumno.php';

class Usuario extends Tabla
{
    private $id_usuario;
    private $nif;
    private $nombre;
    private $password;
    private $tipo;
    private $imagen_personal;
    private $email;
    private $biografia;
    private $centro;
    private $id_token;
    private $num_fields = 10;

    /**
     * Usuario constructor.
     */
    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("usuario", "id_usuario", $fields);
    }

    /* Getters y Setters */

    public function getId_Token()
    {
        return $this->id_token;
    }

    public function setId_Token($id_token): void
    {
        $this->id_token = $id_token;
    }

    public function getId_Usuario()
    {
        return $this->id_usuario;
    }

    public function setId_Usuario($id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNif()
    {
        return $this->nif;
    }

    public function setNif($nif): void
    {
        $this->nif = $nif;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getImagen_Personal()
    {
        return $this->imagen_personal;
    }

    public function setImagen_Personal($imagen_personal): void
    {
        $this->imagen_personal = $imagen_personal;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getBiografia()
    {
        return $this->biografia;
    }

    public function setBiografia($biografia): void
    {
        $this->biografia = $biografia;
    }

    public function getCentro()
    {
        return $this->centro;
    }

    public function setCentro($centro): void
    {
        $this->centro = $centro;
    }

    public function setId_Centro($id_centro): void
    {
        $centro = new Centro();
        $centro->loadById($id_centro);
        $this->centro = $centro;
    }

    /**
     * Esta función nos devolverá el valor de una propiedad pedida si existe el get de esa propiedad
     * @param String $name Nombre de la propiedad que queremos recoger
     * @return mixed Nos devuelve el método get de la propiedad pedida
     * @throws Exception Lanza una excepción si no encuentra el get de la propiedad
     */
    function __get($name)
    {
        $metodo = "get$name";
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        } else {
            throw new Exception("Propiedad desconocida");
        }
    }

    /**
     * Esta función cambiara el valor de una propiedad pedida si existe el Setter de esa propiedad
     * @param mixed $name Nombre de la propiedad que queremos cambiar
     * @param mixed $value Nuevo valor para la propiedad a cambiar
     * @return mixed Carga la función si existe dentro de la clase
     * @throws Exception Lanza una excepción si no encuentra el Setter de la propiedad pedida
     */
    function __set($name, $value)
    {
        $metodo = "set$name";
        if (method_exists($this, $metodo)) {
            return $this->$metodo($value);
        } else {
            throw new Exception("Propiedad desconocida");
        }
    }

    /**
     * Función que nos devuelve un registro si el usuario se ha encontrado dentro del la base de datos
     * @param $email
     * @param $password
     * @return string
     * @throws
     */
    function logIn($email, $password)
    {
        $user = $this->getAll(['email' => $email]);

        $u = new Usuario();
        $u->loadById($user[0]["id_usuario"]);
        if(password_verify($password, $u->password)) {
            return $user;
        } else {
            throw new Exception("Password incorrecto");
        }
    }

    /**
     * Función que nos devuelve un registro de la base de datos si conicide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    function loadById($id)
    {
        $usuario = $this->getById($id);

        if (!empty($usuario)) {
            $this->id_usuario = $id;
            $this->nombre = $usuario["nombre"];
            $this->nif = $usuario["nif"];
            $this->biografia = $usuario["biografia"];
            $this->password = $usuario["password"];
            $this->tipo = $usuario["tipo"];
            $this->imagen_personal = $usuario["imagen_personal"];
            $this->email = $usuario["email"];

            $centro = new Centro();
            $centro->loadById($usuario['id_centro']);
            $this->centro = $centro;

        } else {
            throw new Exception("No existe un registro con este identificador");
        }
    }

    /**
     * Función que nos devuelve un array associativo, con los datos del objeto de la clase
     * @return array
     */
    private function valores()
    {
        $valores = array_map(function ($v) { //If v es un objeto tenemos que sacar el valor id de ese objeto
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

    /**
     * Función que modifica o inserta un registro, dependiendo de la variable idcentro de la clase
     */
    function updateOrInsert()
    {
        $usuario = $this->valores();
        unset($usuario['id_usuario']);

        //$this->centro->updateOrInsert();
        $usuario["id_centro"] = $this->centro->id_centro;
        unset($usuario["centro"]);

        if (empty($this->id_usuario)) {

            $this->insert($usuario);
            $this->id_usuario = self::$conn->lastInsertId();


        } else {
            $this->update($this->id_usuario, $usuario);
        }

        return $this;
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    function delete()
    {
        if (!empty($this->id_usuario)) {
            $this->deleteById($this->id_usuario);
            $this->id_usuario = null;
            $this->nif = null;
            $this->biografia = null;
            $this->password = null;
            $this->tipo = null;
            $this->imagen_personal = null;
            $this->email = null;
            $this->centro = null;
            $this->id_token = null;
        } else {
            throw new Exception("No existe ese registro para borrar");
        }
    }

    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize()
    {
        return $this->valores();
    }

    /**
     * Función que busca y borra el token del usuario que ha cerrado sesión
     * @param $id_token String Token del usuario
     * @throws Exception Si no encuentra el token lanzamos la excepción que nos devuelve MySQL
     */
    function logOut($id_token)
    {
        $user = $this->getAll(['id_token' => $id_token]);
        $u = new Usuario();
        $u->loadById($user[0]["id_usuario"]);
        $u->id_token = null;
        $u->updateOrInsert();
    }

    /**
     * Método con el que comprobamos que un usuario este logueado dentro de la aplicación
     * @param $id_token String Token del usuario
     * @return mixed Object Datos del usuario logueado
     */
    function checkToken($id_token)
    {
        $user = $this->getAll(['id_token' => $id_token]);
        return $user;

    }

    /**
     * @param $id_token
     * @return mixed
     * Función que devuelve las asignaturas del usuario logueado
     */
    function getAsignaturas($id_token)
    {

        $resultado = self::$conn->query("select asignatura.* from asignatura inner join alumno on asignatura.id_curso = alumno.id_curso
                INNER JOIN usuario on alumno.id_usuario = usuario.id_usuario
                where usuario.id_token = '" . $id_token . "'");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @param $tipo
     * @return mixed
     * Función que lista los usuarios según su tipo
     */
    function listarUsuarios($tipo)
    {

        $user = $this->getAll(['tipo' => $tipo]);
        return $user;

    }

    /**
     * @param $u
     * @param $data
     * @return bool
     * Función que registra un usuario como alumno o profesor según su tipo
     */
    function registroUsuario($u, $data)
    {

        $user = new Usuario();

        foreach ($u as $campo => $valor) {
            if ($campo == "password") {
                $user->$campo = password_hash($valor, PASSWORD_DEFAULT);
            } else {
                $user->$campo = $valor;
            }
        }

        $user->updateOrInsert();

        if (!empty($user)) {

            if ($user->tipo === "Alumno") {

                $a = new Alumno();
                $a->setId_Curso($data->id_curso);
                $a->setId_Usuario($user->id_usuario);
                $a->updateOrInsert();

                return true;

            } else if ($user->tipo === "Profesor") {

                $p = new Profesor();
                $p->setId_Usuario($user->id_usuario);
                $p->updateOrInsert();

                return true;

            }

        }

    }

    /**
     * @param $id_token
     * @return mixed
     * Función que te devuelve el usuario según el token indicado
     */
    function getUsuarioByToken($id_token)
    {

        $user = $this->getAll(['id_token' => $id_token]);
        return $user;

    }

    /**
     * Función que te devulve un count de  los post creados y las respuestas de un usuario logueado
     */

    function getCountByToken($id_token){

        //Post
        $resultado = self::$conn->query("SELECT COUNT(*) as posts FROM post inner join 
        alumno on post.id_alumno = alumno.id_alumno inner join 
        usuario on alumno.id_usuario = usuario.id_usuario where usuario.id_token = '" . $id_token . "'");
        $countPost = $resultado->fetchAll(PDO::FETCH_ASSOC);

        //Respuesta
        $resultado = self::$conn->query("SELECT COUNT(*) as respuestas FROM respuesta inner join 
        post on respuesta.id_post = post.id_post inner join
        alumno on post.id_alumno = alumno.id_alumno inner join 
        usuario on alumno.id_usuario = usuario.id_usuario where usuario.id_token = '" . $id_token . "'");
        $countRespuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);

        $array = array_merge($countPost, $countRespuesta);
        return $array;

    }

    /**
     * @param $id_token
     * @return mixed
     * Función que te devuelve la ID del alumno según el token del usuario.
     */
    function getAlumnoByToken($id_token){

        $resultado = self::$conn->query("select id_alumno from Alumno inner join 
        usuario on alumno.id_usuario = usuario.id_usuario where usuario.id_token = '" . $id_token . "'");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
}