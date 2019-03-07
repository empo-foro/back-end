<?php
/**
 * Created by PhpStorm.
 * User: DAW
 * Date: 12/12/18
 * Time: 13:07
 */

require_once 'Tabla.php';
require_once 'Usuario.php';
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

    /* Setters y Getters */

    public function getId_Profesor()
    {
        return $this->id_profesor;
    }

    public function setId_Profesor($id_profesor)
    {
        $this->id_profesor = $id_profesor;
    }

    public function getUsuario() : Usuario
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
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
     * Función que carga los datos de un registro dentro del objeto
     * @param $id Id del registro que queremos recoger
     * @throws Exception Lanza un expeción si no encuentra un registro con el id indicado
     */
    public function loadById($id)
    {
        $profesor = $this->getById($id);

        if (!empty($profesor)) {
            $this->id_profesor = $id;

            $u=new Usuario();
            $u->loadById($profesor['id_usuario']);
            $this->usuario = $u;
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
     * Función que inserta o modifica un registro
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
     * Función que elimina un registro, si el campo id del objeto coincide con el id de un registro en la base de datos
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

    /**
     * Función que llamamos desde la REST para devolver los valores cuando cogan al objeto por su id
     * @return array Devuelve un Array asociativo con los datos del objeto
     */
    function serialize() {
        return $this->valores();
    }

}