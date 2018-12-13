<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 12/12/18
 * Time: 13:07
 */

require_once 'Tabla.php';
class Profesor extends Tabla
{
    private $id_profesor;
    private $usuario;
    private $num_fields = 2;

    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("profesor", "id_profesor", $fields);
    }

    //SETTERS Y GETTERS

    /**
     * @return mixed
     */
    public function getId_Profesor()
    {
        return $this->id_profesor;
    }

    /**
     * @param mixed $id_profesor
     */
    public function setId_Profesor($id_profesor)
    {
        $this->id_profesor = $id_profesor;
    }

    /**
     * @return mixed
     */
    public function getUsuario() : Usuario
    {
        return $this->usuario;
    }

    /**
     * @param mixed $id_usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     *Getter
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
     * Función que nos devuelve un registro de la base de datos si conicide con el id que le pasamos
     * @param $id
     * @throws Exception
     */
    public function loadById($id)
    {
        $profesor = $this->getById($id);

        if (!empty($profesor)) {
            $this->id_profesor = $id;
            $this->usuario = $id;
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
    public function updateOrInsert()
    {
        $profesor = $this->valores();
        unset($profesor['id_profesor']);
        $this->usuario->updateOrInsert();
        $profesor["id_usuario"] = $this->usuario->id_usuario;
        unset($profesor["usuario"]);
        if (empty($this->id_profesor)) {
            $this->insert($profesor);
            $this->id_profesor = self::$conn->lastInsertId();
        } else {
            $this->update($this->id_profesor, $profesor);
        }
    }

    /**
     * Función que elimina un registro de la base de datos si conicide el id con el que le pasamos
     * @throws Exception
     */
    public function delete()
    {
        if (!empty($this->id_profesor)) {
            $this->deleteById($this->id_profesor);
            $this->id_profesor = null;
            $this->usuario = null;
        } else {
            throw new Exception("No existe ese registro para borrar");
        }
    }
}