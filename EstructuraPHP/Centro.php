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

    private $id_centro; //set y get
    private $nif; //set y get
    private $nombre; //set y get
    private $password; //set
    private $biografia; //set y get
    private $descripcion; //set y get
    private $imagen_personal; //set y get
    private $email; //set y get
    private $num_fields = 8;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("centro", "id_centro", $fields);
    }


    //SETTERS Y GETTERS

    /**
     * @return mixed
     */
    public function getIdCentro()
    {
        return $this->id_centro;
    }

    /**
     * @return mixed
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * @param mixed $nif
     */
    public function setNif($nif): void
    {
        $this->nif = $nif;
    }

    /**
     * @return mixed
     */
    function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    function getBiografia()
    {
        return $this->biografia;
    }

    /**
     * @param mixed $biografia
     */
    function setBiografia($biografia): void
    {
        $this->biografia = $biografia;
    }

    /**
     * @return mixed
     */
    function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    function getImagenPersonal()
    {
        return $this->imagen_personal;
    }

    /**
     * @param mixed $imagen_personal
     */
    function setImagenPersonal($imagen_personal): void
    {
        $this->imagen_personal = $imagen_personal;
    }

    /**
     * @return mixed
     */
    function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Getter
     * @param nombre $name nombre del campo
     * @return mixed valor del campo
     * @throws Exception
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
     * Setter
     * @param propiedad $name nombre del campo
     * @param nuevo $value nuevo valor para el campo
     * @throws Exception
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
     * @param $nif
     * @param $password
     * @return string
     */
    function logIn($nif, $password)
    {
        try {

            $resultado = self::$conn->query("select * from " . $this->table . " where nif " . " = " . $nif. "AND password = " . $password);
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
        if (!empty($this->idcentro)) {
            $this->deleteById($this->idcentro);
            $this->idcentro = null;
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


}