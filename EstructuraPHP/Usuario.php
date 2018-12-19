<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 10/12/18
 * Time: 13:04
 */
require_once 'Tabla.php';

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
    private $num_fields = 9;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Usuario", "id_usuario", $fields);
    }

    //GETTERS Y SETTERS

    /**
     * @return mixed
     */
    public function getId_Usuario()
    {
        return $this->id_usuario;
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
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getImagenPersonal()
    {
        return $this->imagen_personal;
    }

    /**
     * @param mixed $imagen_personal
     */
    public function setImagenPersonal($imagen_personal): void
    {
        $this->imagen_personal = $imagen_personal;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBiografia()
    {
        return $this->biografia;
    }

    /**
     * @param mixed $biografia
     */
    public function setBiografia($biografia): void
    {
        $this->biografia = $biografia;
    }

    /**
     * @return mixed
     */
    public function getCentro()
    {
        return $this->centro;
    }

    public function setCentro($centro): void
    {
        $this->centro = $centro;
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

            $resultado = self::$conn->query("select * from " . $this->table . " where nif " . " = " . $nif . "AND password = " . $password);
            return $resultado->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {

            return $ex->getMessage();

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
            throw new Exception("No existe ese registro");
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
        $this->centro->updateOrInsert();
        $usuario["id_centro"] = $this->centro->id_centro;
        unset($usuario["centro"]);
        if (empty($this->id_usuario)) {
            $this->insert($usuario);
            $this->id_usuario = self::$conn->lastInsertId();
        } else {
            $this->update($this->id_usuario, $usuario);
        }
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
        } else {
            throw new Exception("No existe ese registro para borrar");
        }
    }

}