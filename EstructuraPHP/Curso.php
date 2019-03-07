<?php
/**
 * Created by PhpStorm.
 * User: daw
 * Date: 19/12/18
 * Time: 12:54
 */
require_once 'Tabla.php';
require_once 'Centro.php';
class Curso extends Tabla
{
    private $id_curso;
    private $nombre;
    private $centro;
    private $num_fields = 3;

    /**
     * Curso constructor.
     */
    public function __construct()
    {
        $fields = array_slice(array_keys(get_object_vars($this)), 0, $this->num_fields);
        parent::__construct("Curso", "id_curso", $fields);

    }

    /* Getters y Setters */

    public function getId_Curso()
    {
        return $this->id_curso;
    }

    public function setId_Curso($id_curso): void
    {
        $this->id_curso = $id_curso;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }


    public function getCentro()
    {
        return $this->centro;
    }

    public function setCentro($centro): void
    {
        $this->centro = $centro;
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
    function loadById($id)
    {

        $curso = $this->getById($id);

        if(!empty($curso)) {

            $this->id_curso = $id;
            $this->nombre = $curso['nombre'];

            $centro = new Centro();
            $centro->loadById($curso['id_centro']);
            $this->centro = $centro;

        } else {

            throw new Exception("No existe un registro con ese id");

        }

    }

    /**
     * Función que nos devuelve un array asociativo con los datos del objeto de la clase
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
     * Función que inserta o modifica un registro
     */
    function updateOrInsert()
    {
        $curso = $this->valores();
        unset($curso['id_curso']);
        $this->centro->updateOrInsert();
        $curso['id_centro'] = $this->centro->id_centro;
        unset($curso['centro']);
        if(empty($this->id_curso)) {
            $this->insert($curso);
            $this->id_curso = self::$conn->lastInsertId();
        } else {
            $this->update($this->id_curso, $curso);
        }
    }

    /**
     * Función que elimina un registro, si el campo id del objeto coincide con el id de un registro en la base de datos
     */
    function delete()
    {
        if(!empty($this->id_curso)) {
            $this->deleteById($this->id_curso);
            $this->id_curso = null;
            $this->nombre = null;
        } else {
            throw new Exception("Este curso no existe dentro de la base de datos");
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