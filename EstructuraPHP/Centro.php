<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 5/12/18
 * Time: 12:16
 */
require_once 'Tabla.php';

class Centro extends Tabla
{

    private $id_centro;
    private $nif;
    private $nombre;
    private $password;
    private $biografia;
    private $descripcion;
    private $imagen_personal;
    private $email;
    private $id_token;
    private $num_fields = 9;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("centro", "id_centro", $fields);
    }


    /* Setters y Getters */
    public function getId_Token()
    {
        return $this->id_token;
    }

    public function setId_Token($id_token): void
    {
        $this->id_token = $id_token;
    }

    public function getId_Centro()
    {
        return $this->id_centro;
    }

    public function getNif()
    {
        return $this->nif;
    }

    public function setNif($nif): void
    {
        $this->nif = $nif;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    function getPassword()
    {
        return $this->password;
    }

    function setPassword($password): void
    {
        $this->password = $password;
    }

    function getBiografia()
    {
        return $this->biografia;
    }

    function setBiografia($biografia): void
    {
        $this->biografia = $biografia;
    }

    function getDescripcion()
    {
        return $this->descripcion;
    }

    function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    function getImagen_Personal()
    {
        return $this->imagen_personal;
    }

    function setImagen_Personal($imagen_personal): void
    {
        $this->imagen_personal = $imagen_personal;
    }

    function getEmail()
    {
        return $this->email;
    }

    function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Esta función nos devolvera el valor de una propiedad pedida si existe el Getter de esa propiedad
     * @param nombre $name Nombre de la propiedad que queremos recoger
     * @return mixed Nos devuelve el metódo Getter de la propiedad pedida
     * @throws Exception Lanza una excepción si no encuentra el Getter de la propiedad pedida
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
     * @param propiedad $name Nombre de la propiedad que queremos cambiar
     * @param nuevo $value Nuevo valor para la propiedad a cambiar
     * @return Carga la función si existe dentro de la clase
     * @throws Exception Lanza una expeción si no encuentra el Setter de la propiedad pedida
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
     */
    function logIn($email, $password)
    {
        try {

            $resultado = self::$conn->query("select * from " . $this->table . " where email " . " = '" . $email . "' AND password = '" . $password . "'");
            return $resultado->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {

            return $ex->getMessage();

        }
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    function delete()
    {
        if (!empty($this->id_centro)) {
            $this->deleteById($this->id_centro);
            $this->id_centro = null;
            $this->nombre = null;
        } else {
            throw new Exception("No hay registro para borrar");
        }
    }

    /**
     * Función que nos devuelve un registro de la base de datos si conicide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    function loadById($id)
    {
        $centro = $this->getById($id);

        if (!empty($centro)) {
            $this->id_centro = $id;
            $this->nombre = $centro["nombre"];
            $this->nif = $centro["nif"];
            $this->biografia = $centro["biografia"];
            $this->password = $centro["password"];
            $this->descripcion = $centro["descripcion"];
            $this->imagen_personal = $centro["imagen_personal"];
            $this->email = $centro["email"];
        } else {
            throw new Exception("No existe ese registro");
        }
    }

    /**
     * Función que nos devuelve un array associativo, con los datos del objeto de la clase
     * @return array
     */
    private function valores()
    {
        $valores = array_map(function ($v) {
            return $this->$v;
        }, $this->fields);
        return array_combine($this->fields, $valores);
    }

    /**
     * Función que modifica o inserta un registro, dependiendo de la variable idcentro de la clase
     */
    function updateOrInsert()
    {
        $centro = $this->valores();
        unset($centro['id_centro']);
        if (empty($this->id_centro)) { //Si el idcentro de la clase esta vacío, insertaremos un nuevo registro en la base de datos
            $this->insert($centro);
            $this->id_centro = self::$conn->lastInsertId();
        } else { //Si el idcentro de la clase tiene un valor, modificaremos el registro que coincida con el id de la clase en la base de datos
            $this->update($this->id_centro, $centro);
        }
    }

    function registroUsuario($id_centro)
    {
        $user = $this->getAll(['id_centro' => $id_centro]);
        return $user;
    }

    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize()
    {
        return $this->valores();
    }

}