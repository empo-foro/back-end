<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 10/12/18
 * Time: 13:04
 */

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
    private $id_centro;


    //GETTERS Y SETTERS

    /**
     * @return mixed
     */
    public function getIdUsuario()
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
    public function getIdCentro()
    {
        return $this->id_centro;
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


    function loadById($id)
    {
        // TODO: Implement loadById() method.
    }

    function updateOrInsert()
    {
        // TODO: Implement updateOrInsert() method.
    }

    function delete()
    {
        // TODO: Implement delete() method.
    }

}